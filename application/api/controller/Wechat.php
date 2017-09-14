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
*  微信
*
*/
class Wechat extends ApiBaseController{

    protected $beforeActionList = [
        'loginAuth'  =>  ['only'=>''],
    ];

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
     * 微信模版消息
     */
    function templateMsg(){
        $rule = [
            'appid'=>'require',
            'touser'=> 'require',
            'template_id'=> 'require',
            'form_id'=> 'require',
            'data'=>'require'
        ];
        $param =  Request::instance()->param();
        $this->validate($param,$rule);

        $m = ThreeAccountModel::get(['appid'=>$param['appid']]);
        if($m==null)error("检查微信公众号、小程序等第三方账号是否更换，请到用户系统后台修改APPID!");

        /* 第一步 获取token */
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$param['appid']."&secret=$m->secret";
        $rt = wxServerRequest($url);

        if(!isset($rt['access_token'])){
            return api(0,'微信服务器返回错误信息',$rt);
        }
        /* 第二步 发送模版消息 */
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$rt['access_token'];
        $rt = wxServerRequest($url,'post',$param);
        success($rt);
    }

}

