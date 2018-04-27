<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/26 下午10:54
 * Description: 提供对外的客服增删改查接口
 */
namespace App\Api\CustomerServiceApi;

use App\Common\Model\CustomerServiceModel;
use Tool\Response\Response;
use Tool\Request\Request;
use App\Api\ApiInter\CustomerServiceApiInter;
use Tool\Log\Log;
class CustomerServiceApi implements CustomerServiceApiInter
{

    /**
     * Description: 添加客服接口
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午12:22
     * @param Request $request
     */
    public function addCustomerService(Request $request)
    {
        // TODO: Implement addCustomerService() method.
        $data = $request->params; // 处理后的数据
        // 检测参数
        $checkResult = $request->checkParams([['data' => ['customer_service_name']]]);
        if ($checkResult !== true) {
            Log::instance(['customer_service_api'])->error('缺少参数:' . $checkResult);
            Response::returnResult(Response::CODE_ERROR, $checkResult, []);
        }
        $saveData = ['customer_service_name' => $data['data']['customer_service_name']];
        // 验证是否重复
        if (CustomerServiceModel::instance()->findInfo($saveData)) {
            Log::instance(['customer_service_api'])->error('客服名称重复不能重复添加');
            Response::returnResult(Response::CODE_ERROR, "客服名称重复不能重复添加");
        }
        // 添加客服
        if (!CustomerServiceModel::instance()->addInfo($saveData)) {
            Log::instance(['customer_service_api'])->error('添加客服失败');
            Response::returnResult(Response::CODE_ERROR, "添加客服失败");
        }
        Response::returnResult(Response::CODE_SUCCESS, "添加客服成功");
    }
}