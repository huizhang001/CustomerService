<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 上午10:09
 * Description:
 */
namespace App\Server;

class Consts
{
    const MSG_TYPE_CONNECT = "CONNECT"; // 连接

    const MSG_TYPE_CLOSE = "CLOSE"; // 关闭连接

    const CUSTOMER_CONNECT = "CUSTOMER_CONNECT"; // 用户连接

    const CUSTOMER_NEWS = "CUSTOMER_NEWS"; // 用户发送消息

}