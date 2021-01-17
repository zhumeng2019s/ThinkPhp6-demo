<?php
/**
 * author:wanghongze
 */

namespace app\admin\controller;


use think\facade\View;
use app\admin\model\AuthRule as ModelAuthRule;
use think\facade\Db;

class AuthRule extends Common
{
    public function index()
    {
        return View::fetch('admin-rule');
    }

    /**
     *
     * 顶级权限
     */
    public function RuleAdd()
    {
        if ((request()->isPost())) {
            $data = request()->param();
            $modelAuth = new ModelAuthRule();
            return $modelAuth->ruleAdd($data);
        }
        return View::fetch('rule-add');
    }

    /**
     *
     * 二级权限
     */
    public function levelAdd()
    {
        $id = input('id');
        $listFid = Db::table('think_auth_rule')->find($id);
        if ((request()->isPost())) {
            $data = request()->param();
            if (isset($data['status'])) {
                $data['status'] = 0;
            }
            if ($data['fid']) {
                $data['fid'] = 2;
            } else {
                $data['fid'] = 1;
            }
            $modelAuth = new ModelAuthRule();
            return $modelAuth->ruleAdd($data);
        }
        View::assign('level', $listFid);
        return View::fetch('level-add');
    }
    /**
     *删除
     */
    public function ruleDel($id)
    {
        $resnull = Db::table('think_auth_rule')->where('id', $id)->find();
        if ($resnull['fid'] == 2) {
            $res = Db::table('think_auth_rule')->where('id', $id)->delete();
            if ($res) {
                $this->delAuthRul($id);
                return show('200', '删除成功');
            } else {
                return show('201', '删除失败');
            }
        } else {
            $resnull = Db::table('think_auth_rule')->where('pid', $id)->find();
            if (!$resnull) {
                $res = Db::table('think_auth_rule')->where('id', $id)->delete();
                if ($res) {
                    $this->delAuthRul($id);
                    return show('200', '删除成功');
                } else {
                    return show('201', '删除失败');
                }
            }
            return show('201', '有下属成员，无法删除😱');
        }


    }
    /**
     *    更新
    */
    public function ruleUpdata($id)
    {
        $data = [
            'id' => $id,
            'status' => input('status')
        ];
        $res = Db::table('think_auth_rule')
            ->where('id', $data['id'])
            ->update(['status' => $data['status']]);
        return $res;
    }
    /**
     * 公共删除方法
    */
    public function delAuthRul($id)
    {
        $gropu = new \app\admin\model\AuthGroup();
        $resgroup = Db::table('think_auth_group')->select();
        $dararules = [];
        foreach ($resgroup as $a => $v) {
            $rulesarr = ['id' => $v['id'], 'title' => $v['title'], 'rules' => $v['rules'], 'del' => '1'];
            $rulesrt = explode(",", $rulesarr['rules']);
            if (in_array($id, $rulesrt)) {
                foreach ($rulesrt as $key => $value) {
                    if ($value === $id)
                        unset($rulesrt[$key]);
                }
                $rulesarr['rules'] = implode(',', $rulesrt);
                array_push($dararules, $rulesarr);
            }
        }
        $gropu->groupEdit($dararules);
    }
}