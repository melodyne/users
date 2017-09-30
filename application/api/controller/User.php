<?php
namespace app\api\controller;


use think\Cache;
use ChuanglanSmsHelper\ChuanglanSmsApi;

use common\controller\ApiBaseController;
use app\api\model\Users as UsersModel;//总用户系统模型
use app\api\model\User as UserModel;//直播用户系统模型
use app\api\model\ThreeAccount as ThreeAccountModel;
use app\admin\model\Subsystem as SubsystemModel;
use think\Loader;
use think\Request;//总用户系统模型

/**
*  用户控制器
*
*/
class User extends ApiBaseController{

    protected $beforeActionList = [
        'loginAuth'  =>  ['only'=>'identityauth,update,point,bindingphone'],
    ];

    /**
     * 单点身份授权认证
     * @return [type] [description]
     */
    public function identityAuth(){
        $appid = paramFromPost('app_id',true);
        $secret = paramFromPost('secret',true);
        $m = SubsystemModel::get($appid);
        if(!$m){
            api(160,"app_id无权限！");
        }
        if($m->secret!=$secret){
            api(160,"secret和app_id不匹配！");
        }
        if($this->user){
            success($this->user);
        }else{
            api(103,"登录过期，请重新登录！");
        }
    }

    /**
     * 用户列表
     */
    public function index(){
        $list = UsersModel::order("create_time desc")->paginate(20);
        foreach ($list as $m){
            $m->hidden(['pwd','access_token']);
            $m->append(['status_text']);
        }
        success($list);
    }

    /**
     * 用户信息
     * @param $id
     */
    public function read($id){
        $user = UsersModel::get($id);
        if($user){
            unset($user->pwd);
            unset($user->wx_unionid);
            unset($user->wx_openid);
            unset($user->access_token);
            success($user);
        }else{
            error("该用户不存在哦！");
        }
    }

    /**
     * 登录
     * @return [type] [description]
     */
    public function login(){

        $account = paramFromPost("account",true);
        $pwd = paramFromPost("pwd",true);

        if($this->user){
            api(101,"你已经登录云宿用户系统！",$this->user);
        }

        if(isPhone($account)){
            $user=UsersModel::getByPhone($account);
        }elseif(isEmail($account)){
            $user=UsersModel::getByEmail($account);
        }else{
            return api(123,"请输入手机号或者邮箱");
        }

        if($user){
            if(md5($pwd)==$user['pwd']){
                $user->access_token = base64_encode(md5(uniqid(rand())).time());
                $user->save();
                $this->doLogin($user);
                api(100,"成功登录云宿用户系统！",$user);
            }else{
                api(103,"密码错误！");
            }
        }else{
			$url="http://www.icloudinn.com:8080/yunsu-mobile-webapp/Server/user/ILogin?account=$account&password=$pwd";
            $lg=httpGet($url);
            $luser=json_decode($lg,true);
            $code=$luser['code'];
            if($luser==null){
                return api(104,"系统未知错误",$lg);
            }
            if($code==0){
                $yunsuId=$luser['data']['yunsuId'];
                $livePhone=$luser['data']['phone'];
                $account=$luser['data']['account'];
                $pwd=$luser['data']['password'];

                if(isPhone($livePhone)){
                    $phone=$livePhone;
                }elseif(isPhone($account)){
                    $phone=$account;
                }else{
                    return api(105,"你的账号存在问题，请联系管理员",$lg);
                }

                //总用户系统
		        $paras = array(
		        	'yunsu_id'=>$yunsuId,
		            'account' =>$phone, 
		            'pwd' =>md5($pwd),
		            'phone' => $phone, 
		            'email' =>"", 
		            );
		        $result = UsersModel::create($paras);

                $user=UsersModel::getByPhone($phone);

                if(!$user){
                	$user=UsersModel::getByAccount($phone);
                }
                $_SESSION['yunsuId']=$yunsuId;
                return api(100,"恭喜你，成功登录云宿用户系统！",$user);

            }elseif($code==1){
                error("请输入正确的手机",$lg);
            }else{
                return api(110,$luser['msg'],$lg);
            }

        }

    }

