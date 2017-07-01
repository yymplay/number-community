<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Privilege as PrivilegeModel;
class Privilege extends Base
{
    public function index()
    {
    	$Model = new PrivilegeModel();
    	$data = $Model->getTree();
    	$this->assign('data', $data);
        return $this->fetch();
    }
    public function add(Request $request)
    {
    	$Model = new PrivilegeModel();
    	if($request->isAjax() && $request->isPost()){
    		//获取数据
	    	$data=$request->param();
	    	//判断是否符合验证器规则 
	    	$validate=new \app\admin\validate\public_validate();
	    	if(!$validate->scene('privilege')->check($data))
			    return sendJson(0,$validate->getError());//1参数错误		   	


			//判断是否写入成功数据库
	    	if($Model->allowField(true)->save($data))
	    		return sendJson(1,'添加成功');
	    	else
	    		return sendJson(0,$Model->getError());
    	}else{


		$parentData = $Model->getTree();
    	$this->assign('parentData', $parentData);
        return $this->fetch();
    	}
    }
    public function edit(Request $request){
    	$data=$request->param();
    	$Model = new PrivilegeModel();
    	if($request->isAjax() && $request->isPost()){
    		//获取数据
	    	$data=$request->param();
	    	//判断是否符合验证器规则 
	    	$validate=new \app\admin\validate\public_validate();
	    	if(!$validate->scene('privilege')->check($data))
			    return sendJson(0,$validate->getError());//1参数错误		   	


			//判断是否写入成功数据库
	    	if($Model->allowField(true)->save($data,['id' => $data['id']]))
	    		return sendJson(1,'更新成功');
	    	else
	    		return sendJson(0,$Model->getError());
    	}else{


		$data = $Model->where(['id'=>$data['id']])->find();
    	$this->assign('data', $data);
        return $this->fetch();
    	}
    }
    public function listorder(Request $request) {
    	$data=$request->param();
        $listorder = $data['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        try {
            if ($listorder) {
                foreach ($listorder as $Id => $v) {
                    // 执行更新
                    $id = $this->updateListorderById($Id, $v);
                    if ($id === false) {
                        $errors[] = $Id;
                    }
                }
                if ($errors) {
                    return sendJson(0, '排序失败-' . implode(',', $errors),0, array('jump_url' => $jumpUrl));
                }
                return sendJson(1, '排序成功', 0,array('jump_url' => $jumpUrl));
            }
        }catch (Exception $e) {
            return sendJson(0, $e->getMessage());
        }
        return sendJson(0,'排序数据失败',0,array('jump_url' => $jumpUrl));
    }
    public function updateListorderById($id, $listorder) {
        if(!$id || !is_numeric($id)) {
            exception('ID不合法');
        }
        $data = array('listorder'=>intval($listorder));
        $Model = new PrivilegeModel();
        return $Model->save($data,['id'=>$id]);
    }
    public function setStatus(Request $request){
        $data=$request->param();
        if(db('privilege')->where(['id'=>$data['id']])->delete()!==FALSE){
            return sendJson(1,'删除成功',0,$data);
        }
        return sendJson(0,'删除失败');
    }

}
