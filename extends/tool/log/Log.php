<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/25 下午6:40
 * Description: 日志记录等级 分为三级 错误日志（error） 调试日志（debug） 跟踪日志（trace） 根据需要选择
 */
namespace Tool\Log;
use Tool\Log\Config;

class Log {

    protected $logType;
    protected $path = Config::LOG_PATH;  //定义log日志文件的存放路径
    protected static $myself = null;
    protected $modular = '';
    protected static $params = [''];
    protected $logData;

    /**
     * Log constructor.
     */
    public function __construct()
    {
        umask(0); // 777
        date_default_timezone_set( Config::TIME_ZONE ); // 设置时区
        $this->init();
    }

    /**
     * Description: 单例模式
     * User: 郭玉朝
     * CreateTime: 2018/4/25 下午9:39
     * @return Log
     */
    public static function instance(array $params  = [''])
    {
        if (Log::$myself == null || $params[0] != Log::$params[0]) {
            Log::$params = $params;
            Log::$myself = new Log();
            return Log::$myself;
        }
        Log::$params = $params;
        return Log::$myself;
    }

    /**
     * Description: 初始化必要参数
     * User: 郭玉朝
     * CreateTime: 2018/4/25 下午10:44
     * @param $params
     */
    public function init() {
        if (isset(Log::$params[0])) {
            $this->path = $this->path . "/".Log::$params[0];
            $this->isExistencePath();
        }
        if (isset(Log::$params[1])) {
            $this->try_covert_to_string(Log::$params[1]);
            $this->logData = Log::$params[1];
        }
    }

    /**
     * Description: 检查目录是否存在不存在自动创建
     * User: 郭玉朝
     * CreateTime: 2018/4/25 下午10:44
     */
    function isExistencePath() {
        if (!is_dir($this->path)) mkdir($this->path, 0777,true); // 如果不存在则创建
    }

    function logging( $string ) {
        $ip = '127.0.0.1';
        $file = $this->path . '/' . date("Ymd") . '.log';
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $msg = "\r\n==================================请求时间:".date("Y-m-d H:i:s")."=======================================================\r\n".
            "IP地址: ". $ip . "\r\n".
            "日志类型:". $this->logType . "\r\n".
            "日志提示:". $string . "\r\n".
            "日志数据:". $this->logData . "\r\n".
            "请求过程:". $this->getCallFunctions() . "\r\n";
        @error_log($msg, 3, $file);
    }

    /**
     * Description: 获取调用函数的过程
     * User: 郭玉朝
     * CreateTime: 2018/4/30 下午1:35
     * @param int $function_num
     * @return string
     */
    private function getCallFunctions($function_num = 3)
    {
        $debugInfo = debug_backtrace();
        $functionNums = [0, 3];
        if (is_int($function_num)) {
            $functionNums[1] = $function_num;
        } elseif (is_array($function_num) && count($function_num) > 1) {
            $functionNums[0] += $function_num[0];
            $functionNums[1] = $function_num[1];
        }
        if (count($debugInfo) < $functionNums[0]) {
            return '';
        }
        $stack = "[\r\n";
        $k = 1;
        for ($i = $functionNums[0]; $i < count($debugInfo); $i++) {
            if ($functionNums[1] <= 0) {
                break;
            }
            $stack .= "   (" . ($k++) . ") { file:" . (isset($debugInfo[$i]["file"]) ? $debugInfo[$i]["file"] : 'null');
            $stack .= ",line:" . (isset($debugInfo[$i]["line"]) ? $debugInfo[$i]["line"] : 'null');
            $stack .= ",function:" . (isset($debugInfo[$i]["function"]) ? $debugInfo[$i]["function"] : 'null') . " }\r\n";
            $functionNums[1]--;
        }
        $stack .= "]";
        return $stack;
    }

    /**
     * Description: 把数组或者对象转为字符
     * User: 郭玉朝
     * CreateTime: 2018/4/25 下午9:40
     * @param $input
     */
    function try_covert_to_string( &$input ) {
        if( is_object( $input ) || is_array( $input ) ) {
            $input = var_export( $input, true );
        }
    }

    /**
     * Description: 错误日志
     * User: 郭玉朝
     * CreateTime: 2018/4/25 下午9:40
     * @param $string
     */
    function error( $string ) {
        $this->logType = Config::LOG_ERROR;
        $this->try_covert_to_string( $string );
        $this->logging( $string );
    }

    /**
     * Description: 跟踪日志
     * User: 郭玉朝
     * CreateTime: 2018/4/25 下午9:41
     * @param $string
     */
    function trace( $string ) {
        $this->logType = Config::LOG_TRACE;
        $this->try_covert_to_string( $string );
        $this->logging( $string );
    }

    /**
     * Description: 调试日志
     * User: 郭玉朝
     * CreateTime: 2018/4/25 下午9:41
     * @param $string
     */
    function debug( $string ) {
        $this->logType = Config::LOG_DEBUG;
        $this->try_covert_to_string( $string );
        $this->logging( $string );
    }
}
