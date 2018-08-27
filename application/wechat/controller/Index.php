<?php
namespace app\wechat\controller;

use app\wechat\model\Wechatuser;
use think;
use think\Session;
use think\Cookie;
class Index extends NotLogin
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';
    
    public function _initialize()
    {
        parent::_initialize();
    }
    private function islogin() {
        //判断是否登录
        if (Wechatuser::isLogin()) {
            $url = $this->request->get('url', 'sign/index');
            $this->success('', $url, '', 0);
        }
    }
    
    /**
     * 登录页面
     * @return type
     */
    public function index()
    {
        $this->islogin();
        return $this->view->fetch();
    }
    
    /**
     * 处理登录
     * @return json
     */
    public function login() {
        $ret = array();
        if ($this->request->isAjax()) {
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $userinfo = Wechatuser::getPasswordByUsername($username);
            if ($userinfo) {
                $flag = Wechatuser::checkPassword($userinfo['password'], $password, $userinfo['salt']);
                if ($flag) {
                    //登录成功 记录Session
                    Wechatuser::setSession(Wechatuser::$sessionName, $userinfo->toArray());
                    //Cookie::set($name);
                    $ret['code'] = 0;
                    $ret['msg'] = 'login ok';
                } else {
                    //密码error
                    $ret['code'] = 1;
                    $ret['msg'] = '帐号/密码有误';
                }
            } else {
                //没有数据
                $ret['code'] = 1;
                $ret['msg'] = '帐号/密码有误';
            }
            return $ret;
            
        }
        $url = $this->request->get('url', 'index/index');
        $this->error('', $url, '', 0);
    }

}
