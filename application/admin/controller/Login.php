<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Admin as AdminModel;
class Login extends Controller
{
    public function index()
    {
       return $this->fetch();
    }
    public function checkLogin(Request $request)
    {
    	//获取数据
    	$data=$request->param();
    	//判断是否符合验证器规则 
    	$validate=new \app\admin\validate\public_validate();
    	if(!$validate->scene('admin_login')->check($data))
       		return sendJson(0,$validate->getError());//1参数错误
       	//查询数据库
        $where=[];
        $where['username'] =   $data['username'];
		$where['password']=   md5('szwl'.$data['password'].'szwl');
		$MemberModel      =   new AdminModel();
		$Member           =   $MemberModel->where($where)->find();
		// dump($Member);
		// echo md5('szwl123456szwl');
  //       //
    	if($Member){
    		if(($Member['status']+0)!==1)
    			return sendJson(0,'该账号已禁用');

			// 更新数据用户信息
			$where['id'] = $Member['id'];
			$save['last_time'] = time();
    		if(!$MemberModel->allowField(true)->save($save,['id' => $Member['id']]))
    			return sendJson(0,'服务器繁忙请稍后再试');
    		//设置登录信息
    		session('user',$Member);
    		return sendJson(1,'登录成功');
    	}else{
    		return sendJson(0,'账号或密码错误');
    	}

    }
    public function loginout(){
        session('user',null);
        $this->redirect('/index.php?s=admin/login/index');
    }
}
