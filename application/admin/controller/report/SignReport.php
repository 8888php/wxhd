<?php

namespace app\admin\controller\report;

use app\common\controller\Backend;
use app\common\model\Category;
use app\wechat\model\Sign;
use app\wechat\model\Wechatuser;
use fast\Tree;
use think\Db;

/**
 * 签到统计
 *
 * @icon fa fa-sign
 */
class SignReport extends Backend
{

    protected $relationSearch = true;
    protected $noNeedRight = ['selectpage'];

    public function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags']);
        /*$this->signList = Db::name('wechatuser')
            ->alias('a')
            ->join('sign b', 'a.id = b.user_id', 'LEFT')
            ->order('b.updatetime','desc')
            ->paginate(3,false,[
                'type'     => 'bootstrap',
                'var_page' => 'page',
            ]);*/
    }

    public function index(){

        if ($this->request->isAjax())
        {

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('wechatuser')
                ->alias('a')
                ->join('sign b', 'a.id = b.user_id', 'LEFT')
                ->where($where)
                ->order('b.updatetime','desc')
                ->count();

            $list = Db::name('wechatuser')
                ->alias('a')
                ->join('sign b', 'a.id = b.user_id', 'LEFT')
                ->where($where)
                //->field('a.id,a.username,a.nickname,b.user_id,b.date,b.month,b.updatetime')
                ->order('b.updatetime','desc')
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);


            /*$search = $this->request->request("search");
            $type = $this->request->request("type");

            //构造父类select列表选项数据
            $list = [];
            $this->signList = Db::name('wechatuser')
                ->alias('a')
                ->join('sign b', 'a.id = b.user_id', 'LEFT')
                ->order('b.updatetime','desc')->select();
//                ->paginate(3,false,[
//                    'type'     => 'bootstrap',
//                    'var_page' => 'page',
//                ]);
            $list = $this->signList;*/
            /*foreach ($this->signList as $k => $v)
            {
                if ($search) {
                    if ($v['type'] == $type && stripos($v['name'], $search) !== false || stripos($v['nickname'], $search) !== false)
                    {
                        if($type == "all" || $type == null) {
                            $list = $this->signList;
                        } else {
                            $list[] = $v;
                        }
                    }
                } else {
                    if($type == "all" || $type == null) {
                        $list = $this->signList;
                    } else if ($v['type'] == $type){
                        $list[] = $v;
                    }

                }

            }*/

            /*$total = count($list);
            $result = array("total" => $total, "rows" => $list);
            return json($result);*/
        }
        return $this->view->fetch();


        /*// 查询状态为1的用户数据 并且每页显示10条数据
        $signList = Db::name('wechatuser')
            ->alias('a')
            ->join('sign b', 'a.id = b.user_id', 'LEFT')
            ->order('b.updatetime','desc')
            ->paginate(3,false,[
                'type'     => 'bootstrap',
                'var_page' => 'page',
            ]);
        // 获取分页显示
        $page = $signList->render();
        // 模板变量赋值
        $this->assign('page', $page);
        $this->view->assign('signList', $signList);
        return $this->view->fetch();*/
    }

    public function selectpage()
    {
        return parent::selectpage();
    }

}
