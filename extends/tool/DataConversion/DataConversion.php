<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/27 下午7:21
 * Description:
 */
namespace Tool\DataConversion;

class DataConversion
{

    /**
     * Description: 数组转json
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午7:22
     * @param array $data
     * @return string
     */
    public static function arrayToJson(array $data) {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @return string
     */
    public static function arrayToXml(array $data) {
        $xml = '';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  ( is_array($val) || is_object($val)) ? self::arrayToXml($val) : $val;
            list($key, ) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        return $xml;
    }

}
