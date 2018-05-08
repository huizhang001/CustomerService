<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/5/4 上午8:25
 * Description:
 */
namespace App\Server\Customer;

use App\Common\Model\CustomerServiceModel;
use App\Server\Consts;
use GatewayWorker\Lib\Gateway;
use Tool\Log\Log;
use Tool\Request\Request;
use Tool\Response\Response;

class Customer
{

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:37
     * @var null
     * Description: 当前对象
     */
    protected $myself = null;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:37
     * @var Request
     * Description: 请求对象
     */
    protected $request;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:37
     * @var mixed
     * Description: 客服连接id
     */
    protected $clientId;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:37
     * @var array|mixed
     * Description: 客服发送的数据
     */
    protected $data;

    /**
     * Customer constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data =  $request->getData();
        $this->clientId = $request->clientId;
    }

    /**
     * Description: 返回当前对象
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午8:56
     * @return Customer
     */
    public static function instance(Request $request) {
        return new Customer($request);
    }

    /**
     * Description: 查找最优客服
     * User: 郭玉朝
     * CreateTime: 2018/5/4 下午12:10
     * @return array
     */
    public function optimumCusomerService() {
        $connectNum = 0;
        $first = 0;
        $optimumCusomerService = [];
        // 查询所有在线客服
        $onlines = CustomerServiceModel::instance()->getAllOnline()->toArray();
        // 找出最优客服
        foreach ($onlines as $key => $value) {
            if ($value['connect_num'] <= $connectNum || $first == 0) {
                $connectNum = $value['connect_num'];
                $optimumCusomerService = $value;
            }
            $first++;
        }
        if (empty($optimumCusomerService)) {
            Log::instance([Consts::C_LOG_PATH_NAME, $this->data])->error('没有客服在线');
            Gateway::sendToClient($this->clientId,
                Response::returnResult(Response::CODE_ERROR, "没有客服在线", []
                    , Consts::CS_CONNECT, Response::DATA_TYPE_JSON,Response::RETURN_TYPE_RETURN));
            Gateway::closeClient($this->clientId);
        } else {
            $_SESSION[$this->clientId] = ['customer_service_id' => $optimumCusomerService['customer_service_id']];
        }
        return $optimumCusomerService;
    }

    /**
     * Description: 返回用户成功的消息
     * User: 郭玉朝
     * CreateTime: 2018/5/4 下午12:27
     * @param string $code
     * @param string $msg
     * @param array $data
     */
    public function sendSuccess(string $msg = "成功", array $data = []) {
        Gateway::sendToClient($this->clientId,
            Response::returnResult(Response::CODE_SUCCESS, $msg, $data
                , $this->request->msgType, Response::DATA_TYPE_JSON,Response::RETURN_TYPE_RETURN));
    }

    /**
     * Description: 返回用户失败的消息
     * User: 郭玉朝
     * CreateTime: 2018/5/4 下午12:35
     * @param string $msgType
     * @param string $msg
     * @param array $data
     */
    public function sendError(string $msg = "失败", array $data = []) {
        Gateway::sendToClient($this->clientId,
            Response::returnResult(Response::CODE_ERROR, $msg, $data
                , $this->request->msgType, Response::DATA_TYPE_JSON,Response::RETURN_TYPE_RETURN));
    }


    /**
     * Description: 发送数据给客服
     * User: 郭玉朝
     * CreateTime: 2018/5/8 下午12:57
     */
    public static function sendUid($uid, $msg, $data, $msgType) {
        Gateway::sendToUid($uid,
            Response::returnResult(Response::CODE_SUCCESS, $msg, $data
                , $msgType, Response::DATA_TYPE_JSON, Response::RETURN_TYPE_RETURN));
    }

}