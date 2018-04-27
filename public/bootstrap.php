<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/25 下午5:39
 * Description:
 */
// Autoload 自动载入

use think\Db;

require PUBLIC_PATH.'/../vendor/autoload.php';
require '../config/database.php';
Db::setConfig(require '../config/database.php');
// whoops 错误提示

$whoops = new \Whoops\Run;

$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

$whoops->register();