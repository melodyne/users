<?php 
namespace app\home\controller;

use think\Controller;
use app\home\model\Users as UsersModel;//总用户系统模型

class User extends Controller
{

	 public function login()
    {
        if(!isLogin()){
             if(isset($_POST['username'])&&!empty($_POST['username'])){
                $username=$_POST['username']; 
                $pwd=$_POST['password'];  

                if(isPhone($username)){
                    $user=UsersModel::getByPhone($username);
                }elseif(isEmail($username)){
                    $user=UsersModel::getByEmail($username);
                }else{
                    $user=UsersModel::getByAccount($username);
                }

                if($user){
                    if(md5($pwd)==$user['pwd']){
                         $_SESSION['yunsuId']=$user['yunsu_id'];
                    }else{
                         echo "密码错误！";exit();
                    }
                }else{
                    echo "不存在此用户";exit();
                }    
            }
        }

       
        $this->assign("loginInfo",showLoginInfo());	
        return $this->fetch();
    }
   
	 public function register()
    {
		$this->assign("loginInfo",showLoginInfo());	
        return $this->fetch();
    }
}

