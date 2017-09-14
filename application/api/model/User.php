<?php
namespace app\api\model;

use think\Model;

/**
*  直播用户模型
*
*/

class User extends Model
{
    // 设置单独的数据库连接
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => '127.0.0.1',
        // 数据库名
        'database'    => 'dbapp_yunsu',
        // 数据库用户名
        'username'    => 'root',
        // 数据库密码
        'password'    => 'hnyy2015root',
        // 数据库连接端口
        'hostport'    => '3306',
        // 数据库连接参数
        'params'      => [100],
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => '',
        // 数据库调试模式
        'debug'       => true,
    ];
	
    public function read()
    {
       
        $this->pwd    = '2342342342sdfs';
        $this->phone = 131345852854;
        if ($this->save()) {
            return '用户[ ' . $this->phone . ' ]新增成功';
        } else {
            return $user->getError();
        }
    }
}