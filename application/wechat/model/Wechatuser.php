<?php
namespace app\wechat\model;

use think\Model;
use think\Session;
class Wechatuser extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'timestamp';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    public static $sessionName = 'wechatInfo';
    public static $sessionLiftTime = 365 *24 * 60 * 60;//一年

    
    public static function getPasswordByUsername($username) {
        $user = Wechatuser::get(['username' => $username]);
        return $user;
    }
    
    public static function checkPassword($u_password, $password, $salt, $encrypt = 'md5') {
        return $u_password === $encrypt($password . $salt);
    }
    
    public static function isLogin($name = null) {
        if (!$name) {
            $name = self::$sessionName;
        }
        return self::getSession($name);
    }

    public static function setSession($name = null , $data = null) {
        if (!$name) {
            $name = self::$sessionName;
        }
        Session::set($name, $data);
    }
    
    public static function dropSession($name = null){
        if (!$name) {
            $name = self::$sessionName;
        }
        Session::delete($name);
    }

    public static function getSession($name = null){
        if (!$name) {
            $name = self::$sessionName;
        }
        return Session::get($name);
    }
    
    /************************************************************
     
     CREATE TABLE `fa_wechatuser` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` char(11) NOT NULL COMMENT '用户手机号',
        `password` char(32) NOT NULL COMMENT '用户密码',
        `salt` char(8) DEFAULT 'wxhd' COMMENT 'salt',
        `createtime` int NOT NULL DEFAULT NOT,
        `updatetime` int NOT NULL DEFAULT NOT,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='活动用户表'


     **************************************************************/
}