<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class Msg
{	

	/*  //主帐号,对应开官网发者主账号下的 ACCOUNT SID
		$accountSid= '8a48b5514e00e2d3014e00e88899000c';
		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		$accountToken= 'ce00aa2b9cff405a9fdd25f0570f478c';
		//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
		//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
		$appId='8a48b5514e00e2d3014e00ec8b93000e';
		//请求地址
		//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
		//生产环境（用户应用上线使用）：app.cloopen.com
		$serverIP='app.cloopen.com';
		//请求端口，生产环境和沙盒环境一致
		$serverPort='8883';
		//REST版本号，在官网文档REST介绍中获得。
		$softVersion='2013-12-26';*/
		 
	protected function sendTemplateSMS($to,$datas,$tempId,$accountSid= '8a48b5514e00e2d3014e00e88899000c',$accountToken= 'ce00aa2b9cff405a9fdd25f0570f478c',$appId='aaf98f8951d304710151d32457120066',$serverIP='app.cloopen.com',$serverPort='8883',$softVersion='2013-12-26')
	{
	     // 初始化REST SDK
		 //发送短信
		 vendor('sendmsg/REST');
	     $rest = new \REST($serverIP,$serverPort,$softVersion);
	     $rest->setAccount($accountSid,$accountToken);
	     $rest->setAppId($appId);
	    
	     // 发送模板短信
	     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
	     if($result == NULL ) {
	     	return array('status'=>0,'code'=>1,'msg'=>'短信发送失败');
	     }
	     if($result->statusCode!=0) {
	     	return array('status'=>0,'code'=>2,'msg'=>$result->statusMsg);
	         //TODO 添加错误处理逻辑
	     }else{
	     	return array('status'=>1,'code'=>0,'msg'=>'发送成功');
	     }
	}

	public function sendmsg(Request $request)
    {	
        //获取手机号,
		$account= $request->param('account');
		if(!preg_match("/^1[34578]{1}\d{9}$/",$account)) 
			return sendJson(0,'请输入正确的手机号',1);

		//获取类型: 1 注册 2 改密
		//$type=$request->param('type')+0;
		//$userinfo = $this->findUser(array('account'=>$account));
		// $userinfo = Db::name('member')
		// 		    ->where('account', $account)
		// 		    ->find();
		// //dump($userinfo);die;
		// if($type===1) {	
		//  	if($userinfo) return sendJson('0','该手机号已注册');

		// } else if($type===2){
		// 	if(!$userinfo) return sendJson(0,'该手机号未注册');
		// }

		$ip=ip2long($request->ip())+0;
		$time=time();
		//获取当前ip下 短信总次数, 获取当前手机号总次数,获取上次发送验证码时间
		$start = strtotime(date('Y-m-d 0:00:00'));
		$end   = strtotime(date('Y-m-d 23:59:59'));
		$sum   = DB::name('send_msg')
					->where('ip',$ip)
					->where('addtime','between',[$start,$end])
					->sum('number');

		//dump($sum);exit($sum);
		//当前IP 限制一天50次发送次数
		if($sum > 50 ) return sendJson(0,'IP地址短信获取次数到达上限',3);

		//查找当前手机号记录
		$msgData = Db::name('send_msg')
				    ->where('account', '=', $account)
				    ->find();

		//限制60内不允许重复获取, 以及每日次数
		if($msgData){
			if(($time- $msgData['addtime'])<60 ) return sendJson(0,'60秒内获取频繁',4);
			if($msgData['number']>=6 && $msgData['addtime']>strtotime(date('Y-m-d 0:00:00'))) 
				return sendJson(0,'手机号超过当天次数上限',5) ;	
		}

		//生成随机6位数字
		$code=rand(000000,999999);
		//发送短信
		$result= $this->sendTemplateSMS($account,array($code,5),57956);
		//dump($result);die;
		if($result['status']==0) return sendJson(0,$result['msg'],6);
		//发送记录写入数据库
		if($msgData){
			//上次发送time小于今日零时 重置今日次数
			if($msgData['addtime']<strtotime(date('Y-m-d 0:00:00'))) $msgData['number']=0;
			$data = Db::name('send_msg')
				->where(['account'=>$account])
    			->update(['code'=>$code,'addtime'=>$time,'ip'=>$ip,'number'=>$msgData['number']+1]);
		}else{
			$data = Db::name('send_msg')
    			->insert(['account'=>$account,'code'=>$code,'addtime'=>$time,'ip'=>$ip,'number'=>1]);
		}
		if($data===false) return sendJson(0,'服务器繁忙',2);
		return sendJson(1,'发送成功');
    }

  //   //验证
  //   public function checkcode($account,$code)
  //   {
    	
		// if(!preg_match("/^1[34578]{1}\d{9}$/",$account)){
			
		// 	return sendJson(0,'请输入正确的手机号');
		// } 
			

		// if(!is_numeric($code))
		// 	return sendJson(0,'请输入正确的验证码');

		// //查找当前手机号记录
		// $msgData = Db::name('send_msg')
		// 		    ->where('account',$account)
		// 		    ->find();
		// if(!$msgData){
	 // 		return sendJson(0,'超时.请重新发送短信');	 	 
		// }else{
		//  	if(time() -  $msgData['addtime'] >300)	return sendJson(0,'验证码失效或过期');
		//  	if($msgData['code'] != $code) return sendJson(0,'验证码错误'); 
		// }
		// return sendJson(1);
		
  //   }

}