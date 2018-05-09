<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 上午10:09
 * Description:
 */
namespace App;

class Consts
{

//-----------------------------------------------------------------------------客服
    //==========================================(客服添加图片的Api)
    const CS_ADD_IMG = 'CS_ADD_IMG';


    //==========================================(客服的msg_type)

    const CS_CONNECT = "CS_CONNECT"; // 连接

    const CS_CLOSE = "CS_CLOSE"; // 关闭连接

    const CS_NEWS = "CS_NEWS"; // 客服发送消息

    //===========================================(普通consts)

    const CS_CONNECT_NUM_ADD = "CS_CONNECT_NUM_ADD"; // 增加客服服务客户的连接数量

    const CS_CONNECT_NUM_REDUCE = 'CS_CONNECT_NUM_REDUCE'; // 减少客服服务客户的连接数量

//-----------------------------------------------------------------------------客服(客户的msg_type)
    //==========================================(客户的msg_type)

    const C_CONNECT = "C_CONNECT"; // 客户连接

    const C_CLOSE = "C_CLOSE"; // 客户关闭连接

    const C_NEWS = "C_NEWS"; // 客户发送消息

//-----------------------------------------------------------------------------日志
    const C_LOG_PATH_NAME = 'customer'; // 客户日志目录名称

    const CS_LOG_PATH_NAME = 'customer_service';
}