<?php
/**
* 
*/
define("APPID" , trim($rsUsers["Users_WechatAppId"]));  //appid
define("APPKEY" ,trim($rsPay["PaymentWxpayPaySignKey"])); //paysign key
define("SIGNTYPE", "sha1"); //method
define("PARTNERID",trim($rsPay["PaymentWxpayPartnerId"]));//通加密串
define("PARTNERKEY",trim($rsPay["PaymentWxpayPartnerKey"]));//通加密串
define("APPSECRET", trim($rsUsers["Users_WechatAppSecret"]));

?>