<?php


namespace app\admin\controller;

use app\admin\model\User;
use think\cache\driver\Redis;
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
//    public function test(){
//        $redis = new Redis(config('cache.redis'));
//        return $redis->lLen('Meail');
////        $redis->lpush('Meail','1151534434@qq.com');
////        $redis->rPop('Meail');
////        return  $redis->exists('Meail');
////        $toMeail = $redis->rPop('list');
////        return $toMeail;
////        $redis->lpush('list','11111');
////        $redis->lpush('list','html');
////        return $redis->get('list');
////        return json($redis->lRange('list',0,-1));
//
//    }
}