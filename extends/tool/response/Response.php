<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/10 下午6:11
 * Description: 返回类
 */
namespace Tool\Response;

use Tool\DataConversion\DataConversion;

class Response
{
    // 返回的code码
    const CODE_SUCCESS = 200; // 请求成功
    const CODE_ERROR = 444; // 请求失败

    // 返回的数据类型
    const DATA_TYPE_JSON = "JSON";
    const DATA_TYPE_XML = "XML";

    // 返回方式
    const RETURN_TYPE_RETURN = "RETURN";
    const RETURN_TYPE_DIE = "DIE";

    // 返回的提示
    const RETURN_MSG = ''; // 返回的提示

    /**
     * Description: 返回数据结果
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午11:23
     * @param int $code
     * @param string $msg
     * @param array $data
     * @param $msg_type
     * @param string $dataType
     * @param string $returnType
     * @return string
     */
    public static function returnResult(
        int $code, string $msg, $data = [], $msg_type,
        string $dataType = Response::DATA_TYPE_JSON, string $returnType = Response::RETURN_TYPE_DIE
    ) {
        $data = [
            'return_code'  => $code,
            'msg_type'  => $msg_type,
            'return_msg'   => $msg,
            'data'  => $data
        ];
        if ($dataType == Response::DATA_TYPE_JSON) {
            $returnData = DataConversion::arrayToJson($data);
        } elseif ($dataType == Response::DATA_TYPE_XML) {
            $returnData = DataConversion::arrayToXml($data);
        }
        if ($returnType == Response::RETURN_TYPE_DIE) {
            die($returnData);
        } elseif ($returnType == Response::RETURN_TYPE_RETURN) {
            return $returnData;
        }
    }
}
