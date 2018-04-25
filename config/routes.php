<?php
/**
 * User: 郭玉朝
 * CreateTime: 2018/4/25 下午4:55
 * Description:
 */
use NoahBuscher\Macaw\Macaw;

Macaw::get('fuck', function() {
    echo "成功！";
});

Macaw::get('(:all)', function($fu) {
    echo '未匹配到路由<br>'.$fu;
});

Macaw::dispatch();

