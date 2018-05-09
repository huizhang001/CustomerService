<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/27 下午4:36
 * Description:
 */
namespace Tool\Request;
use App\Consts;
use Tool\Encrypt\Encrypt;
use Tool\Log\Log;
use Tool\Response\Response;

class Request
{

    public $params;
    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午4:41
     * @var array
     * Description: 请求的数据
     */
    public $data;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:42
     * @var mixed
     * Description: 连接id
     */
    public $clientId;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:52
     * @var
     * Description: 消息类型
     */
    public $msgType;

    public function __construct(array $params)
    {
        $this->params = $params;
        if (isset($params[2]) && $params[2] == false) {
            $this->data = json_decode($params[0], true);
        } else {
            $this->data = json_decode(Encrypt::authcodeEncrypt($params[0],Encrypt::DECRYPT), true);
        }
        $this->init();
    }

    /**
     * Description: 初始化参数
     * User: 郭玉朝
     * CreateTime: 2018/5/4 上午9:45
     */
    protected function init() {
        $this->clientId = $this->params[1];
        $this->msgType = $this->data['msg_type'];
    }

    /**
     * Description: 返回当前对象
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午4:38
     * @return null|Request
     */
    public static function instance(array $params):Request
    {
        return new Request($params);
    }

    /**
     * Description: 验证必要参数
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午4:47
     * @param array $checkCondition
     * @return array|true
     */
    public function checkParams(array $checkCondition = [])
    {
        $oneFirst = []; $twoFirst = [];
        foreach ($checkCondition as $value) {
            is_array($value)?array_push($twoFirst, $value):array_push($oneFirst, $value);
        }
        // 验证一维数组
        $result = array_diff_key(array_flip($oneFirst), $this->data);
        if (!empty($result)) {
            return json_encode(array_keys($result), JSON_UNESCAPED_UNICODE);
        }
        // 验证二维数组
        foreach ($twoFirst as $key => $value) {
            foreach ($value as $checkKey => $checkValue) {
                if (!isset($this->data[$checkKey])) return json_encode([$checkKey], JSON_UNESCAPED_UNICODE);
                $result = array_diff_key(array_flip($checkValue), $this->data[$checkKey]);
                if (!empty($result)) {
                    return json_encode(array_keys($result, JSON_UNESCAPED_UNICODE));
                }
            }
        }
        return true;
    }

    /**
     * Description: 获取解密后的数据
     * User: 郭玉朝
     * CreateTime: 2018/4/30 上午10:05
     * @return array|mixed
     */
    public function getData()
    {
        return $this->data;
    }


}
