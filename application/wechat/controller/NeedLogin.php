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
            if ($this->request->isAjax()) {
                $ret['code'] = -1;
                $ret['msg']  = '请登录';
                exit(json_encode($ret));
            }
            $this->redirect('/wechat/index/index');
        }
    }
    
}
