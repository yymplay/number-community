<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
class Base extends Controller
{
    public function __construct(Request $request){
        parent::__construct();
    }
    
}