     /**
     * 通过Api注册
     */
     public function register(){

         $account = paramFromPost("account",true);
         $pwd = paramFromPost("pwd",true);

         if(isPhone($account)){
            $phone=$account;
            $email="";
            $user=UsersModel::getByPhone($account);
         }elseif(isEmail($account)){
            $phone="";
            $email=$account;
            $user=UsersModel::getByEmail($account);
         }else{
            return api(0,"请输入手机号或者邮箱");
         }

         if($user){
             return api(0,"该账号已经被占用！");
         }
         //注册字段
         $paras = array(
             'account' =>$account,
             'pwd' =>md5($pwd),
             'phone' =>$phone,
             'email' =>$email,
            );
         $result = UsersModel::create($paras);
         if ($result) {
             return api(100,"注册成功",$result);
         } else {
             error("注册失败",$result);
         }
     }

    /**
     * 退出登录
     */
     function logout(){

         session_start();
         session_destroy();
         unset($_SESSION);
         if(isset($_SESSION)){
             error("退出登录失败！");
         }else{
             return api(100,"成功退出登录！");
         }
     }

    /**
     * 检查用户是否存在
     * @return [type] [description]
     */
     function checkUser($account=""){

        if($account==""){
            return api(102,"account参数缺失");
        }
 
        if(isPhone($account)){
        	
            $user=UsersModel::getByPhone($account);

            if($user){
            	 return api(100,"该账户已存在",$user);
            }else{
            	$user=UserModel::getByPhone($account);
            	if($user){
            	 return api(100,"该账户已存在",$user);
            	}else{
            	     return api(101,"该用户不存在");
            	}
            }

        }elseif(isEmail($account)){

            $user=UsersModel::getByEmail($account);

            if($user){
            	 return api(100,"该账户已存在",$user);
            }else{
            	$user=UsersModel::getByEmail($account);
            	if($user){
     
		            return api(100,"该账户已存在",$user);
            	}else{
		            return api(101,"该用户不存在");
            	}
            }

        }else{
            return api(102,"请输入手机号或者邮箱");    
        }
     }

    /**
     * 公众号、移动应用 微信授权登录
     */
      function wxLogin(){
          $param = Request::instance()->param();
          $rule = [
              'appid'  => 'require|max:100',
              'code'   => 'require',
          ];

          $this->validate($param,$rule);

          //第一步：用户同意授权，获取code   （由前端传过来）

          //第二步：通过code换取网页授权access_token
          $m = ThreeAccountModel::get(['appid'=>$param['appid']]);
          if($m==null)error("检查微信公众号、移动应用等第三方账号是否更换，请到用户系统后台添加APPID!");

          $wxAPI2 = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$param['appid']."&secret=$m->secret&code=".$param['code']."&grant_type=authorization_code";
          $authInfo = wxServerRequest($wxAPI2);
          if(isset($authInfo['errcode'])){
              return api(110,"第二步：通过code换取网页授权access_token时，微信服务器返回错误信息。code=".$param['code'],$authInfo);
          }

          if(!isset($authInfo['unionid']))error("该公众号没有绑定到微信开放平台！");

          $user = UsersModel::getByWxUnionid($authInfo['unionid']);
          if($user){
              if($this->doLogin($user)){
                  return api(100,"用户系统登录验证成功",$user);
              }else{
                  error('用户系统，检查是否开启了缓存！');
              }
          }


          //第三步：刷新access_token   （该access_token只使用一次，无需延时）

          //第四步：拉取用户信息(需scope为 snsapi_userinfo)
          $wxAPI4 = "https://api.weixin.qq.com/sns/userinfo?access_token=".$authInfo['access_token']."&openid=".$authInfo['openid']."&lang=zh_CN";
          $wxUserInfo = wxServerRequest($wxAPI4);

          if(isset($wxUserInfo['errcode'])){
              return api(110,'第四步：拉取用户信息时，微信服务器返回错误信息',$wxUserInfo);
          }

          $paras = array(
              'account'=>substr(time(), -5).rand(100,999),
              'wx_unionid'=>$wxUserInfo['unionid'],
              'wx_openid' =>$wxUserInfo['openid'],
              'nickname' =>filterEmoji($wxUserInfo['nickname']),
              'sex' =>$wxUserInfo['sex'],
              'head_img_url' =>$wxUserInfo['headimgurl'],
              'country'=>$wxUserInfo['country'],
              'province'=>$wxUserInfo['province'],
              'city'=>$wxUserInfo['city'],
              'access_token'=>base64_encode(md5(uniqid(rand())).time()),
          );

          $user = UsersModel::create($paras);
          if ($user) {
              if($this->doLogin($user)){
                  return api(100,"用户系统登录验证成功",$user);
              }
          }
          return api(105,"用户系统登录验证失败，数据库写入失败，请检查你的参数！");
      }

