<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["ItemID"])){
	$ItemID=$_GET["ItemID"];
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
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsCharge=$DB->GetRs("user_charge","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Item_ID=".$ItemID);
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
if(!$rsCharge){
	echo '此充值记录不存在';
	exit;
}
if($rsCharge && $rsCharge["Status"]==1){
	echo '此充值记录已完成';
	exit;
}
$PaymentMethod = array(
	"1"=>"微支付",
	"2"=>"支付宝"
);
if($Method==1){//微支付
	$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
	if($rsPay["PaymentWxpayEnabled"]==0 || empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
		echo '商家“微支付”支付方式未启用或信息不全，暂不能支付！';
		exit;
	}
	$pay_fee = $rsCharge["Amount"];
	$pay_orderno = $ItemID;
	$pay_subject = $SiteName."(会员:".$_SESSION[$UsersID."User_ID"].")在线充值，充值编号:".$ItemID;
	if($rsPay["PaymentWxpayType"]==1){
		header("location:/pay/wxpay2/sendto_charge.php?UsersID=".$UsersID."_".$ItemID);
	}else{
		header("location:/pay/wxpay/sendto_charge.php?UsersID=".$UsersID."_".$ItemID);
	}
}elseif($Method==2){//支付宝
	if($rsPay["Payment_AlipayEnabled"]==0 || empty($rsPay["Payment_AlipayPartner"]) || empty($rsPay["Payment_AlipayKey"]) || empty($rsPay["Payment_AlipayAccount"])){
		echo '商家“支付宝”支付方式未启用或信息不全，暂不能支付！';
		exit;
	}
	$pay_fee = $rsCharge["Amount"];
	$pay_orderno = $ItemID;
	$pay_subject = $SiteName."(会员:".$_SESSION[$UsersID."User_ID"].")在线充值，充值编号:".$ItemID;
	header("location:/pay/alipay/sendto_charge.php?UsersID=".$UsersID."_".$ItemID);
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
<title>会员充值</title>
</head>

<body>
页面正在跳转至<?php echo $PaymentMethod[$Method];?>支付页面
</body>
</html>