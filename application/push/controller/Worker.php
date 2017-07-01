<?php

namespace app\push\controller;

use think\worker\Server;

class worker extends Server
{
    protected $socket = 'tcp://0.0.0.0:2346';
    private static $member=[];
    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    /*************/
    public function onMessage($connection, $message)
    {
        $request_data=[];
        echo $message.'\r\n';
        $message = json_decode($message, true);
        $ctrl = isset($message['ctrl']) ? $message['ctrl'] : '';
        $action = isset($message['action']) ? $message['action'] : '';
        $request_data  = isset($message['param']) ? $message['param'] : [];
        //dump($request_data);
        /******如果访问的不是初始化操作 而且也没验证过就断开*******/
        if($action!=='init' && !self::$member){
            $connection->close(getJson(0,$action,'需先验证身份'));
            return;
        }

        /******根据action判断所要执行的逻辑*******/
        switch($action) {
           case 'init':
                /***** 验证token ******/
                $rst=action('index/Member/checktoken',['data'=>$request_data],'event');
                if($rst){
                    /******* 通过验证 *********/
                    self::$member['member_id']=$rst['id'];
                    self::$member['member_name']=$rst['name'];
                    $connection->send(getJson(1,'init','验证成功'));                   
                }else{
                        $connection->close(getJson(0,'init','没有权限',2));   
                } 
                return;
           default :
                $request_data['member_id']=self::$member['member_id'];
                $request_data['member_name']=self::$member['member_name'];
                $rst=action('index/'.$action,['data'=>$request_data],'event');
                $connection->send($rst);
        }
        /*传输数据 
            发起连接:    
{"action":"init","param":{"token":"232389f6f780b72097ad1773f83e0d7c"}}
            设置头像名字:
{"action":"Member/setinfo","param":{"face":"2","name":"xxxxxx"}}
{"action":"Member/getinfo","param":""}
{"action":"Community/add","param":{"password":"123456","name":"明哥的社区"}}
        */
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {

    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {

    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onworkerStart($worker)
    {

    }
}