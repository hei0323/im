/**
 * 与GatewayWorker建立websocket连接，域名和端口改为你实际的域名端口，
 * 其中端口为Gateway端口，即start_gateway.php指定的端口。
 * start_gateway.php 中需要指定websocket协议，像这样
 * $gateway = new Gateway(websocket://0.0.0.0:7272);
 */
$(function () {
    //实例化ws对像
    let ws = new WebSocket("ws://im.newweb.com:8282");
    //处理服务端推送过来的数据
    ws.onmessage = function(e){
        // json数据转换成js对象
        let wsData = eval("("+e.data+")");
        let type = wsData.type || '';
        switch(type){
            //用户绑定初始化
            case 'init':
                bindUser(wsData.clientId);
                break;
            // 当mvc框架调用GatewayClient发消息时直接alert出来
            case "msg":
                receiverMsg(wsData.data);
            default :
            /*alert(wsData);*/
        }
    };
});

/**
 * 处理用户的操作
 */
$(function () {
    //切换导航栏操作
    $("#myMassage").click(function () {
        $(".contact ul").hide();
        $(".myMassage").show();
    }).css({
        "fill": "#c6c698;",
        "border-left": "2px solid #dcdc98;",
    });
    $("#myFriends").click(function () {
        $(".contact ul").hide();
        $(".myFriends").show();
    }).css({
        "fill": "#c6c698;",
        "border-left": "2px solid #dcdc98;",
    });
    $("#myGroups").click(function () {
        $(".contact ul").hide();
        $(".myMassage").show();
    }).css({
        "fill": "#c6c698;",
        "border-left": "2px solid #dcdc98;",
    });

    $("#allUsers").click(function () {
        $(this).css({
            "fill": "#c6c698;",
            "border-left": "2px solid #dcdc98;",
        });
        $(".allUsers li").remove();
        //获取所有用户
        getAllUsers();
        //展示
        $(".contact ul").hide();
        $(".allUsers").show();
    });

    //切换联系人操作
    $(".contact ul ").on('click','li',function () {
        let userId = $(this).children("input").val();
        let userName = $(this).find(".contactName").text();
        let userAvatar = $(this).find("img").prop("src");

        console.log(userAvatar);
        $(".contactAbstract input").val(userId);
        $(".contactAbstract span").text(userName);
        $(".chatHeader img").prop("src",userAvatar);
        $(".chatBody ul").hide();
        if($("#"+userId).length !== 0){
            $("#"+userId).show();
        }else {
            let addElement = "<ul id=\""+userId+"\" style=\"display: block;\"></ul>";
            $(".chatBody").append(addElement);
        }
    });

    //聊天发送消息
    $(".sendMsg button:submit").click(function () {
        let contents = $(".sendMsg textarea").val();
        let receiverId = $(".contactAbstract input").val();
        let sendId = $(".navLeft input[name=userId]").val();
        let sendName = $(".navLeft input[name=userName]").val();
        let type = 1;
        let result = sendMsg(sendId,receiverId,contents,type);
        if(result){
            //渲染消息
            let child = "<li>" +
                "<div class=\"contactInfo\">" +
                "<img class=\"contactAvatar myAvatar\" src=\"../../images/avatar.png\" alt=\"\">" +
                "<span class=\"myName\">" +
                sendName +
                "</span></div><div class=\"myMsg\"><span class=\"msgContent\">" +
                contents +
                "</span></div></li>";
            $("#"+receiverId).append(child);
            $(".sendMsg textarea").val("");
            scrollChatBox();
        }else{
            //发送失败处理
        }
    });

});

/**
 * 用户绑定方法
 */
function bindUser(clientId) {
    $.get('http://im.newweb.com/auth/bind/'+clientId, function(data,status){
       if(data.code == 0){
           $(".userName").text(data.data.member_name);
           $(".welcomeText").text("欢迎你回来！ID："+data.data.member_id);
           $(".welcome").css("display","block");
           $(".welcome").fadeOut(5000);
           $(".navLeft input").val(data.data.member_id);
       }else{
           alert(data.msg)
       }
    }, 'json');
}

/**
 * 处理接收到的用户消息
 */
function receiverMsg(data) {
    let msgHtml = "<li>\n" +
        "                        <div class=\"contactInfo\">\n" +
        "                            <img class=\"contactAvatar\" src=\""+data.sender_avatar+"\" alt=\"\">" +
        "<span>"+data.sender_name+"</span>\n" +
        "                        </div>\n" +
        "                        <div class=\"msgContent\">"+ data.sender_content+"</div>\n" +
        "                    </li>";
    $("#"+data.sender_id).append(msgHtml);
    scrollChatBox();
}

/**
 * 推送消息方法
 */
function sendMsg(sendId,receiverId,contents,type) {
    let temp;
    $.ajax({
        type : "post",
        url : "http://im.newweb.com/msg/send",
        data : {'sendId':sendId,'receiverId':receiverId,'contents':contents,'type':type},
        async : false,
        success : function(data){
            if(data.code == 0){
                temp = true;
            }else {
                temp =  false;
            }
        }
    });
    return temp;
}

/**
 * 获取所有用户加到页面
 */
function getAllUsers() {
    $.ajax({
        type:"get",
        url: "http://im.newweb.com/member/online",
        data:null,
        async: true,
        success:function (data) {
            if (data.code == 0){
                let users = data.data;
                let usersHtml = '';
                for (let i=0;i<users.list.length;i++){
                    usersHtml += "<li>\n" +
                        "<input name='userId' type='hidden' value='" + users.list[i].member_id +"'>" +
                        "                        <div class=\"contactMember\">\n" +
                        "                            <div class=\"memberLeft contactInfo\">\n" +
                        "                                <img src=\"" +
                         users.list[i].member_avatar +
                        "\" alt=\"\">\n" +
                        "                            </div>\n" +
                        "                            <div class=\"memberRight\">\n" +
                        "                                <div>\n" +
                        "                                    <span class=\"contactName\">" +
                        users.list[i].member_name +
                        "                                    <span class=\"contactTime\"></span>\n" +
                        "                                </div>\n" +
                        "                                <div class=\"contactMsg\"></div>\n" +
                        "                            </div>\n" +
                        "                        </div>\n" +
                        "                    </li>";
                }
                $(".allUsers").append(usersHtml);
            }else{
                alert(data.msg);
            }
        }
    });
}


/**
 * 设置聊天框滚动到最低处
 */
function scrollChatBox() {
    let chatBoxHeight = $(".chatBody").prop("scrollHeight");
    $(".chatBody").scrollTop(chatBoxHeight);
}
