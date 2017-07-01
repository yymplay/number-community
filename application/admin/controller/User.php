<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Admin as AdminModel;
use app\index\model\Member as MemberModel;
use app\admin\model\Order as OrderModel;
class User extends Base
{
    public function index(Request $request)
    {   
        $type=input('post.type',0);
        $search_keywords=input('post.search_keywords','');
        $where=[];
        switch ($type) {
            case '1':
                $where= ['a.account'   => $search_keywords];
                break;
            case '2':
                $where= ['b.name' => ['like', "%$search_keywords%"]];
                break;
            case '3':
                $where= ['b.province' => ['like', "%$search_keywords%"]];
                break;
        }
        // $where= ['phone'   => $search_keywords,//'name' => ['like', '%think%'],
        //         ];
    	$Model = new MemberModel();
    	$data = $Model->alias('a')->field('a.*,b.province,b.name xingming,c.order_id')->join('__MEMBER_INFO__ b','a.id = b.member_id','LEFT')->join('__ORDER__ c','a.id = c.member_id','LEFT')->group('a.id')->where($where)->select();
    	$this->assign('data', $data);
        $this->assign('type', $type);
        $this->assign('search_keywords', $search_keywords);
        return $this->fetch();
    }
    public function setInfo(Request $request){
        //获取数据
        $data=$request->param();
        if($data['member_id']<=0) return sendJson(0,'参数错误');
        $memberinfo=Db::name('member_info')->where('member_id',$data['member_id'])->find();
        if($memberinfo){
            $memberinfo['detail_info']=json_decode($memberinfo['detail'],true);
        }
        
        $isCommunity=Db::name('order')->where('member_id',$data['member_id'])->where('type',1)->find();
        if($request->isAjax() && $request->isPost()){
            //  //判断是否符合验证器规则 
            $validate=new \app\admin\validate\public_validate();
            if(!$validate->scene('setinfo')->check($data))
                return sendJson(0,$validate->getError());//1参数错误            
            /*******************图片逻辑*************************/
            //如果没有查到这个人的数据
            if(!$memberinfo){
                //获取 图片对象
                $file = $request->file('sfzz');
                $file2 = $request->file('sfzf');
                if (empty($file)) 
                    return sendJson(0,'请选择身份证正面照片',0,$data);
                if (empty($file2)) 
                    return sendJson(0,'请选择身份证反面照片',0,$data);
                
                /*********************************************/
                //
                $uprst=$this->up($request,$file);
                if($uprst['status']===0)
                    return sendJson(0,'身份证正面:'.$uprst['info'],0,$savedata);
                //得到图片地址
                $savedata['sfzz']=$uprst['url'];

                $uprst=$this->up($request,$file2);
                if($uprst['status']===0){
                    //删除正面 
                    unlink('./uploads/'.$savedata['sfzz']);
                    return sendJson(0,'身份证反面:'.$uprst['info'],0,$savedata);
                }
                $savedata['sfzf']=$uprst['url'];
            }else{
                $file = $request->file('sfzz');
                $file2 = $request->file('sfzf');
                if (!empty($file)) {
                    $uprst=$this->up($request,$file);
                    if($uprst['status']===0)
                        return sendJson(0,'身份证正面:'.$uprst['info'],0,$savedata);
                    //得到图片地址
                    $data['sfzz']=$uprst['url'];
                    //删除前图
                    unlink('./uploads/'.$memberinfo['sfzz']);
                }
                if (!empty($file2)) {
                    $uprst=$this->up($request,$file2);
                    if($uprst['status']===0)
                        return sendJson(0,'身份证反面:'.$uprst['info'],0,$data);
                    //得到图片地址
                    $data['sfzf']=$uprst['url'];
                    //删除前图
                    unlink('./uploads/'.$memberinfo['sfzf']);
                }
            }
            

           /*******************图片逻辑end*************************/
           //  //判断是否写入成功数据库 并判断 已有数据则是更新 没有则是新增
           $savedata['member_id']=$data['member_id'];
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
                return sendJson(1,'设置成功',0,['id'=>$data['member_id']]);
             else if($dbrst!==false)
                return sendJson(1,'设置成功',0,['id'=>$data['member_id']]);
             else 
                return sendJson(0,'设置失败');
        }else{
            $this->assign('member_id',$data['member_id']);
            $this->assign('memberinfo',$memberinfo);
            $this->assign('isCommunity',$isCommunity);
            return $this->fetch();
        }
    }

