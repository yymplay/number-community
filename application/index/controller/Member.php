<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Member as MemberModel;
use think\Request;
class Member extends Base
{
    public function register(Request $request){
    	//获取注册数据
    	$data=$request->param();
    	//判断是否符合验证器规则 
    	$validate=validate('Member');
    	if(!$validate->scene('register')->check($data))
		    return sendJson(0,$validate->getError(),1);//1参数错误		   	

    	//判断短信验证码是否正确
  		checkcode($data['account'],$data['checkcode']);//2验证码错误

  		//加密
    	$data['password']=md5('szwl'.$data['password'].'szwl');
		$data['create_time']=time();
		$data['regip']=$_SERVER['REMOTE_ADDR'];
		//实例化会员表
    	$MemberModel = new MemberModel();

    	//判断是否已存在
    	if($MemberModel->where('account',$data['account'])->find())
    		return sendJson(0,'该手机号已注册',6);

		//判断是否写入成功数据库
    	if($MemberModel->allowField(true)->save($data))
    		return sendJson(1,'成功注册',0,['id'=>$MemberModel->id]);
    	else
    		return sendJson(0,$MemberModel->getError(),2);
    }
    public function login(Request $request){
    	//获取登录数据
    	$data=$request->param();
    	
		//判断是否符合验证器规则 
    	$validate=validate('Member');
    	if(!$validate->scene('login')->check($data))
		    return sendJson(0,$validate->getError(),1);
        
		//查询数据库
        $where=[];
        $where['account'] =   $data['account'];
		$where['password']=   md5('szwl'.$data['password'].'szwl');
		$MemberModel      =   new MemberModel();
		$Member           =   $MemberModel->where($where)->find();

        //
    	if($Member){
    		//生成token
    		$time=time();
			$token = md5($Member['id'].$Member['account'].$time);
			// 更新数据用户信息
			$where['id'] = $Member['id'];
			$save['token'] 	   = $token;
			$save['last_time'] = $time;
			$save['last_ip']   = $_SERVER['REMOTE_ADDR'];
    		if(!$MemberModel->allowField(true)->save($save,['id' => $Member['id']]))
    			return sendJson(0,'服务器繁忙请稍后再试',2);
    		return sendJson(1,'登录成功',0,['token'=>$token]);
    	}else{
    		return sendJson(0,'账号或密码错误',3);
    	}
    }
    public function setinfo(Request $request){
        //获取数据
        $data=$request->param();
        //根据token 查询是哪个用户
        if(!isset($data['token']))
            return sendJson(0,'参数错误');
        $memberid=Db::name('member')->where('token',$data['token'])->value('id');
        if(($memberid+0)<=0) 
            return sendJson(0,'参数错误');
        $memberinfo=Db::name('member_info')->where('member_id',$memberid)->find();
        if($memberinfo){
            $memberinfo['detail_info']=json_decode($memberinfo['detail'],true);
        }
        
        if($request->isAjax() && $request->isPost()){
            //  //判断是否符合验证器规则 
            $validate=new \app\admin\validate\public_validate();
            if(!$validate->scene('setinfo')->check($data))
                return sendJson(0,$validate->getError());//1参数错误            
           //  //判断是否写入成功数据库 并判断 已有数据则是更新 没有则是新增
           $savedata['member_id']=$memberid;
           $savedata['name']=$data['name'];
           $savedata['identity']=$data['identity'];
           $savedata['province']=$data['province'];
           $savedata['city']=$data['city'];
           $savedata['area']=$data['area'];
           $savedata['address']=$data['address'];
           $savedata['detail']=json_encode($data,JSON_UNESCAPED_UNICODE);
           if($memberinfo){
                $dbrst=Db::name('member_info')->where('member_id',$memberinfo['member_id'])->update($savedata);
           }else{
                $dbrst=Db::name('member_info')->insert($savedata);
           }
             if($dbrst)
                return sendJson(1,'设置成功');
             else if($dbrst!==false)
                return sendJson(1,'设置成功');
             else 
                return sendJson(0,'设置失败');
        }else{
            $this->assign('token',$data['token']);
            $this->assign('memberinfo',$memberinfo);
            return $this->fetch();
        }
        
    }
    protected function up($request,$file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(['size' => 1024*1024*4,'ext' => 'jpg,png,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                return ['status'=>1,'url'=>$info->getSaveName()];
            } else {
                // 上传失败获取错误信息
                return ['status'=>0,'info'=>$file->getError()];
            }
    }
}
