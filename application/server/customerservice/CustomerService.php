<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/5/4 上午8:25
 * Description:
 */
namespace App\Server\CustomerService;

use App\Common\Model\CustomerServiceModel;
use App\Server\Consts;
use GatewayWorker\Lib\Gateway;
use think\exception\DbException;
use Tool\Log\Log;
use Tool\Request\Request;
use Tool\Response\Response;

class CustomerService
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
     * CustomerService constructor.
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
     * @return CustomerService
     */
    public static function instance(Request $request) {
        return new CustomerService($request);
    }

    /**
     * Description: 查找客服是否存在
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午8:51
     * @param array $condition
     * @return array|bool|false|\PDOStatement|string|\think\Model
     */
    public function findCustomerService() {
        $condition = [
            'customer_service_name' => $this->data['customer_service_name'],
            'customer_service_sign' => $this->data['customer_service_sign'],
            'customer_service_status' => 1,
        ];
        $customerServiceInfo = CustomerServiceModel::instance()->findInfo($condition);
        if (!$customerServiceInfo) {
            Log::instance([Consts::CS_LOG_PATH_NAME, $this->data])->error('没有找到此客服');
            $this->sendError("没有找到此客服", $this->data);
            Gateway::closeClient($this->clientId);
        }
        if ($customerServiceInfo['client_id'] != '') {
            Log::instance([Consts::CS_LOG_PATH_NAME, $this->data])->error('不能重复登录');
            $this->sendError("不能重复登录", $this->data);
            Gateway::closeClient($this->clientId);
        }
        return $customerServiceInfo;
    }

    /**
     * Description: 保存客服连接
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午10:17
     * @return false|int
     */
    public function saveClientId() {
        $condition = ['customer_service_name' => $this->data['customer_service_name']];
        $data = ['client_id' => $this->clientId];
        $saveResult = CustomerServiceModel::instance()->editInfo($condition, $data);
        if (!$saveResult) {
            Log::instance([Consts::CS_LOG_PATH_NAME, $this->data])->error('保存连接到库失败');
            $this->sendError("保存连接到库失败", $this->data);
            Gateway::closeClient($this->clientId);
        }
        return $saveResult;
    }

    /**
     * Description: 检测客服连接的必要参数
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:03
     */
    public function checkConnectionParams(array $params) {
        $checkParams = $this->request->checkParams($params);
        if ($checkParams !== true) {
            Log::instance([Consts::CS_LOG_PATH_NAME, $this->data])->error('缺少必要参数:'.$checkParams);
            $this->sendError('缺少必要参数:'.$checkParams, $this->data);
            Gateway::closeClient($this->clientId);
        }
        return true;
    }

    /**
     * Description: 返回客服成功的消息
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
     * Description: 返回客服失败的消息
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
     * Description: 初始化客服连接
     * User: 郭玉朝
     * CreateTime: 2018/5/4 下午12:52
     */
    public static function setAllClientIdNull() {
        CustomerServiceModel::instance()->setAllClientIdNull();
        Log::instance([Consts::CS_LOG_PATH_NAME, []])->trace('初始化客服连接完成');
    }

    /**
     * Description: 更改客服的服务连接数量
     * User: 郭玉朝
     * CreateTime: 2018/5/4 下午5:44
     * @param array $condition
     */
    public static function changeConnectNum(string $id, string $changeType = Consts::CS_CONNECT_NUM_ADD) {
        try {
            if ($changeType == Consts::CS_CONNECT_NUM_ADD) {
                $changeType = 'inc';
            } else {
                $changeType = 'dec';
            }
            if (CustomerServiceModel::instance()->changeConnectNum($id, $changeType)) {
                return true;
            }
        } catch (DbException $e) {
            Log::instance([Consts::CS_LOG_PATH_NAME, []])->trace('更改客服连接数量失败');
        }
        return  false;
    }
}
