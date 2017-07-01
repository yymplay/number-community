<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//------------------------
// ThinkPHP 助手函数
//-------------------------

use think\Cache;
use think\Config;
use think\Cookie;
use think\Db;
use think\Debug;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\Lang;
use think\Loader;
use think\Log;
use think\Request;
use think\Response;
use think\Session;
use think\Url;
use think\View;


if (!function_exists('sendJson')) {
    /**
     * 获取\think\response\Json对象实例
     * @param mixed   $data 返回的数据
     * @param integer $code 状态码
     * @param array   $header 头部
     * @param array   $options 参数
     * @return \think\response\Json
     */
    function sendJson($status=1,$info='',$error=0,$data = [], $code = 200, $header = [], $options = [])
    {
        $arr['status']=$status;
        $arr['info']=$info;
        $arr['error']=$error;
        $arr['data']=$data;
        return Response::create($arr, 'json', $code, $header, $options);
    }
}
if (!function_exists('getJson')) {
    /**
     * 获取\think\response\Json对象实例
     * @param mixed   $data 返回的数据
     * @param integer $code 状态码
     * @param array   $header 头部
     * @param array   $options 参数
     * @return \think\response\Json
     */
    function getJson($status=1,$action='',$info='',$error=0,$data = [])
    {
        $arr['status']=$status;
        $arr['action']=$action;
        $arr['info']=$info;
        $arr['error']=$error;
        $arr['data']=$data;
        return json_encode($arr);
    }
}
if (!function_exists('checkcode')) {
    function checkcode($account,$code)
    {
            
            if(!preg_match("/^1[34578]{1}\d{9}$/",$account)){
                
                exit(json_encode(['status'=>0,'info'=>'请输入正确的手机号','error'=>1,'data'=>[]]));
            } 
                

            if(!is_numeric($code))
                exit(json_encode(['status'=>0,'info'=>'请输入正确的验证码','error'=>1,'data'=>[]]));

            //查找当前手机号记录
            $msgData = Db::name('send_msg')
                        ->where('account',$account)
                        ->find();
            if(!$msgData){
                exit(json_encode(['status'=>0,'info'=>'请先发送短信','error'=>3,'data'=>[]]));         
            }else{
                if(time() -  $msgData['addtime'] >300) exit(json_encode(['status'=>0,'info'=>'验证码已过期','error'=>4,'data'=>[]]));
                if($msgData['code'] != $code) exit(json_encode(['status'=>0,'info'=>'验证码错误','error'=>5,'data'=>[]])); 
            }
            return ;
            
    }
}
if (!function_exists('status')) {
    function status($code)
    {
        
            
    }
}
if (!function_exists('getActive')) {
    function getActive($navc)
    {
        $c = strtolower(request()->controller());
        if(strtolower($navc) == $c) {
            return 'class="active"';
        }
        return '';
            
    }
}
if (!function_exists('getStatus')) {
    function getStatus($data,$a,$b)
    {
        
        if($data) {
            return $a;
        }
        return $b;
            
    }
}
if (!function_exists('payType')) {
    function payType($type)
    {
        $arr=[1=>'社区购买',2=>'',3=>''];
            return $arr[$type];   
    }
}
