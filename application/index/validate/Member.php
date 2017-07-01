<?php
namespace app\index\validate;

use think\Validate;

class Member extends Validate
{
    // 验证规则
    protected $rule = [
    	['account', 'require|checkPhone','账号不能为空|账号格式错误'],
        ['username', 'require|length:1,12', '账号不能为空|账号长度为1-12字符'],
    	['password', 'require|length:6,20', '密码不能为空|请输入6-20位的密码'],
        ['checkcode', 'require|length:6', '验证码不能为空|验证码为6字符'],
        ['token', 'require|length:32', '验证码不能为空|验证码为6字符'],
        ['name', 'require|length:1,12', '角色名不能为空|角色名长度为1-12字符'],
    	['face', 'require|between:1,12', '请选择头像|头像参数错误'],
    ];
    //验证场景
    protected $scene = [
        'register'  =>  ['account','password','checkcode'],
        'login'  =>  ['account','password'],
        'admin_login'  =>  ['username','password'],
        'checktoken'  =>  ['token'],
        'setinfo'  =>  ['name','face'],
    ];
    public function checkPhone($value){
    	if(preg_match("/^1[34578]{1}\d{9}$/",$value))
    		return true;
    	return false;
    }
}