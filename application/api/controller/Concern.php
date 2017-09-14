<?php
namespace app\api\controller;

use common\controller\ApiBaseController;
use app\api\model\Users as UsersModel;//总用户系统模型
use app\api\model\Concern as ConcernModel;

/**
*  用户控制器
*/
class Concern extends ApiBaseController{

    /**
     * 关注好友列表
     */
    public function index(){
        $type = paramFromGet('type');
        if($type==1){
            $list = ConcernModel::where(['concern_user_id'=>$this->userId])->paginate(20);
        }else{
            $list = ConcernModel::where(['user_id'=>$this->userId])->paginate(20);
        }

        if($type==2){
            $list2 = $list->toArray();
            $list2 = $list2['data'];
        }
        $n = 0;
        foreach ($list as $k=>$m){
            if($type==1){
                $user = UsersModel::get(['yunsu_id'=>$m->user_id]);
            }else{
                $user = UsersModel::get(['yunsu_id'=>$m->concern_user_id]);
            }
            $user->toArray();
            unset($user['access_token']);
            unset($user['pwz']);
            unset($user['yun_coin']);
            unset($user['create_time']);
            $m['user'] = $user;
            $list2[$k]['user'] = $user;

            if($type==2){
                $cm = ConcernModel::get(['user_id'=>$m['concern_user_id'],'concern_user_id'=>$this->userId]);
                if($cm==null){
                    $n++;
                    array_splice($list2,$k,1);
                }
            }
        }
        $list = $list->toArray();
        if($type==2){
            $list['total'] = $list['total']-$n;
            $list['data']= $list2;
        }
        success($list);
    }
    /**
     * 关注好友/取消关注
     */
    public function read($id){
        if($id==$this->userId){
            api(131,"不能关注自己哦！");
        }
        $user = UsersModel::get($id);
        if($user){
            $m = ConcernModel::get(['user_id'=>$this->userId,'concern_user_id'=>$id]);
            if($m){
                $rt = $m->delete();
                if($rt){
                    api(100,"取消关注成功！");
                }
            }
            $m = new ConcernModel();
            $m->user_id = $this->userId;
            $m->concern_user_id = $id;
            $m->concern_time =time();
            $rt = $m->save();
            if($rt){
                api(100,"关注成功！",$m);
            }else{
                error("关注失败！");
            }
        }else{
            error("不存在该用户！");
        }
    }

}