    /**
     * 微信小程序授权登录,一定要绑定微信开放平台，否则获取不到unionId
     */
    function wxAppLogin(){
        $rule = [
            'appid'  => 'require|max:100',
            'code'   => 'require',
            'encrypted_data'=> 'require',
            'iv'=>'require'
        ];
        $param =  Request::instance()->param();
        $this->validate($param,$rule);

        // 引入 extend/wechat-sdk/app/wxBizDataCrypt.php
        $load = Loader::import('wxBizDataCrypt', EXTEND_PATH.'/wechat-sdk/app', '.php');
        if(!$load)exception("微信APPSDK导入失败！");

        $m = ThreeAccountModel::get(['appid'=>$param['appid']]);
        if($m==null)error("检查微信公众号、小程序等第三方账号是否更换，请到用户系统后台修改APPID!");

        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$param['appid']."&secret=$m->secret&js_code=".$param['code']."&grant_type=authorization_code";
        $authInfo = wxServerRequest($url);

        if(!isset($authInfo['openid'])){
            return api(0,'微信服务器返回错误信息',$authInfo);
        }

        $pc = new \WXBizDataCrypt($param['appid'], $authInfo['session_key']);
        $errCode = $pc->decryptData($param['encrypted_data'], $param['iv'], $data );
        $wxUser = json_decode($data);
        if ($errCode!= 0)error("微信用户信息解密错误，错误码".$errCode);
        if(!isset($wxUser->unionId))("你的小程序无法获取微信unionId，请检查是否绑定到微信开放平台！");

        $user = UsersModel::getByWxUnionid($wxUser->unionId);
        if($user){
            $user->access_token = base64_encode(md5(uniqid(rand())).time());
            if($user->wx_openid != $wxUser->openId){
                $user->wx_openid = $wxUser->openId;
            }
            $user->save();
            if($this->doLogin($user)){
                return api(100,"用户系统登录验证成功",$user);
            }else{
                return api(0,"登录失败，请检查总用户系统缓存是否开启！");
            }

        }

        $userInfo = array(
            'account'=>substr(time(), -5).rand(100,999),
            'wx_unionid'=>$wxUser->unionId,
            'wx_openid'=>$wxUser->openId,
            'nickname' =>$wxUser->nickName,
            'sex' =>$wxUser->gender,
            'head_img_url' =>$wxUser->avatarUrl,
            'country'=>$wxUser->country,
            'province'=>$wxUser->province,
            'city'=>$wxUser->city,
            'access_token'=>base64_encode(md5(uniqid(rand())).time()),
        );

        $user = UsersModel::create($userInfo);
        if ($user) {
            if($this->doLogin($user)){
                $this->accountImport($user->yunsu_id);
                return api(100,"用户系统登录验证成功",$user);
            }else{
                return api(0,"登录失败，请检查总用户系统缓存是否开启！");
            }
        } else {
            return api(0,"用户系统系统异常！");
        }
    }


