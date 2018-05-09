<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/30 下午2:08
 * Description:
 */
namespace App\Server\CustomerService;
use App\Common\Model\CustomerServiceModel;
use App\Consts;
use GatewayWorker\Gateway;
use Tool\Log\Log;
class SCloseCallBack
{

    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午2:10
     * @var string
     * Description: 要关闭的连接
     */
    protected $clientId;

    /**
     * SCloseCallBack constructor.
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
     * @return SCloseCallBack
     */
    public static function instance(string $clientId)
    {
        return new SCloseCallBack($clientId);
    }

    /**
     * Description: 关闭连接
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午2:10
     */
    public function closeConnection()
    {
        $condition = ['client_id' => $this->clientId];
        $data = ['client_id' => null];
        if (!CustomerServiceModel::instance()->editInfo($condition, $data)) {
            Log::instance([Consts::CS_LOG_PATH_NAME, $this->clientId])->error('清除数据库连接失败');
        }
    }
}
