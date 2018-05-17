<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/5/10 上午9:38
 * Description:
 */
namespace Tool\File;

use Tool\Log\Log;

class File
{
    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午9:58
     * @var null
     * Description: 当前对象
     */
    protected static $myself = null;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午10:00
     * @var
     * Description: 文件名称
     */
    protected $fileName;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午10:01
     * @var
     * Description: 存放文件的路径
     */
    protected $filePath;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午10:04
     * @var
     * Description: 文件的完全路径
     */
    protected $imageSrc;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午10:33
     * @var
     * Description: 文件扩展名
     */
    protected $fileExt;

    /**
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午10:01
     * @var
     * Description: base64数据
     */
    protected $base64;

    /**
     * Description: 文件可访问的url
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午10:56
     */
    protected $fileUrl;

    /**
     * File constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->initParams($params); // 初始化参数
        $this->createPath();
    }

    /**
     * Description: 创建路径
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午10:12
     */
    protected function createPath() {
        umask(0); // 777
        date_default_timezone_set(     'Asia/Shanghai'); // 设置时区
        if (!is_dir($this->filePath)){ //判断目录是否存在 不存在就创建
            mkdir($this->filePath,0777,true);
        }
    }

    /**
     * Description: 初始化对象的内部参数
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午9:58
     * @param array $params
     */
    protected function initParams(array $params) {
        if (isset($params[0])) {
            $this->filePath = $params[0];
        } else {
            $this->filePath = "../runtime/img/" . date("Ymd",time()) . "/";
        }
        if (isset($params[1])) {
            $this->fileName = $params[1];
        } else {
            $this->fileName = time();
        }
        if (isset($params[2])) {
            $this->fileExt = $params[2];
        } else {
            $this->fileExt = ".txt";
        }
        $this->imageSrc = $this->filePath . $this->fileName;
    }

    /**
     * Description: 单例模式
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午9:44
     * @param array $params 0 图片存放路径 1 图片存放名称
     * @return File
     */
    public static function instance(array $params = []) {
        if (File::$myself == null) {
            File::$myself = new File($params);
        }
        return File::$myself;
    }

    /**
     * Description: 创建base64的文件
     * User: 郭玉朝
     * CreateTime: 2018/5/10 上午9:56
     * @param $data
     * @return bool|int
     */
    public function createBase64(string $data) {
        $base64String= explode(',', $data); //截取data:image/png;base64, 这个逗号后的字符
        if (count($base64String) != 2) {
            return 0;
        }
        $this->fileExt = "." . substr($base64String[0], 11, strlen($base64String[0]) - 18);
        $this->fileUrl = ROOT_PATH . str_replace(array("."),"", $this->filePath) . $this->fileName . $this->fileExt;
        $data= base64_decode($base64String[1]);//对截取后的字符使用base64_decode进行解码
        file_put_contents($this->filePath . $this->fileName . $this->fileExt,  $data);//返回的是字节数
        return $this->fileUrl;
    }
}
