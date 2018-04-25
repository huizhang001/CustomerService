<?php
class Events
{

    public static function onConnect($client_id) {

    }

    /**
     * Description: 接收消息
     * User: 郭玉朝
     * CreateTime: 2018/4/14 下午2:55
     * @param $client_id
     * @param $message
     */
    public static function onMessage($client_id, $message) {

    }

    /**
     * Description: 当连接关闭的时候
     * User: 郭玉朝
     * CreateTime: 2018/4/14 下午8:27
     * @param $client_id
     */
    public static function onClose($client_id) {

    }
}
