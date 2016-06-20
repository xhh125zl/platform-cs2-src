<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/virtual.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
include 'config.php';
include 'lib/yeepayMPay.php';
/**
*此类文件是有关回调的数据处理文件，根据易宝回调进行数据处理

*/
$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
try {
	$return = $yeepay->callback($_POST['data'], $_POST['encryptkey']);
// TODO:添加订单处理逻辑代码
    $rsOrder=$DB->GetRs("user_order","Users_ID,User_ID,Order_Status","where Order_ID='".$return['orderid']."'");
	if(!$rsOrder){
		echo "fail";
		exit;
	}
    $UsersID = $rsOrder["Users_ID"];
	$UserID = $rsOrder["User_ID"];
	$Status = $rsOrder["Order_Status"];
	
	$pay_order = new pay_order($DB,$OrderID);
	$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
	$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$UserID);
	if($Status==1){
		$data = $pay_order->pay_orders();
		if($data["status"]==1){
			echo "success";
			exit;		
		}else{
			echo "fail";
			exit;
		}
	}else{
		echo "success";
		exit;
	}
	
    $james=fopen('successlog.txt',"a+");
	fwrite($james,"\r\n".date("Y-m-d H:i:s")."|".var_dump($return)."");
	fclose($james);
	
}catch (yeepayMPayException $e) {
// TODO：添加订单支付异常逻辑代码
    
}
?>