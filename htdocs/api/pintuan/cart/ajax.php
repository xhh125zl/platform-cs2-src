<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
ini_set("display_errors","On");

$action = empty($_POST["action"]) ? "" : $_REQUEST["action"];
if($action=="payment"){

	$OrderID = isset($_POST['OrderID']) && !empty($_POST['OrderID'])?$_POST['OrderID']:0;
	$UsersID = isset($_POST['Users_ID']) && !empty($_POST['Users_ID'])?$_POST['Users_ID']:"";
	
	if(!$OrderID || !$UsersID){
	    die(json_encode(["status" => 0, "msg" => "参数缺失！" ],JSON_UNESCAPED_UNICODE));
	}
	
	$rsOrder = $DB->GetRs ( "user_order", "*", "where Users_ID='" . $UsersID . "' and Order_ID='" . $OrderID . "'" );
	if(empty($rsOrder)){
	    die(json_encode(["status" => 0, "msg" => "非法支付，订单不存在！" ],JSON_UNESCAPED_UNICODE));
	}
	
	if(!isset($_SESSION[$UsersID.'User_ID']) || !$_SESSION[$UsersID.'User_ID'])
	{
	    die(json_encode(["status" => 0, "msg" => "Session过期，请重新登录" ],JSON_UNESCAPED_UNICODE));
	}
	if($rsOrder['Order_Status'] != 1){
	    die(json_encode(["status"=>0, "msg"=>'此订单不是“待付款”状态，不能付款！'],JSON_UNESCAPED_UNICODE));
	}
	$Data=[
		"Order_PaymentMethod"=>$_POST['PaymentMethod'],
		"Order_PaymentInfo"=>$_POST['PaymentMethod'],
		"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"]
	];
	$PaymentMethod = [
	    "微支付" => 1,
	    "支付宝" => 2,
	    "易宝支付" => 4,
	    "银联支付" => 5
	];
	$Method = $PaymentMethod[$_POST['PaymentMethod']];
	$Flag = $DB->Set ( "user_order", $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION [$UsersID . "User_ID"] . "' and Order_ID in(".$OrderID.")");
	if($Flag){
	    $rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
	    if($Method==1){//微支付
	        $rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
	        if($rsPay["PaymentWxpayEnabled"]==0 || empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
	            die(json_encode(["status"=>0, "msg"=>'商家“微支付”支付方式未启用或信息不全，暂不能支付！'],JSON_UNESCAPED_UNICODE));
	        }
	        $url = "/pay/wxpay2/sendto_pintuan.php?UsersID=".$UsersID."_".$OrderID;
	    }elseif($Method==2){//支付宝
	        if($rsPay["Payment_AlipayEnabled"]==0 || empty($rsPay["Payment_AlipayPartner"]) || empty($rsPay["Payment_AlipayKey"]) || empty($rsPay["Payment_AlipayAccount"])){
	            die(json_encode(["status"=>0, "msg"=>'商家“支付宝”支付方式未启用或信息不全，暂不能支付！'],JSON_UNESCAPED_UNICODE));
	        }
	        $url = "/pay/alipay/sendto.php?UsersID=".$UsersID."_".$OrderID;
	    }elseif($Method==4){//易宝支付
	        if($rsPay["PaymentYeepayEnabled"]==0 || empty($rsPay["PaymentYeepayAccount"]) || empty($rsPay["PaymentYeepayPrivateKey"]) || empty($rsPay["PaymentYeepayPublicKey"]) || empty($rsPay["PaymentYeepayYeepayPublicKey"])){
	            die(json_encode(["status"=>0, "msg"=>'商家“支付宝”支付方式未启用或信息不全，暂不能支付！'],JSON_UNESCAPED_UNICODE));
	        }
	        $url = "/pay/yeepay/sendto.php?UsersID=".$UsersID."_".$OrderID;
	    }elseif($Method==5){//银联支付
	        if($rsPay["Payment_UnionpayEnabled"]==0 || empty($rsPay["Payment_UnionpayAccount"]) || empty($rsPay["PaymentUnionpayPfx"]) || empty($rsPay["PaymentUnionpayPfxpwd"])){
	            die(json_encode(["status"=>0, "msg"=>'商家“银联支付”支付方式未启用或信息不全，暂不能支付！'],JSON_UNESCAPED_UNICODE));
	        }
	        $url = "/pay/Unionpay/sendto.php?UsersID=".$UsersID."_".$OrderID;
	    }
	    $Data = [ "status"=>1, "url"=>$url ];
	}else{
	    $Data = [ "status"=>0, "msg"=>'在线支付出现错误' ];
	}

	die(json_encode($Data,JSON_UNESCAPED_UNICODE));
}

?>