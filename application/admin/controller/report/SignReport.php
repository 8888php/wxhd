<?php

namespace app\admin\controller\report;

use app\common\controller\Backend;
use app\common\model\Category;
use app\wechat\model\Sign;
use think\Db;

/**
 * 签到统计
 *
 * @icon fa fa-sign
 */
class SignReport extends Backend
{

    protected $relationSearch = true;

    public function index(){
        // 查询状态为1的用户数据 并且每页显示10条数据
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
        return $this->view->fetch();
    }
    /**
     * User模型对象
     */
    /*protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }*/

    /**
     * 查看
     */
    /*public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with('group')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('group')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $k => $v)
            {
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }*/

    /**
     * 编辑
     */
    /*public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
        return parent::edit($ids);
    }*/

}
