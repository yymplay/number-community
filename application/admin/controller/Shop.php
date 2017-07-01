<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Admin as AdminModel;
use app\index\model\Member as MemberModel;
use app\admin\model\Order as OrderModel;
class Shop extends Base
{
    public function index(Request $request)
    {   
        $param=$request->param();
        $param['s']=$request->module().'/'.$request->controller().'/'.$request->action();
        $type=input('get.type',0);
        $search_keywords=input('get.search_keywords','');
        $zongdui=input('get.zongdui','');
        $gongwei=input('get.gongwei','');
        $ft=input('get.ft','');
        $et=input('get.et','');
        $where=[];
        switch ($type) {
            case '1':
                $where ['b.order_id']   = $search_keywords;
                break;
            case '2':
                $where ['b.community_id'] = $search_keywords;
                break;

        }
        if($gongwei)
            $where['b.gongwei']=$gongwei;
        if($zongdui)
            $where['b.zongdui']=$zongdui;
        if($ft && $et){
            $where['b.create_time']=['between',[strtotime("$ft 00:00:00"), strtotime("$et 23:59:59")]];
        }else if($ft){
            $where['b.create_time']=['EGT',strtotime("$ft 00:00:00")];
        }else if($et){
            $where['b.create_time']=['ELT', strtotime("$et 23:59:59")];
        }
        // $where= ['phone'   => $search_keywords,//'name' => ['like', '%think%'],
        //         ];
        $Model = new MemberModel();
        $data=Db::name('member')->alias('a')
        ->field('d.nickname,b.order_id,b.community_id,b.recommend_name,b.recommend_phone,b.create_time,b.admin_id,b.pay_method,b.status,b.zongdui,b.gongwei,a.account,c.name')
        ->join('__ORDER__ b','a.id = b.member_id','RIGHT')
        ->join('__MEMBER_INFO__ c','b.member_id=c.member_id','LEFT')
        ->join('__ADMIN__ d','b.admin_id=d.id','LEFT')
        ->where($where)
        ->paginate(5,false,['query'=>$param]);
        //$data = $Model->alias('a')->field('a.*,b.province,b.name xingming,c.order_id')->join('__MEMBER_INFO__ b','a.id = b.member_id','LEFT')->join('__ORDER__ c','a.id = c.member_id','LEFT')->group('a.id')->where($where)->select();
        $this->assign('data', $data);
        $this->assign('type', $type);
        $this->assign('search_keywords', $search_keywords);
        $this->assign('zongdui', $zongdui);
        $this->assign('gongwei', $gongwei);
        $this->assign('ft', $ft);
        $this->assign('et', $et);
        return $this->fetch();
    }

}