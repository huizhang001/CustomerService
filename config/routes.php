<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/25 下午4:55
 * Description:
 */
use NoahBuscher\Macaw\Macaw;
use App\Api\CustomerServiceApi\CustomerServiceApi;
use Tool\Encrypt\Encrypt;
use Tool\Request\Request;
/**
 * 添加客服
 */
Macaw::get('CustomerService/public/index.php/add_customer_service', function() {
    $test = new CustomerServiceApi();
    $str = json_encode([
        'data'      => [
            'customer_service_name' => "客服1"
        ]
    ]);
    $res = Encrypt::authcodeEncrypt($str,Encrypt::ENCRYPT);
    $test->addCustomerService(Request::instance($res));
});

Macaw::get('(:all)', function($fu) {
    echo '未匹配到路由<br>'.$fu;
});

Macaw::dispatch();

