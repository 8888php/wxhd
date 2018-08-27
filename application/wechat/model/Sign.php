<?php
namespace app\wechat\model;

use think\Model;
use app\wechat\model\Wechatuser;
/**
 * 签到表
 */
class Sign extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    public static function qiandao () {
        $userinfo = Wechatuser::getSession();
        $sign = new Sign();
        $sign->user_id = $userinfo['id'];
        $sign->date = date('Y-m-d', time());
        $sign->month = date('Y-m', time());
        try{
            $res = $sign->save();
            return $res;
        } catch (\think\exception $e){
            if (stripos($e->getMessage(), 'Duplicate') !== false) {
                return -2;
            } else {
                return false;
            }
            
        }
    }




















    /*****************************************************
     CREATE TABLE `fa_sign` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL COMMENT '签到用户id',
        `date` date NOT NULL COMMENT '签到时间，如 2018-08-27',
        `createtime` int NOT NULL DEFAULT NULL,
        `updatetime` int NOT NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `date` (`date`,`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到表'
     ******************************************************/
}