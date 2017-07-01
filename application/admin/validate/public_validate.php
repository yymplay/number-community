<?php
namespace app\admin\validate;

use think\Validate;

class public_validate extends Validate
{
    // 验证规则
    protected $rule = [
    	  ['account', 'require|checkPhone','手机号不能为空|手机号格式错误'],
        //['username', 'require|length:1,12', '账号不能为空|账号长度为1-12字符'],
        ['password', 'require|length:6,20', '密码不能为空|请输入6-20位的密码'],
        ['password_up', 'length:6,20', '请输入6-20位的密码'],

        ['pri_name', 'require|length:1,150', '权限名称不能为空！|权限名称的值最长不能超过 150 个字符！'],
        ['module_name', 'require|length:1,30', '模块名称不能为空！|模块名称的值最长不能超过 30 个字符！'],
        ['controller_name', 'require|length:1,30', '控制器名称不能为空！|控制器名称的值最长不能超过 30 个字符！'],
        ['action_name', 'require|length:1,30', '方法名称不能为空！|方法名称的值最长不能超过 30 个字符！'],
        ['parent_id', 'number', '参数错误！'],

    	['username', 'require|length:1,150', '账号不能为空！|账号的值最长不能超过 150 个字符！'],
        //['password', 'require|length:1,30', '密码不能为空！|密码的值最长不能超过 30 个字符！'],
        //['account', 'length:1,32', '手机号的值最长不能超过 32 个字符！'],
        ['nickname', 'require|length:1,50', '姓名不能为空！|姓名的值最长不能超过 50 个字符！'],
        ['status', 'require|in:0,1', '状态需选择！|状态参数错误！'],
        ['role_id', 'number', '参数错误！'],

        ['role_name', 'require|length:1,150|unique:role', '账号不能为空！|账号的值最长不能超过 150 个字符！|组名已存在'],

    	//['face', 'require|between:1,12', '请选择头像|头像参数错误'],
        //设置用户信息 
        ['name', 'require|length:1,50', '姓名不能为空！|姓名的值最长不能超过 30 个字符！'],
        ['phone', 'require|checkPhone','手机号不能为空|手机号格式错误'],
        ['identity', 'require|is_idcard', '身份证号不能为空！|身份证号格式错误'],
        
        ['province', 'require|length:1,30', '请选择通讯地址省份！|咋选的呢！'],
        ['address', 'require|length:1,30', '请输入详细地址！|详细地址最长不能超过 100 个字符！'],
        //社区订单
        
        ['member_id', 'require', '会员参数错误!'],
        ['community_id', 'require|length:1,30', '请选择社区编号！|社区编号参数错误!'],
        ['gongwei', 'require|length:1,30', '请选择工委！|工委参数错误!'],
        ['zongdui', 'require|length:1,30', '请选择纵队！|纵队参数错误!'],
        ['recommend_name', 'length:1,50', '推荐人最长不能超过 50 个字符！'],
        ['recommend_phone', 'checkPhone','推荐人手机号格式错误'],
        // /-----详细信息-----/
        ['detail_sex', 'require', '请选择性别!'],
        ['detail_birthday', 'require', '请选择出生日期!'],
        ['detail_email', 'require', 'email不能为空!'],
        ['detail_job_age', 'require', '工作年限不能为空!'],
        ['detail_jiguan_province', 'require', '请选择籍贯!'],
        ['detail_jiguan_city', 'require', '请选择籍贯!'],
        ['detail_minzu', 'require', '民族不能为空!'],
        ['detail_hyzk', 'require', '请选择婚姻状况!'],
        ['detail_xuexing', 'require', '请选择血型!'],
        ['detail_tizhong', 'require', '体重不能为空!'],
        ['detail_shengao', 'require', '身高不能为空!'],
        ['detail_zzmm', 'require', '政治面貌不能为空!'],
        ['detail_xueli', 'require', '请选择学历!'],
        ['detail_zhuanye', 'require', '专业不能为空!'],
        ['detail_crb', 'require', '请选择是否有传染病史!'],
        ['detail_hjxz', 'require', '请选择户籍性质!'],
        ['detail_huji_province', 'require', '请选择户籍地址!'],
        ['detail_huji_address', 'require', '户籍详细地址!'],
        ['detail_home_phone', 'require', '家庭联系电话不能为空!'],
        ['detail_youbian', 'require', '邮政编码不能为空!'],
        ['detail_dnnl', 'require', '请选择电脑能力!'],        
    ];
    //验证场景
    protected $scene = [

        'admin_login'  =>  ['username','password'],
        'privilege'  =>  ['pri_name','module_name','controller_name','action_name','parent_id'],
        'admin'  =>  ['username','password','account','nickname','status','role_id'],
        'admin_edit'  =>  ['password_up','account','nickname','status','role_id'],
        'role'  =>  ['role_name'],
        'setinfo'=>['name','identity','province','address','detail_sex','detail_birthday','detail_email','detail_job_age','detail_jiguan_province','detail_jiguan_city','detail_minzu','detail_hyzk','detail_xuexing',
                  'detail_tizhong','detail_shengao','detail_zzmm','detail_xueli','detail_zhuanye','detail_crb','detail_hjxz','detail_huji_province','detail_huji_address','detail_home_phone','detail_youbian','detail_dnnl'],
        'archivecenter'=>['name','identity','province','address'],
        'member_register'=>['account','password'],
        'community_order'=>['community_id','gongwei','zongdui','recommend_name','recommend_phone'],

    ];
    public function checkPhone($value){
    	if(preg_match("/^1[34578]{1}\d{9}$/",$value))
    		return true;
    	return false;
    }

     public  function is_idcard( $id ) 
    { 
                  $id = strtoupper($id); 
                  $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
                  $arr_split = array(); 
                  if(!preg_match($regx, $id)) 
                  { 
                    return FALSE; 
                  } 
                  if(15==strlen($id)) //检查15位 
                  { 
                    $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 
                  
                    @preg_match($regx, $id, $arr_split); 
                    //检查生日日期是否正确 
                    $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
                    if(!strtotime($dtm_birth)) 
                    { 
                      return FALSE; 
                    } else { 
                      return TRUE; 
                    } 
                  } 
                  else      //检查18位 
                  { 
                    $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
                    @preg_match($regx, $id, $arr_split); 
                    $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
                    if(!strtotime($dtm_birth)) //检查生日日期是否正确 
                    { 
                      return FALSE; 
                    } 
                    else
                    { 
                      //检验18位身份证的校验码是否正确。 
                      //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。 
                      $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
                      $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
                      $sign = 0; 
                      for ( $i = 0; $i < 17; $i++ ) 
                      { 
                        $b = (int) $id{$i}; 
                        $w = $arr_int[$i]; 
                        $sign += $b * $w; 
                      } 
                      $n = $sign % 11; 
                      $val_num = $arr_ch[$n]; 
                      if ($val_num != substr($id,17, 1)) 
                      { 
                        return FALSE; 
                      } //phpfensi.com 
                      else
                      { 
                        return TRUE; 
                      } 
                    } 
                  } 
                  
    } 
}