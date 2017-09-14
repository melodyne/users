<?php namespace app\admin\controller;

use think\Controller;
use app\admin\model\Users as UsersModel;//总用户系统模型

class Index extends Controller
{
    public function index()
    {
		
        return $this->fetch();
    }
	
	 public function users()
    {
	   $list = UsersModel::order('create_time desc')->paginate(20);
       $this->assign("userList",$list);
       return $this->fetch();
    }

     public function set()
    {

       return $this->fetch();
    }

}
