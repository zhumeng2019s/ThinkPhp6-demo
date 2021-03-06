<?php


namespace app\admin\model;

use think\cache\driver\Redis;
use think\facade\Request;
use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    //登陆
    public function adminSel($data)
    {
        if (empty($data)) {
            return 0;
        }
        $data = Request::only(['username','password']);
        $res = $this->where('username', $data['username'])->find();
        if (!$res) {
//            return ['code' => 201, 'msg' => '用户不存在'];
            return show('201', '用户不存在', '');

        }
        $salt = $res['salt'];
        $dataSalt = md5($salt . $data['password'] . $salt);
        $data['password'] = $dataSalt;
        $resData = $this->where($data)->find();
        if ($resData) {
            if (!$res['status'] == 1) {
//                return ['code' => 201, 'msg' => '账号被禁用'];
                return show('201', '账号被禁用', '');

            }
            $administrator = [
                'id' => $res['id'],
                'username' => $res['username'],
                'is_admin' => $res['is_admin']
            ];
            $res->login_times = $res->login_times+1;  //登陆次数
            $res->login_ip = get_client_ip();  //登陆ip
            $res->login_time = time();  //登陆时间
            $res->last_login_ip = get_client_ip();  //上次登陆ip
            $res->last_login_time = time();     //上次登陆时间
            $res->save();
            session('administrator', $administrator);
            $redis = new Redis(config('cache.redis'));
            $redis->lpush('Meail',$res['email']);
//            sendMail('mrwanghongze@163.com','登陆成功','hello word');
//            return show('200', '登陆成功', '');
            return show('200', '登陆成功', '');
        } else {
//            return ['code' => 201, 'msg' => '账号或密码错误'];
            return show('201', '账号或密码错误', '');
        }
    }

    /**
     * 添加
     */
    public function adminAdd($data)
    {
        if (empty($data)) {
            return 0;
        }
        $Salt = $this->salt();
        $this->username = $data['username'];
        $this->password = md5($Salt . $data['password'] . $Salt);
        $this->salt = $Salt;
        $result = $this->save();
        if ($result) {
            $modegroupAccess = new AuthGroupAccess();
            $modegroupAccess->uid = $this->id;
            $modegroupAccess->group_id = $data['like'];
            $modegroupAccess->save();
            return show('200', '添加成功', '');
        } else {
            return show('201', '添加失败', '');
        }
    }

    /**
     * 编辑
     */
    public function Edit($data)
    {
        if (empty($data)) {
            return 0;
        }
        $EditData = $this->where('id', $data['id'])->find();
        $Salt = $this->salt();
        $EditData->username = $data['username'];
        $EditData->password = md5($Salt . $data['password'] . $Salt);
        $EditData->salt = $Salt;
        $result = $EditData->save();
        if ($result) {
            $modegroupAccess = new AuthGroupAccess();
            $EditAccess = $modegroupAccess->where('uid', $data['id'])->find();
            $EditAccess->uid = $data['id'];
            $EditAccess->group_id = $data['like'];
            $EditAccess->save();
            return show('200', '修改成功', '');
        } else {
            return show('201', '修改失败', '');
        }
    }

    /**
     *  加盐
     */
    public function salt()
    {
        // 盐字符集
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < 5; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}