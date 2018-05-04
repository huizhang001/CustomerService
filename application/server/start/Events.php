<?php
use App\Server\Customer\CMessageCallBack;
use Tool\Request\Request;
use App\Server\CustomerService\SMessageCallBack;
use App\Server\CustomerService\SCloseCallBack;
use App\Server\Customer\CCloseCallBack;
use App\Server\CustomerService\CustomerService;
use Tool\Log\Log;
class Events
{

    public static function onWorkerStart($businessWorker) {
        if ($businessWorker->id == 0) {
            CustomerService::setAllClientIdNull();
        }
    }
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
        if ($_SERVER['GATEWAY_PORT'] == 8282) { // 客服
            SMessageCallBack::instance(Request::instance([$message, $client_id, false]))->main();
        } elseif ($_SERVER['GATEWAY_PORT'] == 8283) { // 客户
            CMessageCallBack::instance(Request::instance([$message, $client_id, false]))->main();
        }
    }

    /**
     * Description: 当连接关闭的时候
     * User: 郭玉朝x
     * CreateTime: 2018/4/14 下午8:27
     * @param $client_id
     */
    public static function onClose($client_id) {
        if ($_SERVER['GATEWAY_PORT'] == 8282) { // 客服
            SCloseCallBack::instance($client_id)->closeConnection();
        } elseif ($_SERVER['GATEWAY_PORT'] == 8283) { // 客户
            CCloseCallBack::instance($client_id)->closeConnection();
        }
    }
}
