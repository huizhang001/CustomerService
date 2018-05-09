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
use App\Consts;

class CustomerServiceApi implements CustomerServiceApiInter
{

    /**
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午10:05
     * @var Request
     * Description: 得到请求的对象
     */
    protected $request;

    /**
     * CustomerServiceApi constructor.
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
     * @return CustomerServiceApi
     */
    public static function instance(Request $request) {
        return new CustomerServiceApi($request);
    }

    /**
     * Description: 添加客服接口
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午12:22
     */
    public function addCustomerService()
    {
        // TODO: Implement addCustomerService() method.
        $data = $this->request->params; // 处理后的数据
        // 检测参数
        $this->judgeCheckResult($this->request->checkParams([['data' =>
            ['customer_service_name','customer_service_sign']]]));
        $this->judgeOnly([
            'customer_service_name' => $data['data']['customer_service_name'],
        ]); // 验证客服的唯一性
        // 添加客服
        if (!CustomerServiceModel::instance()->addInfo($data['data'])) {
            Log::instance(['customer_service_api'])->error('添加客服失败');
            Response::returnResult(Response::CODE_ERROR, "添加客服失败");
        }
        Response::returnResult(Response::CODE_SUCCESS, "添加客服成功");
    }

    /**
     * Description: 修改客服
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午10:40
     */
    public function editCustomerService()
    {
        // TODO: Implement editCustomerService() method.
        $data = $this->request->params; // 处理后的数据
        // 检测参数
        $this->judgeCheckResult($this->request->checkParams(['customer_service_id',['data' =>
            ['customer_service_name','customer_service_sign']]]));
        $editData = ['customer_service_name' => $data['data']['customer_service_name']];
        $editCondition = ['customer_service_id' => $data['customer_service_id']];
        $this->judgeOnly($editData); // 验证客服的唯一性
        // 修改客服
        if (!CustomerServiceModel::instance()->editInfo($editCondition, $data['data'])) {
            Log::instance(['customer_service_api'])->error('修改客服失败');
            Response::returnResult(Response::CODE_ERROR, "修改客服失败");
        }
        Response::returnResult(Response::CODE_SUCCESS, "修改客服成功");
    }

    /**
     * Description: 删除客服
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午11:02
     */
    public function delCustomerService()
    {
        // TODO: Implement delCustomerService() method.
        $data = $this->request->params; // 处理后的数据
        $this->judgeCheckResult($this->request->checkParams(['customer_service_id']));
        $delCondition = ['customer_service_id' => $data['customer_service_id']];
        // 删除
        if (!CustomerServiceModel::instance()->delInfo($delCondition)) {
            Log::instance(['customer_service_api'])->error('删除客服失败');
            Response::returnResult(Response::CODE_ERROR, "删除客服失败");
        }
        Response::returnResult(Response::CODE_SUCCESS, "删除客服成功");
    }

    /**
     * Description: 查找客服
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午11:07
     */
    public function findCustomerService()
    {
        // TODO: Implement findCustomerService() method.
        $data = $this->request->params; // 处理后的数据
        $this->judgeCheckResult($this->request->checkParams(['customer_service_id']));
        $findCondition = ['customer_service_id' => $data['customer_service_id']];
        // 查找客服
        $findInfo = CustomerServiceModel::instance()->findInfo($findCondition);
        if (!$findInfo) {
            Log::instance(['customer_service_api'])->error('查找客服失败');
            Response::returnResult(Response::CODE_ERROR, "查找客服失败");
        }
        Response::returnResult(Response::CODE_SUCCESS, "查找客服成功", $findInfo);
    }

    /**
     * Description: 验证参数是否成功
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午11:14
     * @param $checkResult
     * @return bool
     */
    protected function judgeCheckResult($checkResult)
    {
        if ($checkResult !== true) {
            Log::instance(['customer_service_api'])->error('缺少参数:' . $checkResult);
            Response::returnResult(Response::CODE_ERROR, $checkResult, []);
        }
        return true;
    }

    /**
     * Description: 验证客服的唯一性
     * User: 郭玉朝
     * CreateTime: 2018/4/28 上午11:25
     * @param array $condition
     */
    protected function judgeOnly(array $condition)
    {
        // 验证是否重复
        if (CustomerServiceModel::instance()->findInfo($condition)) {
            Log::instance(['customer_service_api', $condition])->error('客服名称重复不能修改');
            Response::returnResult(Response::CODE_ERROR, "客服名称重复不能修改");
        }
        return true;
    }

    public function isOnlineCustomerService()
    {
        // TODO: Implement isOnlineCustomerService() method.
        Response::returnResult(Response::CODE_SUCCESS, $this->request->data, Consts::CS_ADD_IMG,
            Response::DATA_TYPE_JSON, Response::RETURN_TYPE_RETURN);
    }


}