  	public function add(Request $request)
    {
    	if($request->isAjax() && $request->isPost()){
    		//获取注册数据
            $data=$request->param();
            //判断是否符合验证器规则 
            $validate=new \app\admin\validate\public_validate();
            if(!$validate->scene('member_register')->check($data))
                return sendJson(0,$validate->getError(),1);//1参数错误          

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
    	}else{

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
    public function community(Request $request){
        //获取数据
        $data=$request->param();
        if($request->isAjax() && $request->isPost()){
            $Model= new OrderModel();
            //判断是否符合验证器规则 
            $validate=new \app\admin\validate\public_validate();
            if(!$validate->scene('community_order')->check($data))
                return sendJson(0,$validate->getError());//1参数错误
            //判断member_id
            $data['member_id']=$data['member_id']+0;
            if(!Db::name('member')->where('id',$data['member_id'])->find())
                return sendJson(0,'用户参数错误',0,$data);
            //根据社区id 并且member_id=0,查询
            if(!Db::table('szwl_communityID')->where('community_id',$data['community_id'])->where('member_id',0)->find()){
                //加一步查询一下 订单 判断 下单时间>半个小时 并且status=0 的话 就不 return 让别人继续下单
                
                return sendJson(0,'社区编号不存在或已被认购');
            }

            Db::startTrans();
            try{
                //先设置为文件锁 ,后续用队列
                $fp=fopen(ROOT_PATH.'/order.lock','r');
                flock($fp,LOCK_EX);//排它锁

                //更新 会员id 关联 社区ID
                if(!Db::table('szwl_communityID')->where('community_id',$data['community_id'])->where('member_id',0)->setField('member_id',$data['member_id'])){
                    //释放锁
                    flock($fp,LOCK_UN);
                    fclose($fp);
                    return sendJson(0,'服务器繁忙请稍后再试');    
                }
                $data['order_id']=date('ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                $data['create_time']=time();//创建时间
                $data['price']=10000;//金额
                $data['type']=1;//1为 社区购买
                $data['pay_method']='后台添加';//支付方式后台添加
                $data['status']=1;//后台添加的直接设置为已支付  
                $data['admin_id']=session('user')->id;// 哪个管理员添加的 APP的为0;
                //
                if($Model->allowField(true)->save($data)){
                    Db::commit();
                    //释放锁
                    flock($fp,LOCK_UN);
                    fclose($fp);
                    return sendJson(1,'添加成功',0,$data);
                }
                else{
                    //释放锁
                    flock($fp,LOCK_UN);
                    fclose($fp);
                    return sendJson(0,$Model->getError());
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return sendJson(0,$e->getMessage());
            }
        }else{
            $this->assign('member_id',$data['member_id']);
            return $this->fetch();
        }
    }
    public function look(Request $request){
        //获取数据
        $data=$request->param();
        //判断member_id
        $data['member_id']=$data['member_id']+0;
        $user=Db::name('member')->alias('a')->field('a.account,b.*')->join('__MEMBER_INFO__ b','a.id = b.member_id','LEFT')->where('a.id',$data['member_id'])->find();
        if(!$user)
            return sendJson(0,'用户参数错误',0,$data);
        //查询用户订单
        $order=Db::name('order')->where('member_id',$data['member_id'])->select();
        $user['detail_info']=json_decode($user['detail'],true);
        $this->assign('data',$user);
        $this->assign('member_id',$data['member_id']);
        $this->assign('order',$order);
        return $this->fetch();
        
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
