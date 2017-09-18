<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;
use think\Request;

function isLogin(){
	if(!isset($_SESSION)){
	    session_start();
	}
    if(isset($_SESSION['yunsuId'])){
         return true;
    }else{
         return false;
    }
}

function getLoginYunsuId(){

    if(isLogin()){
         $yunsuId=$_SESSION['yunsuId'];
    }else{
         $yunsuId=null;
    }
    return $yunsuId;
}



function showLoginInfo(){

    if(isLogin()){
         $loginInfo="你已经登录,云宿ID：".$_SESSION['yunsuId'];
    }else{
         $loginInfo="你还没登录哦！";
    }
    return $loginInfo;
}

function read(){
	$result =  Db::name('users')  ->select();
	print_r($result);
}

/**
 * api get参数
 */
function paramFromGet($name,$required=false,$default=""){

    $request = Request::instance();
    if($required){
        $p=$request->get($name,null);
        if($p===null){
            error($name."参数缺失");
        }else{
            return $p;
        }
    }else{
        return $request->get($name,$default);
    }

}



//判断是否是电话
function isPhone($phone){
    if(preg_match('/^1[34578]{1}\d{9}$/',$phone)){
        return true;  
    }else{  
        return false;
    }  
}

/**
 *判断是否为email
 */
function isEmail($email){
    
    $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
    
    if ( preg_match( $pattern,$email) ){
       return true;
    }else{
       return false;
    }
}

//直播系统认证登录
function httpGet($url) { 
    //获取到cookie
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    //$cookie = array('Cookie:b=2;a=3');
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $cookie);
    //curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    //curl_setopt($curl, CURLOPT_POST, 0);//post方式提交 
    //curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息 
    $rs = curl_exec($ch); //执行
    curl_close($ch); 
    return $rs; 
}

/**
 * api 返回
 * @param $code
 * @param $msg
 * @param null $data
 */
function api($code,$msg,$data=null){
    header('Content-type: application/json');
    $rt['code']=$code;
    $rt['msg']=$msg;
    $rt['data']=$data;
    echo json_encode($rt);
    die();
}

/**
 * api 成功返回
 * @param null $data
 */
function success($data=null){
    api(100,'success',$data);
}

/**
 * api 错误返回
 * @param string $msg
 */
function error($msg='失败了哦！'){
    api(0,$msg);
}

/**
* 随机字符
* @param number $length 长度
* @param string $type 类型
* @param number $convert 转换大小写
* @return string
*/
 function random($length=6, $type='string', $convert=0){
     $config = array(
         'number'=>'1234567890',
         'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
         'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
         'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
     );

     if(!isset($config[$type])) $type = 'string';
     $string = $config[$type];

     $code = '';
     $strlen = strlen($string) -1;
     for($i = 0; $i < $length; $i++){
         $code .= $string{mt_rand(0, $strlen)};
     }
     if(!empty($convert)){
         $code = ($convert > 0)? strtoupper($code) : strtolower($code);
     }
     return $code;
 }


/**
 * 获取API POST参数
 * @param $name
 * @param bool $required
 * @param null $default
 * @return mixed
 */
function paramFromPost($name,$required=false,$default=null){
    $params = $_POST;
    if($params==null){
        $params = json_decode(file_get_contents("php://input"),true);
    }
    if($name==null){
        return $params;
    }

    if(isset($params[$name])){
        return $params[$name];
    }

    if($required){
        api(0,$name."参数缺失");
    }else{
        return $default;
    }

}

/**
 * 请求接口
 * @param $url
 * @param string $type  提交方式：get，post
 * @param null $paras    post提交参数
 * @param null $cookie
 */
function httpClient($url,$type=null,$paras=null,$cookie=null) {
    var_dump($paras);die();
    $curl = curl_init();//初始化curl模块
    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
    curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//禁用后cURL将终止从服务端进行验证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);//检查公用名是否存在，并且是否与提供的主机名匹配。
    if($type=="post"){
        curl_setopt($curl, CURLOPT_POST, 1);//post方式提交
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_decode($paras));//要提交的信息
    }
    if($cookie!=null){
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中
    }
    $rs=curl_exec($curl);//执行cURL
    curl_close($curl);//关闭cURL资源，并且释放系统资源
    return $rs;
}

/**
 * 微信服务器请求结果
 * @param $url 接口地址
 * @return mixed 数组
 */
function wxServerRequest($url,$type = null,$paras = null){
    $output=httpClient($url,$type,$paras);
    return json_decode($output,true);
}

/**
 * 微信昵称，表情替换
 * @param $str
 * @return mixed
 */
function filterEmoji($str)
{
    $str = preg_replace_callback(
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);

    $tmpStr = json_encode($str); //暴露出unicode
    $str = json_decode($tmpStr);
    return $str;
}
