<?php
use \Workerman\Worker;
use \GatewayWorker\Gateway;
// gateway 进程，这里使用Text协议，可以用telnet测试
$gateway = new Gateway("websocket://0.0.0.0:8282");
// gateway名称，status方便查看
$gateway->name = 'tuzisir';
// gateway进程数
$gateway->count = 4;
// 本机ip，分布式部署时使用内网ip
$gateway->lanIp = '127.0.0.1';
// 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
// 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口 
$gateway->startPort = 2900;
// 服务注册地址
$gateway->registerAddress = '0.0.0.0:1238';
// 心跳间隔
$gateway->pingInterval = 20;
// 心跳数据
$gateway->pingData = '{"cmd":"PING"}';
// 客户端连续limit*pingInterval时间内不回应心跳则断开链接。
$gateway->pingNotResponseLimit = 0;
// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START')) {
    Worker::runAll();
}