    /**
     * 更新用户信息
     * 为了安全 这里自己过滤了字段
     */
    public function update(){

        $model = new UsersModel();
        $field = ['nickname','sex','head_img_url','country','province','city','phone'];
        $params = $_POST;
        if($params==null){
            $params = json_decode(file_get_contents("php://input"),true);
        }
        if($params==null){
            error("你没有提交任何参数！");
        }
        $rt = $model->allowField($field)->save($params,['yunsu_id' =>$this->userId]);
        if($rt){
            success(UsersModel::get($this->userId));
        }else{
            error("无修改.请检查参数！".$model->getError());
        }
    }

    /**
     * 用户积分
     */
    public function point(){
        $num = paramFromPost("num",true);
        $action = paramFromPost("action",true);
        $m = UsersModel::get($this->userId);
        if($m){
            if($action=="add"){
                $m->point = $m->point+$num;
                $m->save();
                success($m);
            }elseif($action=="des"){
                if($m->point-$num>=0){
                    $m->point = $m->point-$num;
                    $m->save();
                    success($m);
                }else{
                    error("积分不足以扣取!");
                }
            }else{
                error("action参数错误！");
            }
        }else{
            error("不存在该用户哦！");
        }
    }

    /**
     * 发送短信验证码
     */
    public function sendsms(){
        $phone = paramFromPost('phone',true);
        $action = paramFromPost('action',true);
        if(!isPhone($phone))error("无效的手机号码！");
        $tiop = "";//0：短信登录;1：手机注册；2：修改密码；3：更换绑定手机
        if($action==0){
            $tiop = "短信登录";
        }elseif($action==1){
            $tiop = "手机注册";
        }elseif($action==2){
            $tiop = "修改密码";
        }elseif($action==3){
            $tiop = "绑定手机";
        }elseif($action==4){
            $tiop = "更换绑定手机";
        }else{
            error("action代码错误！");
        }

        $code = rand(1000,9999);
        Cache::set($phone,$code,60*60*60);
        $cl = new ChuanglanSmsApi();
        $rt = $cl->sendSMS($phone,"【云宿网络】尊敬的用户，你的".$tiop."验证码是".$code."请妥善保管！");
        success($cl->execResult($rt));
    }


    /**
     * 短信登录（不存在该电话，则注册并登录）
     */
    public function smsLogin(){

        if($this->user){
            api(101,'你已经登录过！',$this->user);
        }

        $phone =paramFromPost('phone',true);
        if(!isPhone($phone))error("电话号码无效！");
        $smsCode = paramFromPost('sms_code',true);
        if(Cache::get($phone)!=$smsCode)error("验证码不正确！");
        Cache::rm($phone);

        $user = UsersModel::getByPhone($phone);

        if($user){ // 存在则登录
            $user->access_token = base64_encode(md5(uniqid(rand())).time());
            $user->save();
            $this->doLogin($user);
            success($user);
        }else{ // 不存在则注册
            $user = UsersModel::create(['phone' =>$phone]);
            $user->access_token = base64_encode(md5(uniqid(rand())).time());
            $user->save();
            $this->doLogin($user);
            api(100,"登录成功",$user);
        }
    }

    /**
     * 绑定手机号
     */
    public function bindingPhone(){

        $phone = paramFromPost("phone",true);
        if(!isPhone($phone))error("手机号无效！");
        $smsCode = paramFromPost("sms_code",true);
        if(Cache::get($phone)!=$smsCode)error("验证码不正确！");
        Cache::rm($phone);
        $user = UsersModel::getByPhone($phone);
        if($user)api(1004,"该手机号已被其他账号绑定,请换一个手机号吧！");
        $user = UsersModel::get($this->userId);
        if ($user) {
            $user->phone = $phone;
            $user->save();
            api(100,"手机绑定成功",$user);
        } else {
            error("该用户不存在，请重新登录！");
        }
    }
}

