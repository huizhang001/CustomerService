<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 下午2:08
 * Description:
 */
namespace App\Server\Customer;
use App\Common\Model\CustomerServiceModel;
use App\Server\CustomerService\CustomerService;
use GatewayWorker\Lib\Gateway;
use Tool\Log\Log;
class CCloseCallBack
{

    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午2:10
     * @var string
     * Description: 要关闭的连接
     */
    protected $clientId;

    /**
     * CCloseCallBack constructor.
     * @param string $clientId
     */
    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * Description: 省代码
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午2:09
     * @return CCloseCallBack
     */
    public static function instance(string $clientId)
    {
        return new CCloseCallBack($clientId);
    }

    /**
     * Description: 关闭连接
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午2:10
     */
    public function closeConnection()
    {
        // Gateway::onClose回调里无法使用Gateway::getSession来获得当前用户的session数据，但是仍然可以使用$_SESSION变量获得。
        if (isset($_SESSION[$this->clientId])) {
            $customerServiceId = $_SESSION[$this->clientId]['customer_service_id'];
            CustomerService::changeConnectNum($customerServiceId, CustomerService::CONNECT_NUM_REDUCE);
        } else {
            Log::instance([Customer::LOG_PATH_NAME, $this->clientId])->error('减少客服服务数量失败');
        }
    }
}
