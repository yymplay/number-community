<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Index extends Base
{
    public function index()
    {
    	$memberCount=Db::name('member')->count();
    	$memberInfoCount=Db::name('member_info')->count();
    	$payCount=Db::name('order')->group('member_id')->count();
    	$priceCount=Db::name('order')->sum('price');
        $provinceCount=Db::name('order')->where('status',1)->count();
        $provinceData=Db::name('order')->field('province,sum(price) price,count(*) count')->group('province')->where('status',1)->select();
        ///dump($provinceCount);
    	//dump($provinceData);
        $province31=['安徽','北京','福建','上海','天津','山西','贵州','河北','浙江','江苏','甘肃','四川','西藏','云南','重庆','青海','新疆','河南','湖南','吉林',
                    '江西','辽宁','山东','陕西','广西','宁夏','黑龙江','内蒙古','海南','湖北','广东'];
        //dump($province31);
        foreach($provinceData as $v){
            $province[$v['province']]=['price'=>$v['price'],'count'=>$v['count']];
        }
        $this->assign('provinceCount',$provinceCount);
        $this->assign('province',$province);
        $this->assign('province31',$province31);
        $this->assign('memberCount',$memberCount);
    	$this->assign('memberInfoCount',$memberInfoCount);
    	$this->assign('payCount',$payCount);
    	$this->assign('priceCount',$priceCount);
    	return $this->fetch();
    }
}
