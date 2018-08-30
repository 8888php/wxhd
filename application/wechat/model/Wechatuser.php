<?php
namespace app\wechat\model;

use think\Model;
use think\Session;
use think\Cookie;
class Wechatuser extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'timestamp';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    public static $sessionName = 'wechatInfo';
    public static $sessionLiftTime = 365 * 24 * 60 * 60;//一年
    public static $salt = 'wxhd';//默认加密 用户密码用的
    public static $defaultPassword = '123456';//初始密码
    public static $qianzhui = 'wetchat';
    public static $jiamiKey = '1234wxhd5678';//加密用的key cookie用的

    
    public static function getPasswordByUsername($username) {
        $user = Wechatuser::get(['username' => $username]);
        return $user;
    }
    
    public static function checkPassword($u_password, $password, $salt, $encrypt = 'md5') {
        return $u_password === $encrypt($password . $salt);
    }
    
    public static function isLogin($name = null) {
//        if (!$name) {
//            $name = self::$sessionName;
//        }
//        return self::getSession($name);
        $cookie = self::cookieGet(self::$sessionName);
        if ($cookie && !empty($cookie['id'])) {
            return true;
        }
        return false;
    }

    public static function setSession($name = null , $data = null, $lifetime = null) {
        if (!$name) {
            $name = self::$sessionName;
        }
        if ($lifetime) {
            Session::init(array('prefix' => self::$qianzhui, 'expire' => $lifetime));
        }
        Session::set($name, $data, self::$qianzhui);
        if ($lifetime) {
            Cookie::set(session_name(), session_id(), $lifetime);
        }
        
    }
    
    public static function dropSession($name = null){
        if (!$name) {
            $name = self::$sessionName;
        }
        Session::delete($name, self::$qianzhui);
    }

    public static function getSession($name = null){
        if (!$name) {
            $name = self::$sessionName;
        }
        return Session::get($name, self::$qianzhui);
    }
    /***********************************************************************/
    //下面是用与长期登录用的
    public static function passport_encrypt($txt, $key) {
        srand((double)microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for($i = 0;$i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        return base64_encode(self::passport_key($tmp, $key));
    }

    public static function passport_decrypt($txt, $key) {
        //基数是错误的
        if (strlen($txt) % 2 != 0) {
            return null;
        }
        $txt = self::passport_key(base64_decode($txt), $key);
         $tmp = '';
         for($i = 0;$i < strlen($txt); $i++) {
             $md5 = $txt[$i];
             $tmp .= $txt[++$i] ^ $md5;
         }
        return $tmp;
    }

    public static function passport_key($txt, $encrypt_key) {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }
    
    public static function cookieSet($name = null, $value = null, $lifetime = null) {
        if (!$name) {
            $name = self::$sessionName;
        }
        if (!$lifetime) {
            $lifetime = self::$sessionLiftTime;
        }
        Cookie::set($name, self::passport_encrypt($value, self::$jiamiKey), $lifetime);
    }

    public static function cookieGet($name = null) {
        if (!$name) {
            $name = self::$sessionName;
        }
        $cookie = self::passport_decrypt(Cookie::get($name), self::$jiamiKey);
        if ($cookie) {
            $cookie = unserialize($cookie);
        }
        return $cookie;
    }
    
    public static function cookieDrop($name = null) {
        if (!$name) {
            $name = self::$sessionName;
        }
        Cookie::delete($name);
    }
    /*****************************************************************/
    
    public static function insertUser($data, $encrypt = 'md5') {
        $user = new Wechatuser();
        $user->username = $data['username'];
        $user->nickname = $data['nickname'];
        $user->password = $encrypt(self::$defaultPassword . self::$salt);
        $ret = $user->save();
        return $ret;
    }
    /************************************************************
     
     CREATE TABLE `fa_wechatuser` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` char(11) NOT NULL COMMENT '用户手机号',
        `nickname` varchar(64) NOT NULL COMMENT '名称',
        `password` char(32) NOT NULL COMMENT '用户密码',
        `salt` char(8) DEFAULT 'wxhd' COMMENT 'salt',
        `createtime` int(11) DEFAULT NULL,
        `updatetime` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `username` (`username`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动用户表'


     **************************************************************/
}