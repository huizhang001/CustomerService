<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/5/16 下午2:10
 * Description:
 */
namespace App\Api\CommonApi;
use App\Api\ApiInter\CommonApiInter;
use App\Consts;
use Tool\File\File;
use Tool\Request\Request;
use Tool\Response\Response;

class CommonApi implements CommonApiInter
{

    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午10:05
     * @var Request
     * Description: 得到请求的对象
     */
    protected $request;

    /**
     * CommonApi constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Description: 目的是为了省代码
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午10:05
     * @param Request $request
     * @return CommonApi
     */
    public static function instance(Request $request) {
        return new CommonApi($request);
    }

    /**
     * Description: 保存聊天图片
     * User: 郭玉朝
     * CreateTime: 2018/5/16 下午2:55
     * @return string
     */
    public function saveImg()
    {
        $result = File::instance()->createBase64($this->request->data['base64_img']);
        // TODO: Implement isOnlineCustomerService() method.
        return Response::returnResult(Response::CODE_SUCCESS, '保存图片成功', $result,
            Consts::CS_ADD_IMG, Response::DATA_TYPE_JSON, Response::RETURN_TYPE_DIE);
    }

}