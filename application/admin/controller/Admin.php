<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Admin as AdminModel;
class Admin extends Base
{
    public function index(Request $request)
    {   
    	$Model = new AdminModel();
    	$data = $Model->alias('a')->field('a.*,b.role_name')->join('__ROLE__ b','a.role_id = b.id','LEFT')->select();
    	$this->assign('data', $data);
        return $this->fetch();
    }
  	public function add(Request $request)
    {
    	$Model = new AdminModel();
    	if($request->isAjax() && $request->isPost()){
    		//获取数据
	    	$data=$request->param();
	    	//判断是否符合验证器规则 
	    	$validate=new \app\admin\validate\public_validate();
	    	if(!$validate->scene('admin')->check($data))
			    return sendJson(0,$validate->getError());//1参数错误		   	

           $data['password']= md5('szwl'.$data['password'].'szwl');
			//判断是否写入成功数据库
	    	if($Model->allowField(true)->save($data))
	    		return sendJson(1,'添加成功');
	    	else
	    		return sendJson(0,$Model->getError());
    	}else{

        $data = Db::name('role')->select();//取id 和组名
        $this->assign('data', $data);

        return $this->fetch();
    	}
    }
    public function setStatus(Request $request){
        $data=$request->param();
        if(db('admin')->where(['id'=>$data['id']])->delete()!==FALSE){
            return sendJson(1,'删除成功',0,$data);
        }
        return sendJson(0,'删除失败');
    }
    public function edit(Request $request){
        $data=$request->param();
        $Model = new AdminModel();
        if($request->isAjax() && $request->isPost()){
            //获取数据
            $data=$request->param();
            if(empty($data['password'])){
                unset($data['password']);
            }else{
                $data['password']=md5('izhiwo'.$data['password'].'izhiwo');
            }
            //判断是否符合验证器规则 
            $validate=new \app\admin\validate\public_validate();
            if(!$validate->scene('admin_edit')->check($data))
                return sendJson(0,$validate->getError());//1参数错误            


            //判断是否写入成功数据库
            if($Model->allowField(true)->save($data,['id' => $data['id']]))
                return sendJson(1,'更新成功');
            else
                return sendJson(0,$Model->getError());
        }else{


        $data = $Model->where(['id'=>$data['id']])->find();
        $this->assign('data', $data);
        $role = Db::name('role')->select();//取id 和组名
        $this->assign('role', $role);
        return $this->fetch();
        }
    }  
}
