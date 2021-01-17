<?php


namespace app\admin\model;

use think\Model;
class AuthGroup extends Model
{
    /**
     * 添加
    */
    public function groupAdd($data)
    {
        if (empty($data)) {
            return 0;
        }
        $res = $this->save($data);
        if ($res) {
           return  show('200','添加成功');
        } else {
            return  show('201','添加失败');
        }
    }
    /**
     * 删除
     */
//
    /**
     *
     * 修改
    */
    public function groupEdit($data)
    {
        if (empty($data)) {
            return 0;
        }
        $action = request()->action();
        if ($action != 'edit') {
            $Count = count($data);
            for ($i = 0; $i < $Count; $i++) {
                $res = $this->where('id', $data[$i]['id'])->find();
                $res->title = $data[$i]['title'];
                $res->rules = $data[$i]['rules'];
                $res->save();
            }
            return $res;
        }
        $res = $this->where('id', $data['id'])->find();
        $res->title = $data['title'];
        $res->rules = $data['rules'];
        $resData = $res->save();
        if ($resData) {
           return  show('200','修改成功');
        } else {
            return  show('201','修改失败');

        }
    }
}