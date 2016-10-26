<?php
define('USER_PATH', dirname(__FILE__) . '/');

include USER_PATH . '../Framework/Conn.php';
require_once CMS_ROOT . '/include/helper/tools.php';
require_once CMS_ROOT . '/include/api/users.class.php';

$wxAppID = 'wxd89171f2322edc10';
$wxAppSecret = 'a147ceb42cf7aeef53f1139377759dcd';

/*
 * 请求微信接口,并把返回结果转换为数组
 */
function curlWxInterFace($url, $method = 'get', $postfields = [])
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if ($method == 'post') {
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    }
    $output = curl_exec($ch);
    $arr = json_decode($output, true);
    return $arr;
}

$wxAccessCode = (isset($_GET['wxaccesscode']) && strlen($_GET['wxaccesscode']) > 5) ? htmlspecialchars($_GET['wxaccesscode']) : 0;
$uuid = (isset($_GET['uuid']) && strlen($_GET['uuid']) > 5) ? htmlspecialchars($_GET['uuid']) : 0;

if ($wxAccessCode != 0 && $uuid != 0) {
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$wxAppID&secret=$wxAppSecret&code=$wxAccessCode&grant_type=authorization_code";
    $resArr = curlWxInterFace($url);
    $openid = $resArr['openid'];
    $transData = ['openid' => $openid];
    $bizFlag = users::getBizByOpenid($transData);
    if ($bizFlag['errorCode'] == 0) {
        $bizData = $bizFlag['data'];
        $_SESSION["BIZ_ID"]=$bizData["Biz_ID"];
        $_SESSION['Biz_Account'] = $bizData['Biz_Account'];
        $_SESSION["Users_ID"]=$bizData["Users_ID"];
        $time = time();
        $bizLs = $DB->GetRs('biz', '*', "where uuid = '". $uuid ."' and Biz_ID <> " . $bizData["Biz_ID"]);
        if ($bizLs) {
            $DB->Set('biz',['uuid' => 0], "where uuid = '". $uuid ."'");
        }
        $DB->Set('biz',['loginTime' => $time+86400*30, 'uuid' => $uuid], "where Biz_ID = " . $bizData["Biz_ID"]);
        header("Location:/user/admin.php?act=store&time={$time}&bizID=".$bizData['Biz_ID']);
        exit();
    } else {
        $accessToken = $resArr['access_token'];
        $userinfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=$accessToken&openid=$openid&lang=zh_CN";
        $userinfoArr = curlWxInterFace($userinfoUrl);
        $headurl = $userinfoArr['headimgurl'];
        header("Location:http://cs2.3jke.com/user/bind.php?openid={$openid}&headurl={$headurl}&uuid={$uuid}");
        exit();
    }
} else {
    if ($uuid != 0) {
        header("Location:http://cs2.3jke.com/user/login.php?uuid=" . $uuid);
        exit();
    } else {
        header("Location:http://cs2.3jke.com/user/login.php");
        exit();
    }
}