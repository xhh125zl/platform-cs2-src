<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/virtual.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
ini_set("display_errors","On"); 

var_export($_POST,true);
$OrderID = isset($order_no) ? $order_no : 0;
if(!$OrderID){
	echo "不正确的订单";
	exit;
}
$UsersID = "";
$Status = 0;
$rsamount = 0;

if(strpos($OrderID,'PRE')>-1){
	$rsOrder = $DB->GetRs("user_pre_order","status,usersid","where pre_sn='".$OrderID."'");
	if(!$rsOrder){
		echo "订单不存在";
		exit;
	}
	$UsersID = $rsOrder["usersid"];
	$Status = $rsOrder["status"];
	$rsamount = $rsOrder["total"];
}else{
	$OrderID = substr($order_no,10);
	$rsOrder=$DB->GetRs("user_order","Users_ID,Order_Status","where Order_ID='".$OrderID."'");
	if(!$rsOrder){
		echo "订单不存在";
		exit;
	}
	$UsersID = $rsOrder["Users_ID"];
	$Status = $rsOrder["Order_Status"];
	$rsamount = $rsOrder["Order_TotalPrice"];
}

$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
require_once(__DIR__.'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/pay/teegon/autoload.php');
$charge = new charge();
$verify = $charge->verify_return();
if($verify === 0){
	if($Status==1){
		echo "付款失败";
		exit;
	}else{
		$url = '/api/'.$UsersID.'/shop/member/status/'.$Status.'/';
		echo "<script type='text/javascript'>window.location.href='".$url."';</script>";	
		exit;
	}
}else{
	echo $verify['error_msg'];
	exit;
}


?>