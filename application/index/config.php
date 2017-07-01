<?php
//配置文件
return [
	'alipay_config' =>[  
       'appid' =>'2017010604888586',//商户密钥
       'rsaPrivateKey' =>'',//私钥
       'alipayrsaPublicKey'=>'',
       'partner'=>'2088421540577515',//(商家的参数,新版本的好像用不到)
       'input_charset'=>strtolower('utf-8'),//编码
       'notify_url' =>'www.test.com/api/notify.php',//回调地址(支付宝支付成功后回调修改订单状态的地址)
       'payment_type' =>1,//(固定值)
       'seller_id' =>'',//收款商家账号
       'charset'    => 'utf-8',//编码
       'sign_type' => 'RSA2',//签名方式
       'timestamp' =>date("Y-m-d Hi:i:s"),
       'version'   =>"1.0",//固定值
       'url'       => 'https://openapi.alipay.com/gateway.do',//固定值
       'method'    => 'alipay.trade.app.pay',//固定值
     ]
];