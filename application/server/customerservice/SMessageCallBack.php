<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 下午12:55
 * Description:
 */
namespace App\Server\CustomerService;
use App\Common\Model\CustomerServiceModel;
use App\Server\Consts;
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
        // 客服连接
        if ($this->request->msgType == Consts::MSG_TYPE_CONNECT) {
            $this->connection();
        }
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

        // 更新uid
        if (!$this->customerService->saveClientId()) return false;

        // 绑定客服uid
        Gateway::bindUid($this->request->clientId, $customerService['customer_service_id']);

        // 告诉客服已经登录成功
        $this->customerService->sendSuccess("登录成功");
    }
}

