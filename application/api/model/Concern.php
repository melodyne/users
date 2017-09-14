<?php
namespace app\api\model;

use think\Model;

/**
*  用户模型
*
*/

class Concern extends Model
{
    /**
     * 我关注的
     * @return \think\model\relation\HasOne
     */
    public function myConcern(){

        return $this->hasOne('Users','yunsu_id','concern_user_id');
    }

    /**
     * 关注我的
     * @return \think\model\relation\HasOne
     */
    public function concernMe(){

        return $this->hasOne('Users','yunsu_id','user_id');
    }

}