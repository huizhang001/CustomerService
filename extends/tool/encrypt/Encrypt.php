<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/27 下午7:56
 * Description: 加密解密类
 */
namespace Tool\Encrypt;
class Encrypt
{

    const ENCRYPT = "ENCRYPT"; // 加密
    const DECRYPT = "DECRYPT"; // 解密
    const KEY = "123456"; // 秘钥

    /**
     * Encrypt constructor. 非常给力的authcode加密函数，主要用于验证登录
     * $str = 'abc';
     * $key = 'www.tuzisir.com';
     * $token = authcodeEncrypt($str, 'E', $key);
     * echo '加密:'.authcodeEncrypt($str, 'E', $key);
     * echo '解密：'.authcodeEncrypt($str, 'D', $key);
     * @param $string
     * @param $operation
     * @param string $key
     */
    public static function authcodeEncrypt($string ,$operation = Encrypt::ENCRYPT ,$key=Encrypt::KEY){
        $key=md5($key);
        $key_length=strlen($key);
        $string=$operation==Encrypt::DECRYPT?base64_decode($string):substr(md5($string.$key),0,8).$string;
        $string_length=strlen($string);
        $rndkey=$box=array();
        $result='';
        for($i=0;$i<=255;$i++){
            $rndkey[$i]=ord($key[$i%$key_length]);
            $box[$i]=$i;
        }
        for($j=$i=0;$i<256;$i++){
            $j=($j+$box[$i]+$rndkey[$i])%256;
            $tmp=$box[$i];
            $box[$i]=$box[$j];
            $box[$j]=$tmp;
        }
        for($a=$j=$i=0;$i<$string_length;$i++){
            $a=($a+1)%256;
            $j=($j+$box[$a])%256;
            $tmp=$box[$a];
            $box[$a]=$box[$j];
            $box[$j]=$tmp;
            $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
        }
        if($operation==Encrypt::DECRYPT){
            if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
                return substr($result,8);
            }else{
                return'';
            }
        }else{
            return str_replace('=','',base64_encode($result));
        }
    }

}