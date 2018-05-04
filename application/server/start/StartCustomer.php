<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 下午12:52
 * Description:
 */
use \Workerman\Worker;
use \GatewayWorker\Gateway;

// ##########新增端口支持Text协议 开始##########
// 新增8283端口，开启Text文本协议
$gateway_text = new Gateway("websocket://0.0.0.0:8283");
// 进程名称，主要是status时方便识别
$gateway_text->name = 'tuzisir-customer';
// 开启多少text协议的gateway进程
$gateway_text->count = 4;
// 本机ip（分布式部署时需要设置成内网ip）
$gateway_text->lanIp = '127.0.0.1';
// 设置服务注册地址(注意：这个地址是start_register.php中监听的地址)
$gateway_text->registerAddress = '0.0.0.0:1238';
// gateway内部通讯起始端口，起始端口不要重复
$gateway_text->startPort = 2500;
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}
