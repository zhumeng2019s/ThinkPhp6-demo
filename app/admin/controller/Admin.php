<?php


namespace app\admin\controller;


use app\admin\model\User;
use liliuwei\think\Auth;
use think\facade\Db;
use think\facade\View;
use think\Request;

class Admin extends Common
{
    /**
     * 会员列表
     */
    public function adminList()
    {
        $auth = new Auth();
        $list = Db::table('think_user')
            ->where('id', '>', '1')
            ->order('id')
            ->select();
        $GroupTitle = [];
        foreach ($list as $k => $v) {
            $groupTitle = $auth->getGroups($v['id']);
            $data = ['id' => $v['id'], 'groupTitle' => $groupTitle[0]['title'],'rules'=>$groupTitle[0]['rules']];
            array_push($GroupTitle, $data);
            /**
             * 这里的foreach不知道为啥不好使。
             */
//            $v['groupTitle']  = $_groupTitle[0]['title'];
////            $v['groupTitle'] = $groupTitle;
        }
        View::assign('groupTitle', $GroupTitle);
        View::assign('list', $list);
        return View::fetch('admin-list');
    }
    /**
     * 添加
     */
    public function Add(Request $request)
    {
        if (!(request()->isPost())) {
            $list = Db::table('think_auth_group')->select();
            View::assign('List', $list);
            return View::fetch('admin-add');
        }
        $data = $request->param();
        $model = new User();
        $reslut = $model->adminAdd($data);
        return $reslut;
    }

    /**
     * 删除
     */
    public function Del($id)
    {
        $res = Db::table('think_user')->where('id', $id)->delete();
        if ($res) {
            $resGroup = Db::table('think_auth_group_access')
                ->where('uid', $id)
                ->delete();
            if ($resGroup) {
                return show('200', '删除成功', '');
            } else {
                return show('201', '删除失败', '');
            }
        } else {
            return show('201', '删除失败', '');
        }
    }

    /**
     * 编辑
     * */
    public function Edit(Request $request)
    {
        $id = input('id');
        if ((request()->isPost())) {
            $data = $request->param();
            $model = new User();
            $res = $model->Edit($data);
            return $res;
        }
        $Data = Db::table('think_user')
            ->where('id', $id)
            ->find();
        $list = Db::table('think_auth_group')
            ->select();
        View::assign('List', $list);
        View::assign('Data', $Data);
        return View::fetch('admin-edit');
    }

    /**  status 启用停用
     */
    public function Status()
    {
        $data = [
            'id' => input('id'),
            'status' => input('status')
        ];
        $res = Db::name('user')
            ->where('id', $data['id'])
            ->update(['status' => $data['status']]);
        return $res;
    }
    public function adminLog(){
        $auth = new Auth();
        $list = Db::table('think_user')
            ->where('id', '>', '1')
            ->order('id')
            ->select();
        $GroupTitle = [];
        foreach ($list as $k => $v) {
            $groupTitle = $auth->getGroups($v['id']);
            $data = ['id' => $v['id'], 'groupTitle' => $groupTitle[0]['title'],'rules'=>$groupTitle[0]['rules']];
            array_push($GroupTitle, $data);
            /**
             * 这里的foreach不知道为啥不好使。
             */
//            $v['groupTitle']  = $_groupTitle[0]['title'];
////            $v['groupTitle'] = $groupTitle;
        }
        View::assign('groupTitle', $GroupTitle);
        View::assign('list', $list);
        return View::fetch('admin-log');
    }
}