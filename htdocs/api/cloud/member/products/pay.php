<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
$base_url = base_url();
$shop_url = shop_url();

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["OrderID"])){
	$OrderID=$_GET["OrderID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["Method"])){
	$Method=$_GET["Method"];
}else{
	echo '缺少必要的参数';
	exit;
}

$rsOrder=$DB->GetRs("user_gift_orders","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID='".$OrderID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
if(!$rsOrder){
	echo '此订单无效';
	exit;
}
if($rsOrder && $rsOrder["Orders_Status"]!=0){
	echo '此订单不是“待付款”状态，不能付款';
	exit;
}
$PaymentMethod = array(
	"1"=>"微支付",
	"2"=>"支付宝",
	"4"=>"易宝"
);
if($Method==1){//微支付
	$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
	if($rsPay["PaymentWxpayEnabled"]==0 || empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
		echo '商家“微支付”支付方式未启用或信息不全，暂不能支付！';
		exit;
	}
	
	if($rsPay["PaymentWxpayType"]==1){
		header("location:/pay/wxpay2/sendto.php?UsersID=".$UsersID."_gift".$OrderID);
	}else{
		header("location:/pay/wxpay/sendto.php?UsersID=".$UsersID."_gift".$OrderID);
	}
	
}elseif($Method==2){//支付宝
	
	if($rsPay["Payment_AlipayEnabled"]==0 || empty($rsPay["Payment_AlipayPartner"]) || empty($rsPay["Payment_AlipayKey"]) || empty($rsPay["Payment_AlipayAccount"])){
		echo '商家“支付宝”支付方式未启用或信息不全，暂不能支付！';
		exit;
	}
	
	header("location:/pay/alipay/sendto.php?UsersID=".$UsersID."_gift".$OrderID);
}elseif($Method==4){//易宝支付
    if($rsPay["PaymentYeepayEnabled"]==0 || empty($rsPay["PaymentYeepayAccount"]) || empty($rsPay["PaymentYeepayPrivateKey"]) || empty($rsPay["PaymentYeepayPublicKey"]) || empty($rsPay["PaymentYeepayYeepayPublicKey"])){
		echo '商家“支付宝”支付方式未启用或信息不全，暂不能支付！';
		exit;
	}
	header("location:/pay/yeepay/sendto.php?UsersID=".$UsersID."_gift".$OrderID);
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
<title>积分换礼订单付款</title>
</head>

<body>
页面正在跳转至<?php echo $PaymentMethod[$Method];?>支付页面
</body>
</html>