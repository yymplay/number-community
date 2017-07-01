<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
class Menu extends Controller
{
    public function index()
    {
       return $this->fetch();
    }
    public function add()
    {
       return $this->fetch();
    }

}
