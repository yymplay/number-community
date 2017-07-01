<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
class Community extends Base
{   

    // //创建社区
    // public function c_create($data)
    // {
    //     $data = $request->param();
    //     $name = isset($data['name']) ? $data['name'] : ''; // 社区名称 
    //     $pass = isset($data['pass']) ? md5($data['pass']) : ''; // 社区密码
    //     $member_id = isset($data['member_id']) ? $data['member_id'] : ''; // 社区id
    //     $member_name = isset($data['member_name']) ? $data['member_name'] : ''; // 社区id

    //     if(empty($name) || empty($pass) || empty($member_id) || empty($member_name)){
    //         return sendJson(0,'缺少参数',0);
    //     }

    //     //确保数据没问题之后 先走支付逻辑 成功之后在插入数据
    //     $zhifu = true;
    //     if($zhifu){
    //         $res = Db::name('community')
    //             ->insert([
    //                 'name'=>$name,
    //                 'password'=>$pass,
    //                 'member_id'=>$member_id,
    //                 'member_name'=>$member_name,
    //                 ]);
    //         }
    //         if($res) return sendJson(1,'创建成功',0);
            
    //         return sendJson(0,'创建失败',0);
    // }

    // //社区列表
    // public function c_list(Request $request)
    // {
    //     $data = $request->param();
    //     $keyword = isset($data['keyword']) ? $data['keyword'] : '';
    //     $pagesize= isset($data['pagesize'])? $data['pagesize'] : 10;
    //     $pagenow = isset($data['pagenow']) ? $pagenow : 1;

    //     if(empty($keyword)){
    //         $count = Db::name('community')
    //                 ->count();
    //     }else{ //有搜索条件
    //         if(is_numeric($keyword)){
    //             $count = Db::name('community')
    //                 ->where('id','=',$keyword)
    //                 ->count();
    //         }else{
    //             $count = Db::name('community')
    //                 ->where('name|member_name','like',"'%".$keyword."%'")
    //                 ->count();
    //         }
            
    //     }
    //     //没有数据
    //     if($count == 0){
    //         return sendJson(0,'暂无数据',0);
    //     }

    //     $list = Db::name('community')
    //         ->limit(($pagenow-1)*$pagesize,$pagesize);
    //         ->select();

    //     if($list){
    //         foreach($list as $k => $v){
    //             if($v['count'] > 1){
    //                 $sum = Db::name('community_member')
    //                     ->where('member_id','=',$v['member_id'])
    //                     ->count();
    //                 $list[$k]['join'] = $sum;
    //             }
    //         }
    //         return sendJson(1,'获取成功',0,['count'=>$count,'list'=>$list]);
    //     }else{
    //         return sendJson(1,'获取列表失败',1]);
    //     }

    // }

    // //加入社区的活
    // public function addCommunity()
    // {
    //     $data = $request->param();
    //     $member_id = $data['member_id']; //申请者的id
    //     $community_id = $data['community_id']; //申请社区的id
    //     $pass = md5($data['pass']);
    //     $info = Db::name('community')
    //                 ->where('id','=',$community_id)
    //                 ->where('password','=',$pass)
    //                 ->find();
    //     if(!$info){
    //         return sendJson(0,'社区密码错误',0);
    //     }
    //     $count = $info['count'];
    //     if($count == 1) {
    //         return sendJson(0,'社区人数已达上限',0);
    //     }
    //     $sum = Db::name('community_member')
    //                 ->where('community_id','=',$community_id)
    //                 ->count();
    //     if($count <= $sum){
    //         return sendJson(0,'社区人数已达上限',0);
    //     }

    //     //判断是否已经加入该社区
    //     $exist = Db::name('community_member')
    //         ->where([
    //                 'community_id' => $community_id,
    //                 'member_id'    => $member_id,
    //             ])
    //         ->find();
    //     if($exist)
    //         return sendJson(0,'您已经加入该社区',0);

    //     $res = Db::name('community_member')
    //         ->insert([
    //             'community_id'=>$community_id,
    //             'member_id'=>$member_id,
    //             ]);
    //     if($res) return sendJson(1,'加入社区成功',0);

    //     return sendJson(0,'加入社区失败',0);
    // }
    public function archivecenter(Request $request){
        $data=$request->param();//获取参数
        //根据token 查询是哪个用户
        if(!isset($data['token']))
            return sendJson(0,'参数错误');
        $memberid=Db::name('member')->where('token',$data['token'])->value('id');
        if(($memberid+0)<=0) 
            return sendJson(0,'参数错误');
        $memberinfo=Db::name('member_info')->where('member_id',$memberid)->find();
        if($request->isAjax() && $request->isPost()){
            //  //判断是否符合验证器规则 
            $validate=new \app\admin\validate\public_validate();
            if(!$validate->scene('archivecenter')->check($data))
                return sendJson(0,$validate->getError());//1参数错误            
           
           //  //判断是否写入成功数据库 并判断 已有数据则是更新 没有则是新增
           $savedata['member_id']=$memberid;
           $savedata['name']=$data['name'];
           $savedata['identity']=$data['identity'];
           $savedata['province']=$data['province'];
           $savedata['city']=$data['city'];
           $savedata['area']=$data['area'];
           $savedata['address']=$data['address'];
           if($memberinfo){
                $dbrst=Db::name('member_info')->where('member_id',$memberinfo['member_id'])->update($savedata);
           }else{
                $dbrst=Db::name('member_info')->insert($savedata);
           }
             if($dbrst)
                return sendJson(1,'设置成功',0,['id'=>$data['member_id']]);
             else if($dbrst!==false)
                return sendJson(1,'设置成功',0,['id'=>$data['member_id']]);
             else 
                return sendJson(0,'设置失败');
        }else{
            $this->assign('token',$data['token']);
            $this->assign('memberinfo',$memberinfo);
            return $this->fetch();
        }
    }
}
