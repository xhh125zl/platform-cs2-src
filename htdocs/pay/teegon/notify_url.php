<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/virtual.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');

ini_set("display_errors","On");
$notifyData = $_POST; 
$order_no = $notifyData['order_no'];
if(strpos($order_no,'PRE')>-1){
	$rsOrder = $DB->GetRs("user_pre_order","*","where pre_sn='".$order_no."'");
	if(!$rsOrder){
		echo "订单不存在";
		exit;
	}
	$UsersID = $rsOrder["usersid"];
	$UserID = $rsOrder["userid"];
	$Status = $rsOrder["status"];
}else{
	$order_no = substr($order_no,10);
	$rsOrder=$DB->GetRs("user_order","Users_ID,User_ID,Order_Status","where Order_ID='".$order_no."'");
	if(!$rsOrder){
		echo "订单不存在";
		exit;
	}
	$UsersID = $rsOrder["Users_ID"];
	$UserID = $rsOrder["User_ID"];
	$Status = $rsOrder["Order_Status"];
}

$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
require_once(__DIR__.'/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/pay/teegon/autoload.php');
$charge = new charge();
$issign = $charge->checkSign($notifyData,$notifyData['sign']);
if($issign == true && $notifyData['is_success']===true){
	$pay_order = new pay_order($DB,$order_no);
	if($Status==1){
		$data = $pay_order->make_pay();
		if($data["status"]==1){
			//进行分账佣金计算
		    
		    
		    
		    
			exit;
		}else{
			echo $data["msg"];
			exit;
		}
	}else{
		echo "SUCCESS";
		exit;
	}
}
?>