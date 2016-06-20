<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/virtual.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');

$OrderID = isset($_GET["OrderID"]) ? $_GET["OrderID"] : 0;

//$OrderID = substr($OrderID,10);
$rsOrder=$DB->GetRs("user_order","Users_ID,User_ID,Order_Status","where Order_ID='".$OrderID."'");
if(!$rsOrder){
	echo "订单不存在";
	exit;
}
$UsersID = $rsOrder["Users_ID"];
$UserID = $rsOrder["User_ID"];
$Status = $rsOrder["Order_Status"];

$pay_order = new pay_order($DB,$OrderID);
$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$UserID);

if($Status==1){
	$data = $pay_order->pay_orders();
	if($data["status"]==1){
		echo "<script type='text/javascript'>window.location.href='".$data["url"]."';</script>";	
		exit;		
	}else{
		echo $data["msg"];
		exit;
	}
}else{
	$url = '/api/'.$UsersID.'/shop/member/status/'.$Status.'/';
	echo "<script type='text/javascript'>window.location.href='".$url."';</script>";	
	exit;
}
?>