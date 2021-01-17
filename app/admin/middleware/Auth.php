<?php
declare (strict_types=1);

namespace app\admin\middleware;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $controller = strtolower($request->controller());
        $action = strtolower($request->action());
        $root = strtolower($request->root());
        if ('/admin' == $root) { # 后台模块
            if ('login' == $controller) {
                if (session('?administrator')) {//登录
                    return redirect((string)url('admin/Index/index'));
                }
            } else {
                if (!session('?administrator')) {//未登录
                    return redirect((string)url('admin/Login/index'));
                }
                $auth = new \liliuwei\think\Auth();
                $administrator = session('administrator');
                if (1 != $administrator['is_admin']) { //超级管理员
                    $no_auth = [
                        'index/index',
                        'index/welcome'
                    ];
                    $url = strtolower($controller . "/" . $action);
                    if (!in_array($url, $no_auth)) {
                        if (!$auth->check($url, $administrator['id'])) {
                            return response('没有权限');
                        }
                    }
                }
            }
        } else {
            return redirect((string)url('admin/Login/index'));
        }
        return $next($request);
    }
}
