<?php
namespace app\index\event;
use think\Controller;
use think\Db;
use app\index\model\Community as CommunityModel;
use app\index\model\CommunityMember as CommunityMemberModel;
class Community extends Controller
{   

    //创建社区
    public function add($data)
    {
        //判断是否符合验证器规则 
        $validate=new \app\index\validate\Community();
        if(!$validate->scene('add')->check($data))
            return getJson(0,'Community/add',$validate->getError(),1);
        //实例化会员表
        $Model = new CommunityModel();
        $data['password']=md5('szwl'.$data['password'].'szwl');
        //判断是否写入成功数据库
        if($Model->allowField(['name','member_id','member_name','password'])->save($data))
            return getJson(1,'Community/add','新建成功',0,['community_id'=>$Model->id]);
        else
            return getJson(0,'Community/add','服务器繁忙稍后重试',2);
    }

    //社区列表
    public function lists($data)
    {
        $keyword = isset($data['keyword']) ? $data['keyword'] : '';
        $pagesize= 5;
        $pagenow = isset($data['pagenow']) ? $data['pagenow'] : 1;
        $pagenow +=0;
        $Model = new CommunityModel();
        $member = new CommunityMemberModel();

        if(empty($keyword)){
            $count = $Model->where('status',1)->count();
        }else{ //有搜索条件
            if(is_numeric($keyword)){
                $count = $Model->where('id',$keyword)->where('status',1)->count();
            }else{
                $count = $Model->where('name|member_name','like',"%{$keyword}%")->where('status',1)->count();
            }
            
        }
        //没有数据
        if($count == 0){
            return getJson(0,'Community/lists','暂无数据',3);
        }

        $lists = $Model->alias('a')->field('a.id , a.name , a.member_id , b.name member_name ,b.face,a.count , a.allow_count')->join('__MEMBER__ b','a.member_id = b.id','LEFT')->where('a.status',1)->limit(($pagenow-1)*$pagesize,$pagesize)->select();

        if($lists){
            foreach($lists as $k => $v){
                if($v['count'] < $v['allow_count'])
                    $lists[$k]['allow_join'] = 1;
                else
                    $lists[$k]['allow_join'] = 0;
            }
            return getJson(1,'Community/lists','获取成功',0,['sum'=>$count,'pagecount'=>(ceil($count/$pagesize)),'lists'=>$lists]);
        }else{
            return getJson(0,'Community/lists','暂无数据',3);
        }

    }


    //加入社区的活
    public function join($data)
    {
        $validate=new \app\index\validate\Community();
        if(!$validate->scene('join')->check($data))
            return getJson(0,'Community/join',$validate->getError(),1);
       
        $member_id = $data['member_id']; //申请者的id
        $community_id = $data['community_id']+0; //申请社区的id
        $pass = md5('szwl'.$data['password'].'szwl');
        //实例化社区模型
        $Model = new CommunityModel();
        $info = $Model->where([
                'id'=>['=',$community_id],
                'password'=>['=',$pass],
            ])->find();
        if($info['member_id']==$data['member_id'])
            return getJson(0,'Community/join','您已是社区拥有者',2);//2:您已是社区拥有者
        if(!$info){
            return getJson(0,'Community/join','社区密码错误',3);//3:密码错误
        }

        if($info['allow_count'] <= $info['count']) {
            return getJson(0,'Community/join','社区人数已达上限',4);//4:社区人数已达上限
        }
        $member = new CommunityMemberModel();

        //判断是否已经加入该社区
        $exist = $member->where([
                'community_id'=>['=',$community_id],
                'member_id'=>['=',$member_id],
            ])->find();
        if($exist)
            return getJson(0,'Community/join','您已经加入该社区',5);//5:您已经加入该社区

        $res = $member->save([
                'community_id'=>$community_id,
                'member_id'=>$member_id,
                ]);
        if($res) return getJson(1,'Community/join','加入社区成功');

        return getJson(0,'Community/join','加入社区失败',6);//6:服务器插入失败
    }
    public function addpartner(){
        
    }
}