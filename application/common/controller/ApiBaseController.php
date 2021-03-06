<?php
namespace app\common\controller;

use think\Cache;
use think\Controller;
use think\Loader;
use think\Request;


class ApiBaseController extends Controller
{
    protected $user;
    protected $userId;

    //默认所有方法登录权限
    protected $beforeActionList = [
        'loginAuth',
    ];

    /**
     * 初始方法
     */
    public function _initialize(){
        parent::_initialize(); // TODO: Change the autogenerated stub
        //api跨域
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Headers:Origin,X-Requested-With,Content-Type,Accept");
        header("Access-Control-Allow-Methods:DELETE,PUT");
    }

    /**
     * 登录
     * @param $user
     * @return bool
     */
    protected function doLogin($user){
        $accessToken = $user['access_token'];
        $this->user = $user;
        $this->userId = $user['yunsu_id'];
        $rt = Cache::set($accessToken,$user, 20 * 24 * 60 * 60);//token有效期两个小时 测试期间20天
        if(!$rt)die($rt);
        return true;
    }

    /**
     * 需要登录权限
     */
    protected function loginAuth(){

        $accessToken = Request::instance()->header('access-token');
        if($accessToken==null){
            $accessToken = Request::instance()->get('access-token');
        }
        if($accessToken==null){
            api(101,"该接口需要登录权限！");
        }
        if(strlen($accessToken)<24){
            api(102,"access-token无效！");
        }

        $loginUser = Cache::get($accessToken);
        if($loginUser==null){
            api(103,"access-token已过期！");
        }

        $this->user = $loginUser;
        $this->userId = $loginUser['yunsu_id'];
    }

    /**
     * 导入账号到腾讯云IM
     */
    protected function accountImport($userId){

        $config = config('thirdaccount.qcloud');
        $imConfig = $config['im_sdk'];
        $sdkAppId = $imConfig['sdk_app_id'];
        $identifier = $imConfig['identifier'];
        $privateKeyPath = $imConfig['private_key_path'];
        $signaturePath = $imConfig['signature_path'];

        // 引入 extend/wechat-sdk/wechat.class.php
        if(!Loader::import('TimRestApi', EXTEND_PATH.'qcloud/imsdk', '.php'))error("imsdk导入失败！");
        // 初始化API
        $restApi = createRestAPI();
        $restApi->init($sdkAppId, $identifier);
        // 生成签名，有效期一天 对于FastCGI，可以一直复用同一个签名，但是必须在签名过期之前重新生成签名
        $ret = $restApi->generate_user_sig("andmin", '86400', $privateKeyPath, $signaturePath);
        if ($ret == null) error("签名生成失败");
        if($restApi->account_import($userId,$this->user['nickname'],$this->user['head_img_url'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 网络请求
     * @param $url
     * @param $paras
     * @return mixed
     */
    protected function httpPost($url,$paras) {

        $curl = curl_init();//初始化curl模块
        curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
        curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//禁用后cURL将终止从服务端进行验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);//检查公用名是否存在，并且是否与提供的主机名匹配。
        curl_setopt($curl, CURLOPT_POST, 1);//post方式提交
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $paras);//要提交的信息
        $rs=curl_exec($curl);//执行cURL
        curl_close($curl);//关闭cURL资源，并且释放系统资源
        return $rs;
    }

    /**
     * 重写 表单验证
     */
    protected function validate($data, $validate, $message = [], $batch = false, $callback = null)
    {
        $result = parent::validate($data, $validate, $message, $batch, $callback); // TODO: Change the autogenerated stub
        if (true !== $result) {
            error($result);
        }
    }
}
