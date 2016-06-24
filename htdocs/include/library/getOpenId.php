<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/userinfo.class.php');
$rswechat = $DB->GetRs("users", "Users_WechatAppId,Users_WechatAppSecret,Users_WechatType", "where Users_ID='" . $UsersID . "'");
if (! $rswechat["Users_WechatAppId"] || ! $rswechat["Users_WechatAppSecret"]) {
    die($UsersID . "的AppId和AppSecret没有配置");
}

$u = new userinfo();
$u->appid = $rswechat["Users_WechatAppId"];
$u->appsecret = $rswechat["Users_WechatAppSecret"];
$u->DB = $DB;
$u->UsersID = $UsersID;

$islogin = 0;
$codebyurl = "";
if (strpos($_SERVER['REQUEST_URI'], "state=") !== false) { // 微信通信回来
    if (strpos($_SERVER['REQUEST_URI'], "code=") !== false) { // 同意授权获取code
        $arr_temp = explode("code=", $_SERVER['REQUEST_URI']);
        if (strpos($arr_temp[1], "?") != false) {
            $code_arr = explode("?", $arr_temp[1]);
            $codebyurl = $code_arr[0];
        } elseif (strpos($arr_temp[1], "&") != false) {
            $code_arr = explode("&", $arr_temp[1]);
            $codebyurl = $code_arr[0];
        } else {
            $codebyurl = $arr_temp[1];
        }
        
        $u->getOpenid($codebyurl);
        $wechatInfo = $u->getuserinfo($codebyurl);
    } else { // 用户不同意授权
        die("必须同意授权");
    }
} else { // 网站和微信通信开始
    $url = $u->createOauthUrlForCodeweb(urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
    Header("Location: $url");
}
?>