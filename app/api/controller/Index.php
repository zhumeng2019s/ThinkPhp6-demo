<?php
/**
 * author:wanghongze
 */

namespace app\api\controller;


use app\api\model\AppErrand;

class Index
{
    public function index(){
       $appErrand = new AppErrand();
        return json($appErrand->SelectAll());
    }
}