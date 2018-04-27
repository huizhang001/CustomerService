<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/25 下午7:04
 * Description:
 */
namespace Tool\Log;
class Config
{
    const LOG_PATH = PUBLIC_PATH . '/../runtime/log'; // 日志目录
    const TIME_ZONE = 'Asia/Shanghai'; // 模式时区为上海
    const LOG_DEBUG = "DEBUG"; // 调试模式
    const LOG_ERROR = "ERROR"; // 错误
    const LOG_TRACE = "TRACE"; // 追踪模式
}

