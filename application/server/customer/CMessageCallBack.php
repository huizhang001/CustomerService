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
use Tool\Log\Log;
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
        switch ($this->request->msgType) {
            case Consts::C_CONNECT:
                $this->connection();
                break;
            case Consts::C_NEWS:
                $this->sendNews();
                break;
        }
    }

    /**
     * Description: 转发消息给客服
     * User: 郭玉朝
     * CreateTime: 2018/5/6 下午5:26
     */
    protected function sendNews() {
        // 验证参数
        $checkResult = $this->request->checkParams(['msg_type', ['data' => ['news']]]);
        if ($checkResult !== true) {
            Log::instance([Consts::C_LOG_PATH_NAME, $checkResult])->error('转发消息给客服缺少参数');
            $this->customer->sendError("缺少参数:" . $checkResult, $this->data);
            return false;
        }
        // 数据转发给客服
        $sendNews = $this->data['data'];
        $sendNews['client_id'] = $this->request->clientId;
        Customer::sendUid(Gateway::getSession($this->request->clientId)['customer_service_uid'],
            "新消息", $sendNews, Consts::C_NEWS);
    }

    /**
     * Description: 顾客连接
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午3:30
     */
    protected function connection()
    {
        // 验证参数
        $checkResult = $this->request->checkParams(['msg_type']);
        if ($checkResult !== true) {
            $this->customer->sendError("缺少参数:" . $checkResult, $this->data);
            return false;
        }
        // 选择最佳的客服
        $optimumCusomerService = $this->customer->optimumCusomerService();
        if (empty($optimumCusomerService)) return false;
        // 更改客服的服务人数数量
        CustomerService::changeConnectNum($optimumCusomerService['customer_service_id'],
            Consts::CS_CONNECT_NUM_ADD);
        // 设置session
        Gateway::setSession($this->request->clientId,
            ['customer_service_uid' => $optimumCusomerService['customer_service_id']]);
        // 返回客服信息
        $this->customer->sendSuccess("返回客服信息成功", $optimumCusomerService);
        // 告诉客服有人已经连接
        Customer::sendUid($optimumCusomerService['customer_service_id'], "有新的连接",
            ['client_id' => $this->request->clientId], Consts::C_CONNECT);
    }
}
