<?php
namespace app\wechat\controller;

use \app\common\controller\Frontend;
use app\wechat\model\Wechatuser;
/**
 * 不用登录
 */
class NotLogin extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';
    
    public function _initialize()
    {
        parent::_initialize();
    }
}
