<?php
define("APPID" , trim($rsUsers["Users_WechatAppId"]));
define("APPSECRET", trim($rsUsers["Users_WechatAppSecret"]));
define("MCHID",trim($rsPay["PaymentWxpayPartnerId"]));
define("KEY",trim($rsPay["PaymentWxpayPartnerKey"]));
define("JS_API_CALL_URL","http://".$_SERVER['HTTP_HOST']."/pay/wxpay2/sendto.php?UsersID=".$UsersID."_".$OrderID);
define("NOTIFY_URL","http://".$_SERVER['HTTP_HOST']."/pay/wxpay2/notify_url.php");
define("CURL_TIMEOUT",30);
define("SSLCERT_PATH",$_SERVER["DOCUMENT_ROOT"].$rsPay["PaymentWxpayCert"]);
define("SSLKEY_PATH",$_SERVER["DOCUMENT_ROOT"].$rsPay["PaymentWxpayKey"]);
?>