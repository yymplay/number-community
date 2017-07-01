<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class Pay
{   
    public $privateKey= 'MIIEowIBAAKCAQEA+/TwUdP9yibqRk6LckROfvSCozwF7nqieWq3iq1csXitVrdXAXzPX5i3qfKtAsGUIfl8mKcBsdvyr0fJPWOmJrQL6Vd4HE7N3hXMLteFf48Y2Mu1pLKQ43pYh39aKTprnKspoPAN+3MP2fLq01xA+xJ5KXUx2hKMiSr4UdWHflWFeZ/Jcxvu/Cfqe1lAEOkGTwmJWoi7RoPIi7AKkBh5JZwXL+u7cnYWHiCApY8gJdHM8pdTCA778EKMqxRFtQ33q/BS5XlLZtXQtOJiyxYw4onXYQxSVfoTSSb1RM05sSdFcj+NctYyLsbEg+qbQCkrPpzfQCskQj+7byUcJ3WDNQIDAQABAoIBAQDTa6Bw+ZM/m1IAtw36T2kSzPgGI2rSx2MowSzMmFZssVe6qVO/8sxMamjLmdAlvOwhailsKs+YQHkvLeymr8SPO4u3POeT0WicT+wq7jyeYiQ2XxHH5vL/fv1kja/JYrVEHLhszKLe+Zk2iMkVnJcGV2UGLcosNF5rB3yhpqzwiwafb5mwU2WDv5KcDtzqbsOahO696T+tsioeRQ+I3ZDpo2yF/cMnynUQfv2Z9ZupaE4el8+AZYdFojdt/Zs+rAtfzpCXe8FJVwRFYvzkzK7Q+wPIN6JfbZkZDSlmq8UxFBn1Z4cmyj8ouQNNzK+fTTTaWFzSoHDCOd5UrSQEpoyhAoGBAP8nzUewT3Jw0PPJx0toS2ZkJNYoMKnKlmHZfJdVm1jQ9kHhVNBK9Bz2RlqLB6Npsv7SXlPiIRyEm3Nq7P868PHE+1AMVGrZVG/zYnSrNHXgC/cyhhswhPvHy1jtzH+YpBhyu45Udmv1mj8Fj/BSYfMKbp57nf2o9WryKZTKmwa9AoGBAPzKbTOA9fSI0iQ+8clXWjtpbBMojLXjzYYNE+c0+NLar67IpouRW6hRxXrFgb/NJPKP5lSOFUZ+a4M4HSPVCUMD+QuXIL/GhlZlc9W2x6/SAlyurHMxUZ4CAYuvPq2bUgbS2OiI+/a3TwjW0zqpMeHK51q+VeWrpIAd9t2gx1HZAoGAVDRSbqIk1eOdUZb5ik009Ol/rp080gvlbQ1VLKw3xCvR2RP/5W2W+ifcN+1B7A/p3maWcqBhxQOOOZzD8ccF1QPo3BX1lf16/CcaCt6lRXFXAZFO6JIukNjyprpzjnhVTywt8V62ZhJ7gOjZA7psUMTUlMC+9wNXWp/oUQ478xECgYA54afLaTXn/gQP5JG8RlIU1Hi4kkpVw4llrkzmXcyzivs7DBqGTowsKD0NdXfWEQTqvWRB7B6W+49mhky1zavHOnul2b7X6n8L/ULepnFmorGDw0MdRSepBFCSSVgSrEPF6DVeWS9IOITSQ04uWltflfQPZRGJ+xk5dKL38D5zUQKBgBachI7lxwMOEGCxgb/TP7WTaoplyyL7Y3pWBoYnz9OvRsmG7jBcU3kcOy9VdN5bOWUzx0BCIMUf0gYSeiCl8b1WJ/YW7aBGezS6fAfbvju+BzGtjyFfZkPaAPEdvbjwolFfNTJFbPzuolwMxup3r1r3rDJczRV+wGfYJsD2fLMy' ;
    public $publicKey= 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5J9AeacaB1jgc5felOFG0wZzSkoFI0W7J6M+dxpla1Wcjw1Hwmkbqm0ruTsuQiPK6yq+chkzHzuJziKOUloxhln53mY3ROAqmP4Tv5Y9f1sXemaJY7nSi51+riFXBqkXvSoL2SIl/4kp7Y7C1KhP825dIL4EWco7/ZXbaMrfcyBn5YmMBRjPjpdmH5WYWJgL46oGjfivkk3Gr0izAaLaTnVwD//vuv/eRv1p33pBa7Rt2Ky2R3TWHsuti8K8eSFgY+w2sOKI37DCBWd3crapz+21ccRqEYkISkv1mELaWyV4L6rK77McMVSsRc1PYRJ4dzObgsJP47X580eOcIWzjwIDAQAB';   
    

    public function orderInfo(){
         // 初始化
         vendor('alipay_app/AopClient');
         vendor('alipay_app/request/AlipayTradeAppPayRequest');
         $aop = new \AopClient($serverIP,$serverPort,$softVersion);
            $aop->gatewayUrl = "https://openapi.alipaydev.com/gateway.do";
            $aop->appId = "2016080200148122";
            $aop->rsaPrivateKey = $this->privateKey;
            $aop->format = "json";
            $aop->charset = "UTF-8";
            $aop->signType = "RSA2";
            $aop->alipayrsaPublicKey =$this->publicKey; 
            //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
            $request = new \AlipayTradeAppPayRequest();
            //SDK已经封装掉了公共参数，这里只需要传入业务参数
            $bizcontent = json_encode([
                                'body'=>'商品信息',
                                'subject'=>'衣服',
                                'out_trade_no'=>'123456',//此订单号为商户唯一订单号
                                'total_amount'=> '9.88',//保留两位小数
                                'product_code'=>'QUICK_MSECURITY_PAY'
            ]);
            $request->setNotifyUrl("https://szwl.izhiwo.com/index.php?s=index/pay/notify");
            $request->setBizContent($bizcontent);
            //这里和普通的接口调用不同，使用的是sdkExecute
            $response = $aop->sdkExecute($request);
            //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
            return $response;//就是orderString 可以直接给客户端请求，无需再做处理。

    }
    public function notify(){
        vendor('alipay_app/AopClient');
        $aop = new \AopClient;
        $aop->alipayrsaPublicKey = '请填写支付宝公钥，一行字符串';
        //此处验签方式必须与下单时的签名方式一致
        $flag = $aop->rsaCheckV1($_GET, NULL, "RSA");
        //验签通过后再实现业务逻辑，比如修改订单表中的支付状态。
        /**验签通过后核实如下参数out_trade_no、total_amount、seller_id
        修改订单表
        **/
        //打印success，应答支付宝。必须保证本界面无错误。只打印了success，否则支付宝将重复请求回调地址。
        echo 'success';
    }
    public function pay2(){
       vendor('alipay_app/AopClient');
       vendor('alipay_app/request/AlipayTradeAppPayRequest');
        //构造业务请求参数的集合(订单信息)
       $content = array();
       $content['body'] = 'ceshi';
       $content['subject'] = 'funbutton';//商品的标题/交易标题/订单标题/订单关键字等
       $content['out_trade_no'] = '';//商户网站唯一订单号
       $content['timeout_express'] = '1d';//该笔订单允许的最晚付款时间
       $content['total_amount'] = floatval(100);//订单总金额(必须定义成浮点型)
       $content['seller_id'] = '';//收款人账号
       $content['product_code'] = 'QUICK_MSECURITY_PAY';//销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
       $content['store_id'] = 'BJ_001';//商户门店编号
       $con = json_encode($content);//$content是biz_content的值,将之转化成字符串

       //公共参数
       $param = array();
       $Client = new \AopClient();//实例化支付宝sdk里面的AopClient类,下单时需要的操作,都在这个类里面
       $param['app_id'] = '2016080200148122';//支付宝分配给开发者的应用ID
       $param['method'] = 'alipay.apppay';//接口名称
       $param['charset'] = 'utf-8';//请求使用的编码格式
       $param['sign_type'] = 'RSA2';//商户生成签名字符串所使用的签名算法类型
       $param['timestamp'] = date("Y-m-d H:i:s");//发送请求的时间
       $param['version'] = '1.0';//调用的接口版本，固定为：1.0
       $param['notify_url'] = 'https://szwl.izhiwo.com/index.php?s=index/pay/notify';//支付宝服务器主动通知地址
       $param['biz_content'] = $con;//业务请求参数的集合,长度不限,json格式

       //生成签名
       $paramStr = $Client->getSignContent($param);
       echo 
       $sign = $Client->alonersaSign($paramStr,'MIIEowIBAAKCAQEA+/TwUdP9yibqRk6LckROfvSCozwF7nqieWq3iq1csXitVrdXAXzPX5i3qfKtAsGUIfl8mKcBsdvyr0fJPWOmJrQL6Vd4HE7N3hXMLteFf48Y2Mu1pLKQ43pYh39aKTprnKspoPAN+3MP2fLq01xA+xJ5KXUx2hKMiSr4UdWHflWFeZ/Jcxvu/Cfqe1lAEOkGTwmJWoi7RoPIi7AKkBh5JZwXL+u7cnYWHiCApY8gJdHM8pdTCA778EKMqxRFtQ33q/BS5XlLZtXQtOJiyxYw4onXYQxSVfoTSSb1RM05sSdFcj+NctYyLsbEg+qbQCkrPpzfQCskQj+7byUcJ3WDNQIDAQABAoIBAQDTa6Bw+ZM/m1IAtw36T2kSzPgGI2rSx2MowSzMmFZssVe6qVO/8sxMamjLmdAlvOwhailsKs+YQHkvLeymr8SPO4u3POeT0WicT+wq7jyeYiQ2XxHH5vL/fv1kja/JYrVEHLhszKLe+Zk2iMkVnJcGV2UGLcosNF5rB3yhpqzwiwafb5mwU2WDv5KcDtzqbsOahO696T+tsioeRQ+I3ZDpo2yF/cMnynUQfv2Z9ZupaE4el8+AZYdFojdt/Zs+rAtfzpCXe8FJVwRFYvzkzK7Q+wPIN6JfbZkZDSlmq8UxFBn1Z4cmyj8ouQNNzK+fTTTaWFzSoHDCOd5UrSQEpoyhAoGBAP8nzUewT3Jw0PPJx0toS2ZkJNYoMKnKlmHZfJdVm1jQ9kHhVNBK9Bz2RlqLB6Npsv7SXlPiIRyEm3Nq7P868PHE+1AMVGrZVG/zYnSrNHXgC/cyhhswhPvHy1jtzH+YpBhyu45Udmv1mj8Fj/BSYfMKbp57nf2o9WryKZTKmwa9AoGBAPzKbTOA9fSI0iQ+8clXWjtpbBMojLXjzYYNE+c0+NLar67IpouRW6hRxXrFgb/NJPKP5lSOFUZ+a4M4HSPVCUMD+QuXIL/GhlZlc9W2x6/SAlyurHMxUZ4CAYuvPq2bUgbS2OiI+/a3TwjW0zqpMeHK51q+VeWrpIAd9t2gx1HZAoGAVDRSbqIk1eOdUZb5ik009Ol/rp080gvlbQ1VLKw3xCvR2RP/5W2W+ifcN+1B7A/p3maWcqBhxQOOOZzD8ccF1QPo3BX1lf16/CcaCt6lRXFXAZFO6JIukNjyprpzjnhVTywt8V62ZhJ7gOjZA7psUMTUlMC+9wNXWp/oUQ478xECgYA54afLaTXn/gQP5JG8RlIU1Hi4kkpVw4llrkzmXcyzivs7DBqGTowsKD0NdXfWEQTqvWRB7B6W+49mhky1zavHOnul2b7X6n8L/ULepnFmorGDw0MdRSepBFCSSVgSrEPF6DVeWS9IOITSQ04uWltflfQPZRGJ+xk5dKL38D5zUQKBgBachI7lxwMOEGCxgb/TP7WTaoplyyL7Y3pWBoYnz9OvRsmG7jBcU3kcOy9VdN5bOWUzx0BCIMUf0gYSeiCl8b1WJ/YW7aBGezS6fAfbvju+BzGtjyFfZkPaAPEdvbjwolFfNTJFbPzuolwMxup3r1r3rDJczRV+wGfYJsD2fLMy','RSA2');
       echo $sign;
    }


}