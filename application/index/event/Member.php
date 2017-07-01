<?php
namespace app\index\event;
use think\Controller;
use think\Loader;
use app\index\model\Member as MemberModel;
use app\index\model\MemberLevel as MemberLevelModel;
class Member extends Controller
{
    //设置头像  和 角色名
    public function setinfo($data){
        //判断是否符合验证器规则 
        $validate=new \app\index\validate\Member();
        if(!$validate->scene('setinfo')->check($data))
            return getJson(0,'Member/setinfo',$validate->getError(),1);

        $MemberModel      =   new MemberModel();
        
        if($MemberModel->allowField(['face','name'])->save($data,['id' => $data['member_id']])){
            return getJson(1,'Member/setinfo','成功');
        }
        else{
            $Member   =   $MemberModel->field('name,face')->where('id',$data['member_id'])->find();
            if($Member['face']==$data['face'] && $Member['name']=$data['name'])
                return getJson(1,'Member/setinfo','成功'); 
        }
        return getJson(0,'Member/setinfo','服务器繁忙稍后重试',2); 
    }
    //验证token
    public function checktoken($data){
        //判断是否符合验证器规则 
        $validate=new \app\index\validate\Member();
        if(!$validate->scene('checktoken')->check($data))
            return getJson(0,'Member/init',$validate->getError(),1);
        $MemberModel      =   new MemberModel();
        $Member           =   $MemberModel->where('token',$data['token'])->find();
        return ($Member? $Member:false);
    }
    public function getinfo($data){
        $MemberModel      =   new MemberModel();
        $Member           =   $MemberModel->field('id,name,face,money,jyz,jifen')->where('id',$data['member_id'])->find();
        $MemberLevelModel      =   new MemberLevelModel();
        $MemberLevel           =   $MemberLevelModel->where([
                                                            'jyz_bottom'=>['<=',$Member['jyz']],
                                                            'jyz_top'=>['>=',$Member['jyz']]
                                                                ])->find();
        if($MemberLevel){
            $Member['level_name']=$MemberLevel['name'];
            $Member['jyz_bottom']=$MemberLevel['jyz_bottom'];
            $Member['jyz_top']=$MemberLevel['jyz_top'];
            return getJson(1,'Member/getinfo','',0,$Member);
        }
        else{
            return getJson(0,'Member/getinfo','没有数据',1);
        }
        
    }

}















