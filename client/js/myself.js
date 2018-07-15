
const C = 'customer'; // 客户
const CS = 'customer_service'; // 客户
const MSG_TYPE_CS_ADD_IMG = "CS_ADD_IMG";
const NEWS_TYPE_IMAGE = 'image'; // 图片
const NEWS_TYPE_EMOJI = 'emoji'; // 表情
const NEWS_TYPE_ORDINARY = 'ordinary';// 普通
const CS_CONNECT = 'CS_CONNECT'; // 服务器连接

/**
 * 滚动条移动到最底端
 * @param idName
 */
var scrollBottom = function (idName) {
    //聊天框默认最底部
    $(document).ready(function () {
        $("#" + idName).scrollTop($("#" + idName)[0].scrollHeight);
    });
};

/**
 * 请求图片地址
 */
var imgUrl = function () {
    data = JSON.stringify({'base64_img': images, 'customer_service_name': 'kefu1', 'msg_type': MSG_TYPE_CS_ADD_IMG});
    $.ajax({
        type:"POST",
        url: url,
        data: data,
        dataType:"json",
        async: false,
        success:function(data){
            return data.data;
        },
        error:function(jqXHR){
            alert("上传出错");
        }
    });
};

/**
 * 对数据的处理
 * @returns {*}
 */
var dataDeal = function () {
    return {
        // 发送数据的格式
        sendDataType: function (msgType, clientId, data) {
            return JSON.stringify({'msg_type': msgType,'client_id':clientId, 'data': data});
        }
    };
};



