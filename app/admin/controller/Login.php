<?php


namespace app\admin\controller;


use app\admin\model\User;
use think\facade\View;
use think\Request;

class Login
{
    //登陆
    public function index(Request $request){
        if (!(request()->isPost())) {
            return View::fetch('login');
        }
        $data = $request->param();
        $usermodel = new User();
        $result = $usermodel->adminSel($data);
        return $result;
    }
    //
    public function loginout()
    {
        session(null);
        if (!session('admin')) {
            return redirect((string)url('admin/Login/index'));
        }
    }

}