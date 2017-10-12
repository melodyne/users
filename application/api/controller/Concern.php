<?php
namespace app\api\controller;

use app\api\model\Users as UsersModel;//总用户系统模型
use app\api\model\Concern as ConcernModel;
use app\common\controller\ApiBaseController;
use think\Request;

/**
*  用户控制器
*/
class Concern extends ApiBaseController{

    /**
     * 关注好友列表
     */
    public function index(){
        // 0我关注的 1关注我的 2相互关注的
        $rule = [
            'type'=>'require|in:0,1,2'
        ];
        $msg = [
            'type.require'=>'关注类型不能为空',
            'type.in'=>'关注类型错误：0我关注的 1关注我的 2相互关注的'
        ];
        $param = Request::instance()->get();
        $this->validate($param,$rule,$msg);
        if($param['type']==0){
            $list = ConcernModel::where(['user_id'=>$this->userId])->paginate();
            foreach ($list as $m){
                $m->me_concern;
                $m['user']= $m['me_concern'];
                unset($m['me_concern']);
            }
        }
        if($param['type']==1){
            $list = ConcernModel::where(['concern_user_id'=>$this->userId])->paginate();
            foreach ($list as $m){
                $m->concern_me;
                $m['user']= $m['concern_me'];
                unset($m['concern_me']);
            }
        }

        if($param['type'] ==2){
            error("相互关注的功能还没有实现");
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

