<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Role as RoleModel;
use app\admin\model\Privilege as PrivilegeModel;
use think\Db;
class Role extends Base
{
    public function index()
    {
    	$Model = new RoleModel();
    	$data = $Model->select();
    	$this->assign('data', $data);
        return $this->fetch();
    }
    public function add(Request $request)
    {
    	
    	if($request->isAjax() && $request->isPost()){
    		//获取数据
	    	$data=$request->param();
	    	$pris=$data['pris'][0];
	    			$pris=rtrim($pris,',');
	    	//return sendJson(0,'',0,['pris'=>$pris]);
	    	//判断是否符合验证器规则 
	    	$validate=new \app\admin\validate\public_validate();
	    	if(!$validate->scene('role')->check($data))
			    return sendJson(0,$validate->getError());//1参数错误		   	

			$Model = new RoleModel();
			//判断是否写入成功数据库
	    	if($Model->allowField(true)->save($data)){
	    		//return sendJson(1,'添加成功');
	    		
	    		if($data['pris']){
	    			$pris=$data['pris'][0];
	    			$pris=rtrim($pris,',');
	    			$pris=explode(',',$pris);
	    			foreach($pris as $v){
	    				Db::name('role_pri')->insert(['role_id'=>$Model->id,'pri_id'=>$v]);
	    			}
	    			
	    		}
	    		return sendJson(1,'添加成功');//1参数错误	
	    	}
	    	else{
	    		return sendJson(0,$Model->getError());
	    	}
    	}else{

    	$PriModel=new PrivilegeModel();
		$parentData = $PriModel->getTree();
    	$this->assign('parentData', $parentData);
        return $this->fetch();
    	}
    }
    public function edit(Request $request){
    	$data=$request->param();
    	
    	$Model = new RoleModel();
    	if($request->isAjax() && $request->isPost()){

	    	//判断是否符合验证器规则 
	    	$validate=new \app\admin\validate\public_validate();
	    	if(!$validate->scene('role')->check($data))
			    return sendJson(0,$validate->getError());//1参数错误		   	
			
			//判断是否写入成功数据库
	    	if($Model->allowField(true)->save($data,['id' => $data['id']])!==false){
	    		//删除原有数据
				db('role_pri')->where(['role_id'=>$data['id']])->delete();
	    		if($data['pris']){
	    			$pris=$data['pris'][0];
	    			$pris=rtrim($pris,',');
	    			$pris=explode(',',$pris);
	    			foreach($pris as $v){
	    				Db::name('role_pri')->insert(['role_id'=>$data['id'],'pri_id'=>$v]);
	    			}
	    			
	    		}
	    		return sendJson(1,'更新成功');
	    	}
	    	else{
	    		return sendJson(0,$Model->getError(),0,$data);
	    	}
    	}else{

    	$PriModel=new PrivilegeModel();
		$parentData = $PriModel->getTree();//取出权限
		$this->assign('parentData', $parentData);
		$data = $Model->where(['id'=>$data['id']])->find();//取id 和组名
    	$this->assign('data', $data);
    	//取出拥有的权限
    	$rp=Db::name('role_pri')->field('GROUP_CONCAT(pri_id) pri_id')->where('role_id', $data['id'])->find();
    	$this->assign('rp', $rp);

        return $this->fetch();
    	}
    }
    public function setStatus(Request $request){
    	$data=$request->param();
    	if(db('role')->where(['id'=>$data['id']])->delete()!==FALSE){
    		db('role_pri')->where(['role_id'=>$data['id']])->delete();
    		return sendJson(1,'删除成功',0,$data);
    	}
    	return sendJson(0,'删除失败');
    }


}
