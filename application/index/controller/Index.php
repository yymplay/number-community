<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Index extends Base
{
    public function index()
    {   
        $ctrl='Blog';
        $rst= action('index/Blog/update',5,'event');
        $str="http://www.youyizu.com/landingpagehz/index.html";
$rule="/LandingPage(.*)\//i";
preg_match_all($rule,$str,$arr);
return $arr[1][0];
        //dump($event);
        //$rst=$event->update(45);
          return $rst;  
        //return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
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
