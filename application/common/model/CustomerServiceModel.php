<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/26 下午11:01
 * Description:
 */
namespace App\Common\Model;

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;
use App\Common\Model\ModelInter\ModelBaseInter;
use Tool\Log\Log;

class CustomerServiceModel extends Model implements ModelBaseInter
{
    protected $table = "customer_service";
    protected $pk = 'customer_service_id';
    protected static $myself = null;

    /**
     * Description:  单例模式返回当前模型对象
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午4:22
     * @return CustomerServiceModel
     */
    public static function instance() {
        if (self::$myself == null)
        {
            self::$myself = new CustomerServiceModel();
        }
        return self::$myself;
    }

    /**
     * Description: 添加客服信息
     * User: 郭玉朝
     * CreateTime: 2018/4/27 下午4:20
     * @param array $data
     * @return bool
     */
    public function addInfo(array $data):bool
    {
        // TODO: Implement addInfo() method.
        if ($this->allowField(true)->save($data)) {
            return true;
        }
        return false;
    }

    public function editInfo(array $condition, array $data)
    {
        // TODO: Implement editInfo() method.
        return $this->save($data, $condition);
    }


    public function delInfo(array $delCondition)
    {
        // TODO: Implement delInfo() method.
        return $this->where($delCondition)->delete();
    }

    public function selectInfo()
    {
        // TODO: Implement selectInfo() method.
    }

    public function findInfo(array $condition)
    {
        // TODO: Implement findInfo() method.
        try {
            return $this->where($condition)->find();
        } catch (\Exception $e) {
            Log::instance(['customer_service_api'])->error('查找客服数据库异常');
            return false;
        }
    }


}