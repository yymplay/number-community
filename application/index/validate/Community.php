<?php
namespace app\index\validate;

use think\Validate;

class Community extends Validate
{
    // 验证规则
    protected $rule = [
    	['password', 'require|length:6,20', '社区密码不能为空|请输入6-20位的密码'],
        ['name', 'require|length:1,12', '社区名称不能为空|社区名称长度为1-12字符'],
    ];
    //验证场景
    protected $scene = [
        'add'  =>  ['name','password'],
        'join'  =>  ['password'],
    ];
    public function checkPhone($value){
    	if(preg_match("/^1[34578]{1}\d{9}$/",$value))
    		return true;
    	return false;
    }
}