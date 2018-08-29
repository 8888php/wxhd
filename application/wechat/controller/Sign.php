<?php
namespace app\wechat\controller;

use app\wechat\model\Wechatuser;
use think\Config;
use think\Db;
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
        $userinfo = Wechatuser::getSession();
        $user_id  = $userinfo['id'];
        $sigin = \app\wechat\model\Sign::all(['user_id' => $user_id]);
        $count = count($sigin);//签到总次数
        $month = date('Y-m');
        $month_data = \app\wechat\model\Sign::all(['user_id' => $user_id, 'month' => $month]);
        $today = date('Y-m-d');
        $today_data = \app\wechat\model\Sign::all(['user_id' => $user_id, 'date' => $today]);
        
        //取出前5名签到者
        $prefix = Db::connect()->getConfig('prefix');
        $sql_limit_5 = "select count(user_id) count, username, nickname from {$prefix}sign s left join {$prefix}wechatuser u on u.id=s.user_id group by user_id order by count desc limit 5;";
        $paiming5 = Db()->query($sql_limit_5);
        $this->view->assign('paiming5', $paiming5);
        
        $this->view->assign('userinfo', $userinfo);
        $this->view->assign('today', count($today_data));
        $this->view->assign('count', $count);
        $this->view->assign('month_data', $month_data);
        return $this->view->fetch();
    }
    
    public function ajaxsign() {
        if ($this->request->isAjax()) {
            $year = $this->request->post('year');
            $month = $this->request->post('month');
            
            $time = strtotime($year . '-' . $month);
            $month_new = date('Y-m', $time);
            $userinfo = Wechatuser::getSession();
            $user_id  = $userinfo['id'];
            $month_data = \app\wechat\model\Sign::all(['user_id' => $user_id, 'month' => $month_new]);
            return array(
                'code' => 0,
                'data' => $month_data
            );
        }
    }

        /**
     * 签到
     * @return type
     */
    public function signqd() {
        if ($this->request->isAjax()) {
            $count = Config::get('qiandaocishu');//每天的总签到次数
            $today_all = \app\wechat\model\Sign::all(['date', date('Y-m-d', time())]);
            if (count($today_all) >= $count) {
                $ret['code'] = -4;
                $ret['msg']  = '今天签到人数已满';
                return $ret;
            }
            //ajax
            $res = \app\wechat\model\Sign::qiandao();
            if (!$res) {
                //insert error
                $ret['code'] = -3;
                $ret['msg']  = '签到失败';
                
            } elseif ($res == -2) {
                //Duplicate
                $ret['code'] = -2;
                $ret['msg']  = '今天已经签到过了';
                
            } else {
                //ok
                $ret['code'] = 0;
                $ret['msg']  = '签到成功';
            }
            return $ret;
        } else {
            $this->redirect('/wechat/sign/index');
            
        }
        
    }

    public function logout() {
        Wechatuser::dropSession(Wechatuser::$sessionName);
        \think\Cookie::delete(session_name());
        $url = $this->request->get('url', 'index/index');
        $this->success('退出成功', $url);
    }
}
