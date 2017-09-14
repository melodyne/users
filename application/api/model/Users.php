<?php
namespace app\api\model;

use think\Model;

/**
*  用户模型
*
*/

class Users extends Model
{
    public function addUser($paras)
    {
        $this->yunsu_id   = $paras['yunsuId'];
        $this->account    = $paras['account'];
        $this->pwd    = $paras['password'];
        $this->phone = $paras['phone'];
        if(!$this->get($this->yunsu_id)){
            $this->save();
        }
        
    }

    public function getStatusTextAttr($value,$data)
    {

        return "sd";
    }

}