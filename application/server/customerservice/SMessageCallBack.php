<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 下午12:55
 * Description:
 */
namespace App\Server\CustomerService;
use App\Common\Model\CustomerServiceModel;
use App\Consts;
use Tool\Log\Log;
use \GatewayWorker\Lib\Gateway;
use Tool\Request\Request;
use Tool\Response\Response;

class SMessageCallBack
{
    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:36
     * @var CustomerService
     * Description: 客服对象
     */
    protected $customerService;

    protected $request;

    /**
     * MessageCallBack constructor.
     * @param Request $request
     * @param string $clientId
     */
    public function __construct(Request $request)
    {
        $this->customerService = CustomerService::instance($request);
        $this->request = $request;
    }

    /**
     * Description: 省代码
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午3:11
     * @param Response $request
     * @param string $clientId
     * @return SMessageCallBack
     */
    public static function instance(Request $request): SMessageCallBack
    {
        return new SMessageCallBack($request);
    }

    /**
     * Description: 接收消息的主要处理方法
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午12:58
     */
    public function main()
    {
        switch ($this->request->msgType) {
            case Consts::CS_CONNECT: // 客服连接
                $this->connection();
                break;
            case Consts::CS_NEWS: // 客服发送给客服消息
                $this->sendNews();
                break;
        }
    }

    /**
     * Description: 发送消息给客户
     * User: 郭玉朝
     * CreateTime: 2018/5/8 上午11:18
     */
    protected function sendNews() {
        // 验证参数
        $checkResult = $this->request->checkParams(['msg_type', 'client_id', ['data' => ['news', 'news_type']]]);
        if ($checkResult !== true) {
            Log::instance([Consts::C_LOG_PATH_NAME, $checkResult])->error('转发消息给客服缺少参数');
            $this->customer->sendError("缺少参数:" . $checkResult, $this->data);
            return false;
        }
        // 数据转发给客服
        $sendNews = $this->request->data['data'];
        $client_id = $this->request->data['client_id'];
        Gateway::sendToClient($client_id,
            Response::returnResult(Response::CODE_SUCCESS, "新消息",  $sendNews
                , Consts::CS_NEWS, Response::DATA_TYPE_JSON, Response::RETURN_TYPE_RETURN) );
    }

    /**
     * Description: 客服连接
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午2:03
     */
    protected function connection()
    {
        // 检测连接参数
        if ($this->customerService->checkConnectionParams(
            ['msg_type', 'customer_service_name', 'customer_service_sign']) !== true) return false;

        // 查找客服是否存在
        $customerService = $this->customerService->findCustomerService();
        if (!$customerService) return false;

        // 更新client_id
        if (!$this->customerService->saveClientId()) return false;

        // 绑定客服uid
        Gateway::bindUid($this->request->clientId, $customerService['customer_service_id']);

        // 告诉客服已经登录成功
        $this->customerService->sendSuccess("登录成功");
    }
}

