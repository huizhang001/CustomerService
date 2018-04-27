<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/25 下午6:46
 * Description:
 */
namespace App\Server\Customer;
use Tool\Log\Log;
class test
{
    public function test()
    {
        Log::instance(["hahahahah"])->trace("哈哈测试成功");
    }
}