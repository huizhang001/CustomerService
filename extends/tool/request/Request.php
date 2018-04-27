<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/27 下午4:36
 * Description:
 */
namespace Tool\Request;
use Tool\Encrypt\Encrypt;
use Tool\Response\Response;

class Request
{

    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午4:41
     * @var array
     * Description: 请求的数据
     */
    public $params;

    public function __construct(string $params)
    {
        $this->params = json_decode(Encrypt::authcodeEncrypt($params,Encrypt::DECRYPT), true);
    }

    /**
     * Description: 返回当前对象
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午4:38
     * @return null|Request
     */
    public static function instance(string $params):Request
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
        $result = array_diff_key(array_flip($oneFirst), $this->params);
        if (!empty($result)) {
            return json_encode(array_keys($result), JSON_UNESCAPED_UNICODE);
        }
        // 验证二维数组
        foreach ($twoFirst as $key => $value) {
            foreach ($value as $checkKey => $checkValue) {
                if (!isset($this->params[$checkKey])) return json_encode([$checkKey], JSON_UNESCAPED_UNICODE);
                $result = array_diff_key(array_flip($checkValue), $this->params[$checkKey]);
                if (!empty($result)) {
                    return json_encode(array_keys($result, JSON_UNESCAPED_UNICODE));
                }
            }
        }
        return true;
    }


}
