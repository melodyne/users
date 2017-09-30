<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/17
 * Time: 10:01
 */

namespace app\common\controller;

use think\Controller;
use think\Cookie;

class AdminController extends Controller{

    public $modelClass;//模型类，在子控制器中必须申明并赋值
    public $condition; //查询条件

    /**
     * 初始方法
     */
    public function _initialize(){
        if($this->modelClass==null){
            $this->error("控制器没有指定modelClass！");
        }
        if(!$this->isLogin()){
            $this->redirect('admin/user/login');
        }
    }

    /**
     * 查- 列表
     * @return string
     */
    public function index()
    {
        $modelClass = $this->modelClass;
        $list = $modelClass::paginate(20);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 查- 详情
     * @return string
     */
    public function read($id){
        $model = $this->findModel($id);
        $this->assign('model',$model);
        return $this->fetch();
    }

    /**
     * 增- 表单
     * @return string
     */
    public function create(){
       return $this->fetch();
    }

    /**
     * 增 - 确认保存
     * @return string
     */
    public function save()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass($_POST);
        // 过滤post数组中的非数据表字段数据
        $model->allowField(true)->save();
        if($model){
            if(isset($model->create_time)){
                $model->create_time = time();
                $model->save();
            }
            $this->success("新增成功！",'index');
        }else{
            $this->error("新增失败！");
        }


    }

    /**
     * 改 - 表单
     * @param $id
     * @return string
     */
    public function edit($id){

        $model = $this->findModel($id);
        $this->assign('model',$model);
        return $this->fetch();
    }

    /**
     * 改 - 确认修改
     * @param $id
     * @return string
     */
    public function update($id){
        $model = $this->findModel($id);
        $rt = $model->allowField(true)->save(request()->param());
        if($rt){
            $this->success("更新成功！","index");
        }else{
            $this->error("更新失败!");
        }
    }

    /**
     * 删
     * @param $id
     * @return string
     */
    public function delete($id){

        $model = $this->findModel($id);
        $rt = $model->delete();
        if($rt){
            $this->success("删除成功！","index");
        }else{
            $this->error("删除失败！");
        }

    }

    /**
     * 获取model
     * @param $id
     * @return mixed
     */
    protected  function findModel($id){
        $modelClass = $this->modelClass;
        if($this->condition){
            $model = $modelClass::where($this->condition)->get($id);
        }else{
            $model = $modelClass::get($id);
        }

        if(!$model){
            $this->error("你没权限操作或该记录已经被删除！");
        }
        return $model;
    }

    /**
     * 获取登录管理员
     * @return mixed
     */
    protected function getLoginAdmin(){
        return Cookie::get('login_admin');
    }

    /**
     * 是否登录
     */
    protected function isLogin(){
        if(Cookie::get('login_admin')){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 管理员登录
     * @param $admin
     */
    protected function doLogin($admin){
        Cookie::set('login_admin',$admin);
    }

    /**
     * 退出
     */
    protected function doLogout(){
       Cookie::clear('login_admin');
    }

}