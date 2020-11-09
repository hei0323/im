<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IM即时通讯</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/chat.css">
    <script src="../js/jquery-1.12.4.js"></script>
    <script src="../js/iconfont.js"></script>
    <script src="../js/im.js"></script>
</head>
<body>
<div class="mainBox">
    <!--左侧功能主菜单导航-->
    <div class="navLeft">
        <input name="userId" type="hidden" value="">
        <input name="userName" type="hidden" value="">
        <div class="state">
            <button class="stateButton offline"></button>
            <button class="stateButton busy"></button>
            <button class="stateButton online"></button>
        </div>
        <div class="avatar"></div>
        <div class="mane">
            <svg id="myMassage" class="icon" aria-hidden="true">
                <use xlink:href="#icon-message-3-fill"></use>
            </svg>
            <svg id="myFriends" class="icon" aria-hidden="true">
                <use xlink:href="#icon-contacts-fill"></use>
            </svg>
            <svg id="myGroups" class="icon" aria-hidden="true">
                <use xlink:href="#icon-group-fill"></use>
            </svg>
            <svg id="allUsers" class="icon" aria-hidden="true">
                <use xlink:href="#icon-team-line"></use>
            </svg>
            <svg id="setting" class="icon" aria-hidden="true">
                <use xlink:href="#icon-settings-4-line"></use>
            </svg>
        </div>
    </div>
    <!--右侧功能附属菜单导航-->
    <div class="navRight">
        <div class="mane"></div>
    </div>
    <!--中间功能主体区域-->
    <div class="funcMiddle">
        <div class="addressBooks">
            <div class="search">
                <span></span>
                <input type="text" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;搜索">
            </div>
            <div class="fixed"></div>
            <div class="contact">
                <ul class="myMassage">
                    <li>
                        <div class="contactMember">
                            <div class="memberLeft contactInfo">
                                <img src="../images/avatar.png" alt="">
                            </div>
                            <div class="memberRight">
                                <div>
                                    <span class="contactName">聊天消息</span>
                                    <span class="contactTime">18:22</span>
                                </div>
                                <div class="contactMsg">小明：@全体 哈喽！</div>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="myFriends">
                    <li>
                        <div class="contactMember">
                            <div class="memberLeft contactInfo">
                                <img src="../images/avatar.png" alt="">
                            </div>
                            <div class="memberRight">
                                <div>
                                    <span class="contactName">我的好友</span>
                                    <span class="contactTime">18:22</span>
                                </div>
                                <div class="contactMsg">小明：@全体 哈喽！</div>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="myGroups">
                    <li>
                        <div class="contactMember">
                            <div class="memberLeft contactInfo">
                                <img src="../images/avatar.png" alt="">
                            </div>
                            <div class="memberRight">
                                <div>
                                    <span class="contactName">我的群组</span>
                                    <span class="contactTime">18:22</span>
                                </div>
                                <div class="contactMsg">小明：@全体 哈喽！</div>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="allUsers">
                    <li>
                        <div class="contactMember">
                            <div class="memberLeft contactInfo">
                                <img src="../images/avatar.png" alt="">
                            </div>
                            <div class="memberRight">
                                <div>
                                    <span class="contactName">所有用户</span>
                                    <span class="contactTime">18:22</span>
                                </div>
                                <div class="contactMsg">小明：@全体 哈喽！</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="chatBox">
            <div class="chatHeader contactInfo">
                <img src="../images/avatar.png" alt="">
                <div class="contactAbstract">
                    <input type="hidden" name="userId" value="">
                    <span>项目沟通群</span>
                    <ul>
                        <li>38</li>
                        <li>20</li>
                        <li>海外调研分享会-4月6日</li>
                    </ul>
                </div>
                <div class="contactFunc">功能快捷图标</div>
            </div>
            <div class="chatBody">
                <ul id="317306">
                    <li>
                        <div class="contactInfo">
                            <img class="contactAvatar" src="../../images/avatar.png" alt=""><span>王铁锤</span>
                        </div>
                        <div class="msgContent">你好！我王铁锤！</div>
                    </li>
                    <li>
                        <div class="contactInfo">
                            <img class="contactAvatar myAvatar" src="../../images/avatar.png" alt=""><span
                                    class="myName">王铁锤</span>
                        </div>
                        <div class="myMsg">
                            <span class="msgContent">
                                你好！我王铁锤！
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="chatSend">
                <div class="attach">
                    <span class="emoji"></span>
                    <span class="pic"></span>
                    <span class="love"></span>
                </div>
                <div class="sendMsg">
                    <textarea type="text" name="msgText" placeholder="输入消息..."></textarea>
                    <button type="submit" value="发送">发送</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{--欢迎回来弹出窗--}}
<div class="welcome">
    <span class="userName">XXX!</span><span class="welcomeText">欢迎回来！</span>
</div>

</body>
</html>
