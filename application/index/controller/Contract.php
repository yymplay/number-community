<?php
namespace app\index\controller;
use think\Controller;
class Contract extends Base
{
    public function index()
    {   
    	return $this->fetch('index');
    }
    public function hello(){
    	$data=Db::name('data')->find();
    	$this->assign('data',$data);
    	$this->assign('title','hello page');
    	$this->assign('info','world');
    	return $this->fetch('hello');
    }
    public function getUrl(Request $request){
    	//$request=Request::instance();
    	echo 'url:'.$request->url().'<br/>';
        echo $request->user->id,$request->user->data;
    	return ;
    }
    public function  getparam(){
        dump(input('name'));
    }
}
