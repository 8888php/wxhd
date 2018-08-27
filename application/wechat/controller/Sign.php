<?php
namespace app\wechat\controller;

use app\wechat\model\Wechatuser;
class Sign extends NeedLogin
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';
    
    public function _initialize()
    {
        parent::_initialize();
    }
    
    /**
     * 签到页面
     * @return type
     */
    public function index()
    {
        return $this->view->fetch();
    }
    
    
    public function logout() {
        Wechatuser::dropSession(Wechatuser::$sessionName);
        $url = $this->request->get('url', 'index/index');
        $this->success('退出成功', $url);
    }
}
