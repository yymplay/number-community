<?php
namespace app\admin\model;

use think\Model;

class Privilege extends Model
{
	public function getTree()
	{
		$data = $this->order('listorder', 'desc')->select();
		return $this->_reSort($data);
	}
	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
	}
	public function getMenu(){
		$role_id=session('user')->role_id;
		// if($role_id===1){
		// 	return $data = $this->alias('a')->where('parent_id',0)->order('listorder', 'desc')->select();
		// }
		return $data = $this->alias('a')
		->join('__ROLE_PRI__ b ','b.pri_id=a.id','RIGHT')
		->where('role_id',$role_id)
		->where('parent_id',0)->order('listorder', 'desc')->select();

	}
	public function hasPri(){
		$role_id=session('user')->role_id;
		// if($role_id===1){
		// 	return $data = $this->alias('a')->where('parent_id',0)->order('listorder', 'desc')->select();
		// }
		$where['b.role_id']=$role_id;
		$where['a.module_name']=strtolower(request()->module());
		$where['a.controller_name']=strtolower(request()->controller());
		$where['a.action_name']=strtolower(request()->action());

		return $data = $this->alias('a')
		->join('__ROLE_PRI__ b ','b.pri_id=a.id','RIGHT')
		->where($where)
		->order('listorder', 'desc')->select();
	}
}