<?php


namespace app\admin\controller;

use think\facade\Db;
use think\facade\View;
use app\admin\model\AuthGroup as ModelAuthGroup;

class AuthGroup extends Common
{
    /**
     * 添加
     */
    public function index()
    {
        $list = Db::table('think_auth_group')
            ->select();
        View::assign('list', $list);
        return View::fetch('group');
    }

    /**
     * 添加
     */
    function groupAdd()
    {
        if (!(request()->isPost())) {
            $groupList = Db::table('think_auth_rule')->select();
            View::assign('List', $groupList);
            return View::fetch('group-add');
        }
        $manage = input('post.rule/a');
        $data = [
            'title' => input('title'),
            'rules' => implode(",", $manage)
        ];
        $modelGroup = new ModelAuthGroup();
        $resGroup = $modelGroup->groupAdd($data);
        return $resGroup;
    }

    /**
     *删除
     */
    public function groupDel()
    {
        $id = input('id');
        $resAuthGroupAcess = Db::table('think_auth_group_access')
            ->where('group_id', $id)
            ->find();
        if ($resAuthGroupAcess) {
            return show('201', '该分组还有成员！，请先移除成员😭');
        } else {
            $res = Db::table('think_auth_group')->where('id', $id)->delete();
            if ($res) {
                return show('200', '删除成功');
            } else {
                return show('201', '删除失败');
            }
        }

    }

    /**
     *
     */
    public function edit()
    {
        if ((request()->isPost())) {
//            halt('1111');
            $manage = input('post.rule/a');
            $data = [
                'id' => input('id'),
                'title' => input('title'),
                'rules' => implode(",", $manage)
            ];
            array($data);
            $modelGroupo = new ModelAuthGroup();
            $res = $modelGroupo->groupEdit($data);
            return $res;
        }
        $id = input('id');
        //组下的权限
        $data = Db::table('think_auth_group')->find($id);
//        $list = Db::table('think_auth_rule')->all($data['rules']);
        //全部权限
        $rulearr = Db::table('think_auth_rule')->select();
        $grouparr = explode(",", $data['rules']);
        View::assign('data', $data);
        View::assign('groupList', $rulearr);
        View::assign('grouparr', $grouparr);
        return View::fetch('group-edit');
    }
}