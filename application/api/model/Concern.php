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
    public function MeConcern(){

        return $this->hasOne('Users','yunsu_id','concern_user_id')
            ->field('yunsu_id,nickname,head_img_url');
    }

    /**
     * 关注我的
     * @return \think\model\relation\HasOne
     */
    public function concernMe(){

        return $this->hasOne('Users','yunsu_id','user_id')
            ->field('yunsu_id,nickname,head_img_url');
    }

}