<?php
namespace app\wechat\controller;

use \app\common\controller\Frontend;
use app\wechat\model\Wechatuser;
/**
 * 需要登录
 */
class NeedLogin extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';
    
    public function _initialize()
    {
        $this->islogin();
        parent::_initialize();
    }
    
    private function islogin() {
        //判断是否登录
        if (!Wechatuser::isLogin()) {
            $url = $this->request->get('url', 'index/index');
            $this->success('请登录', $url);
        }
    }
    
}
