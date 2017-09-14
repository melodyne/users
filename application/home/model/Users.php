<?php
namespace app\home\model;

use think\Model;

/**
*  用户模型
*
*/

class Users extends Model
{
    public function addUser($yunsuId,$account,$pwd,$phone)
    {
        $this->yunsu_id   = $yunsuId;
        $this->account    = $account;
        $this->pwd    = $pwd;
        $this->phone = $phone;
        if(!$this->get($yunsuId)){
            $this->save();
        }
        
    }
	
}