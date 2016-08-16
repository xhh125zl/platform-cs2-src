<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(isset($_GET["OrderID"])){
	$OrderID=$_GET["OrderID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$rsConfig=$DB->GetRs("zhongchou_config","*","where usersid='".$UsersID."'");
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
if(!$rsConfig || !$rsOrder){
	echo '此订单无效';
	exit;
}
if($rsOrder && $rsOrder["Order_Status"]!=0){
	echo '此订单不是“待付款”状态，不能付款';
	exit;
}

$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
if($rsPay["PaymentWxpayEnabled"]==0 || empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
	echo '商家“微支付”支付方式未启用或信息不全，暂不能支付！';
	exit;
}
$pay_fee = $rsOrder["Order_TotalPrice"];
$pay_orderno = $OrderID;
$pay_subject = $SiteName."(".$UsersID.")微众筹在线支付，订单编号:".$OrderID;
if($rsPay["PaymentWxpayType"]==1){
	header("location:/pay/wxpay2/sendto.php?UsersID=".$UsersID."_".$OrderID);
}else{
	header("location:/pay/wxpay/sendto.php?UsersID=".$UsersID."_".$OrderID);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["name"] ?></title>
</head>

<body>
页面正在跳转至微支付页面
</body>
</html>