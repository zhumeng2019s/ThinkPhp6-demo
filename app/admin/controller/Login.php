<?php


namespace app\admin\controller;


use app\admin\model\User;
use think\captcha\facade\Captcha;
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
        if(!captcha_check($data['captcha'])){
           return  show('201','验证码错误','');
        };
        $usermodel = new User();
        $result = $usermodel->adminSel($data);
        return $result;
//        return $data;
    }
    //
    public function loginout()
    {
        session(null);
        if (!session('admin')) {
            return redirect((string)url('admin/Login/index'));
        }
    }
    public function captcha($id = '')
    {
        return captcha($id);
    }
}