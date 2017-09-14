<?php
function post($url, $post) { 
    $curl = curl_init();//初始化curl模块 
    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址 
    curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息 
    curl_setopt($curl, CURLOPT_POST, 1);//post方式提交 
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息 
    $rt =curl_exec($curl);//执行cURL 
    curl_close($curl);//关闭cURL资源，并且释放系统资源
    return $rt; 
} 
$post=array(
	'account' =>'13006022705', 
	'pwd'=>'123456'
	);
echo post("http://127.0.0.1/api/user/login",$post);
?>
