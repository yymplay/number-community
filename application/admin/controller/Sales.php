<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Admin as AdminModel;
use app\index\model\Member as MemberModel;
use app\admin\model\Order as OrderModel;
class Sales extends Base
{
    public function index(Request $request)
    {   
        $param=$request->param();
        $param['s']=$request->module().'/'.$request->controller().'/'.$request->action();
        $type=$param['type'];
        $search_keywords=$param['search_keywords'];
        $where=['b.recommend_phone'=>['NEQ','']];
        switch ($type) {
            case '1':
                $where= ['b.recommend_phone'   => $search_keywords];
                break;

        }
        // $where= ['phone'   => $search_keywords,//'name' => ['like', '%think%'],
        //         ];
    	$Model = new MemberModel();
        $data=Db::name('member')->alias('a')
        ->field('d.nickname,b.order_id,b.community_id,b.recommend_name,b.recommend_phone,b.create_time,b.admin_id,b.pay_method,b.status,a.account,c.name')
        ->join('__ORDER__ b','a.id = b.member_id','RIGHT')
        ->join('__MEMBER_INFO__ c','b.member_id=c.member_id','LEFT')
        ->join('__ADMIN__ d','b.admin_id=d.id','LEFT')
        ->where($where)
        ->paginate(1,false,['query'=>$param]);
    	//$data = $Model->alias('a')->field('a.*,b.province,b.name xingming,c.order_id')->join('__MEMBER_INFO__ b','a.id = b.member_id','LEFT')->join('__ORDER__ c','a.id = c.member_id','LEFT')->group('a.id')->where($where)->select();
    	$this->assign('data', $data);
        $this->assign('type', $type);
        $this->assign('search_keywords', $search_keywords);
        return $this->fetch();
    }

}