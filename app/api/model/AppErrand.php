<?php
/**
 * author:wanghongze
 */

namespace app\api\model;

use think\Model;
class AppErrand extends Model
{
    public function SelectAll(){
        return $this->select();
    }
}