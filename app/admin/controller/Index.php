<?php


namespace app\admin\controller;

use think\facade\View;
class Index extends Common
{
  public function index(){
    return  View::fetch();
  }
  public function welcome(){
      return View::fetch('welcome');
  }
}