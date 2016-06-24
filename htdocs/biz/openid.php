<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/AES.php');
! isset($_GET["auth"]) && die("auth缺少参数");
! isset($_GET["Biz_ID"]) && die("Biz_ID缺少参数");
$auth = $_GET["auth"];
$Biz_ID = $_GET["Biz_ID"];
$Biz_Info = $DB->GetRs("biz", "Biz_ID,Users_ID,Biz_Account,Biz_Name,Biz_PayConfig", "WHERE Biz_ID='{$Biz_ID}'");
// 解密
$auth = str_replace(" ", "+", $auth);
$authKey = md5($Biz_Info['Biz_Account'] . $Biz_Info['Users_ID']);
$deAuth = Security::decrypt($auth, $authKey);
$deAuth = unserialize(base64_decode($deAuth));
// 判断传递的参数是否篡改

if ($Biz_ID !== $deAuth['Biz_ID']) {
    die("非法篡改数据");
}
$UsersID = $deAuth["Users_ID"];
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/getOpenId.php');

if (! empty($_SESSION[$UsersID . "OpenID"])) {
    // openid绑定
    $openid = $_SESSION[$UsersID . "OpenID"];
    if ($deAuth['Biz_Account'] == $Biz_Info['Biz_Account'] && $deAuth['Users_ID'] == $Biz_Info['Users_ID']) {
        
        // if(isset($Biz_Info['Biz_PayConfig'])){
        $biz_PayConfig = array(
            "PaymentID" => 1,
            "config" => array(
                "OpenID" => $openid,
                "PaymentMethod" => "微信结算",
                "nickname" => $wechatInfo["nickname"],
                "headimgurl" => $wechatInfo["headimgurl"]
            )
        );
        
        $PayConfig = json_encode($biz_PayConfig, JSON_UNESCAPED_UNICODE);
        // $flag = $DB->Set("biz", array("Biz_PayConfig"=>$PayConfig, "Biz_Flag"=>1),"WHERE Biz_ID='{$Biz_ID}'");
        $flag = $DB->query("update biz set Biz_PayConfig = '{$PayConfig}',Biz_Flag = 1  WHERE Biz_ID='{$Biz_ID}'");
        if ($flag) {
            die("OpenID设置成功");
        } else {
            die("OpenID设置失败");
        }
        // }else{
        // die("BizConfig不存在");
        // }
    } else {
        die("非法篡改");
    }
} else {
    print_r($_SESSION);
    die("Session丢失");
}
