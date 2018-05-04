<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 下午3:23
 * Description:
 */
namespace App\Server\Customer;

use App\Server\CustomerService\CustomerService;
use App\Server\Consts;
use \GatewayWorker\Lib\Gateway;
use Tool\Request\Request;
use Tool\Response\Response;

class CMessageCallBack
{
    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午12:58
     * @var array
     * Description: 传递过来的数据
     */
    protected $data;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午3:10
     * @var
     * Description: 请求对象
     */
    protected $request;

    protected $customer;

    /**
     * CMessageCallBack constructor.
     * @param Request $request
     * @param string $clientId
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data =  $request->getData();
        $this->customer = new Customer($request);
    }

    /**
     * Description: 省代码
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午3:11
     * @param Response $request
     * @param string $clientId
     * @return CMessageCallBack
     */
    public static function instance(Request $request): CMessageCallBack
    {
        return new CMessageCallBack($request);
    }

    /**
     * Description: 接收消息的主要处理方法
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午12:58
     */
    public function main()
    {
        // 顾客连接
        if ($this->request->msgType == Consts::MSG_TYPE_CONNECT) {
            $this->connection();
        }
    }

    /**
     * Description: 顾客连接
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午3:30
     */
    protected function connection()
    {
        // 选择最佳的客服
        $optimumCusomerService = $this->customer->optimumCusomerService();
        if (empty($optimumCusomerService)) return false;
        // 更改客服的服务人数数量
        CustomerService::changeConnectNum($optimumCusomerService['customer_service_id'],
            CustomerService::CONNECT_NUM_ADD);
        // 返回客服信息
        $this->customer->sendSuccess("返回客服信息成功", $optimumCusomerService);
        // 告诉客服有人已经连接
        Gateway::sendToUid($optimumCusomerService['customer_service_id'],
            Response::returnResult(Response::CODE_SUCCESS, "有新的连接", ['client_id' => $this->request->clientId]
                , Consts::CUSTOMER_CONNECT, Response::DATA_TYPE_JSON, Response::RETURN_TYPE_RETURN) );
    }
}
