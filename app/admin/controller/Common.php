<?php


namespace app\admin\controller;

use liliuwei\think\Auth;
use think\facade\Db;
use think\facade\View;

class Common
{
    protected $middleware = [
        //Auth::class
        \app\admin\middleware\Auth::class=>['except'=>['upload']],
    ];
    public function __construct()
    {

        $auth = new Auth();
        $list = Db::table('think_user')->where('id', session('administrator.id'))->select();
        $Groupurls =[];
        foreach ($list as $k => &$v) {
            $_groupTitle = $auth->getGroups($v['id']);
            $groupTitle = $_groupTitle[0]['rules'];
            $v['groupurl'] = $groupTitle;
            array_push($Groupurls,$v);
        }
        $array = explode(",", $Groupurls[0]['groupurl']);
        $auth_url = new \app\admin\model\AuthRule();
        $res = $auth_url->select();
        $listData = [];
        foreach ($res as $k => $v) {
            foreach ($array as $a => $b) {
                if ($v['id'] == $b) {
                    array_push($listData, $v);
                }
            }
        }
        View::assign('listData', $listData);
        /***
         *
         *
        */
        $listFid0 = Db::table('think_auth_rule')->where('fid', 0)->select();
        //->一级
        $listFid1 = Db::table('think_auth_rule')->where('fid', 1)->select();
        //->二级
        $listFid2 = Db::table('think_auth_rule')->where('fid', 2)->select();
        View::assign('List0', $listFid0);
        View::assign('List1', $listFid1);
        View::assign('List2', $listFid2);

    }
}