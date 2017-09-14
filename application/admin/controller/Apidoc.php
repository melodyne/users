<?php namespace app\admin\controller;

use think\Controller;
use app\admin\model\Subsystem as SubsystemModel;//总用户系统模型
use app\common\model\ThreeAccount;
use think\Request;


class Apidoc extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function apply(){
        return $this->fetch();
    }

    public function confirApply(){
        $name = paramFromPost('name');
        $intro = paramFromPost('intro');

        if($name==null||$intro==null)$this->error("信息没有填写完整！");

        $m = new SubsystemModel();
        $m->app_id = (int)random(10,'number');
        $m->name = $name;
        $m->desc = $intro;
        $m->create_time = time();
        $rt = $m->save();
        if($rt){
            $this->success("申请已提交，等待审核！","index");
        }else{
            $this->success("申请已提交失败！".$m->getError());
        }
    }

    public function audit(){
        $list = SubsystemModel::where('audit_status','NEQ','1')->order('create_time DESC')->paginate(20);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function confirAudit(){
        $appId = paramFromGet('app_id');
        $auditStatus = paramFromGet('a');
        if($appId==null||($auditStatus!=1&&$auditStatus!=2))$this->error("你思想有点漂浮哦,系统完全跟不上！");
        $m = SubsystemModel::get($appId);
        if($m){
            $m->secret = random(30,'string');
            $m->audit_status = $auditStatus;
            $rt = $m->save();
            if($rt){
                if($auditStatus==1){
                    $this->success("审核已通过！","applist");
                }else{
                    $this->success("审核已驳回！");
                }
            }else{
                $this->error("审核失败".$m->getError());
            }
        }else{
            $this->error("没有该申请！");
        }
    }

    public function applist(){
        $list = SubsystemModel::where('audit_status','EQ','1')->order('create_time DESC')->paginate(20);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function open(){
        $appId = paramFromGet('app_id');
        $auditStatus = paramFromGet('a');
        if($appId==null||($auditStatus!=0&&$auditStatus!=1))$this->error("你思想有点漂浮哦,系统完全跟不上！");
        $m = SubsystemModel::get($appId);
        if($m){
            $m->status = $auditStatus;
            $rt = $m->save();
            if($rt){
                $this->success("操作成功！");
            }else{
                $this->error("开启成功".$m->getError());
            }
        }else{
            $this->error("没有该appid的应用！");
        }
    }

    /**
     * 第三方账号
     */
    public function threeAccount(){
        $list = ThreeAccount::order('create_time desc')->paginate();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 创建第三方账号
     */
    public function createThreaAccount(){
        return $this->fetch();
    }

    /**
     * 创建第三方账号
     */
    public function saveThreaAccount(){
        $rules = [
            'name'  => 'require|min:2|max:10',
            'appid' => 'require',
            'secret'=>'require',
            'describe'=>'require|min:5|max:30'
        ];
        $msg = [
            'name.require'=>'第三方账号名称不能为空！',
            'name.min'=>'第三方账号名称最少为两个字！',
            'name.max'=>'第三方账号不能为空！',
            'appid.require'=>'第三方账号不能为空！',
            'describe.require'=>'第三方账号不能为空！',
        ];
        $param = Request::instance()->param();
        $this->validate($param,$rules);
    }
}
