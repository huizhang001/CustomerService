<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/28 下午12:05
 * Description:
 */
use NoahBuscher\Macaw\Macaw;
use Tool\Request\Request;
use App\Api\CustomerServiceApi\CustomerServiceApi;
use Tool\Log\Log;
use Tool\File\File;
use App\Api\CommonApi\CommonApi;

// 添加客服
Macaw::get('add_customer_ervice', function() {
    CustomerServiceApi::instance(Request::instance([$_POST]))->addCustomerService();
});

// 修改客服
Macaw::get('edit_customer_ervice', function() {
    CustomerServiceApi::instance(Request::instance([$_POST]))->editCustomerService();
});

// 删除客服
Macaw::get('del_customer_ervice', function() {
    CustomerServiceApi::instance(Request::instance([$_POST]))->delCustomerService();
});

// 查询客服
Macaw::get('find_customer_ervice', function() {
    CustomerServiceApi::instance(Request::instance([$_POST]))->findCustomerService();
});

// 上传图片到客服并返回图片地址
Macaw::post('CustomerService/public/index.php/save_img', function() {
    CommonApi::instance(Request::instance([file_get_contents("php://input"), '', false]))
        ->saveImg();
});

Macaw::get('(:all)', function($fu) {
    echo '未匹配到路由<br>'.$fu;
});

Macaw::dispatch();