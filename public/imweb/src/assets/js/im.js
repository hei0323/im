/**
 * 与GatewayWorker建立websocket连接，域名和端口改为你实际的域名端口，
 * 其中端口为Gateway端口，即start_gateway.php指定的端口。
 * start_gateway.php 中需要指定websocket协议，像这样
 * $gateway = new Gateway(websocket://0.0.0.0:7272);
 */
ws = new WebSocket("ws://im.newweb.com:8282");
// 服务端主动推送消息时会触发这里的onmessage
ws.onmessage = function(e){
    // json数据转换成js对象
    let data = eval("("+e.data+")");
    let type = data.type || '';
    switch(type){
        // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
        case 'init':
            // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
            $.post('http://im.newweb.com/auth/bind/'+data.client_id, {client_id: data.client_id}, function(data){}, 'json');
            break;
        // 当mvc框架调用GatewayClient发消息时直接alert出来
        default :
            /*alert(e.data);*/
    }
};