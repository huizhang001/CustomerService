<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/25 下午4:54
 * Description:
 */
// 定义 PUBLIC_PATH

define('PUBLIC_PATH', __DIR__);

// 启动器

require PUBLIC_PATH.'/bootstrap.php';

// 路由配置、开始处理

require PUBLIC_PATH.'/../config/routes.php';