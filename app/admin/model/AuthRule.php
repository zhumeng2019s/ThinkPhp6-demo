<?php
/**
 * author:wanghongze
 */

namespace app\admin\model;


use think\Model;

class AuthRule extends Model
{
    //添加
    public function ruleAdd($data)
    {
        if (empty($data)) {
            return show('201', '添加失败');
        }
        $res = $this->save($data);
        if ($res) {
            return show('200', '添加成功');
        } else {
            return show('201', '添加失败');
        }
    }
    /**
     *
    */
    public function ruleUpdata($data){
        if(empty($data)){
            return 0;
        }
        $res = $this->find($data['id']);
        if($res){
            $res['status'] = $data['status'];
            $ress = $res->save();
            if($ress) {
                return show('200', '更新成功');
            }else{
                return show('201', '更新失败');
            }
        }else{
            return show('201', '更新失败');
        }
    }

}