<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/virtual.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_pintuan.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/api/pintuan/comm/function.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
ini_set("display_errors","On"); 
$xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) && !empty(isset($GLOBALS['HTTP_RAW_POST_DATA']))?$GLOBALS['HTTP_RAW_POST_DATA']:file_get_contents("php://input");

if($xml){
	include_once("WxPayPubHelper.php");
	$notify = new Notify_pub();
	$notify->saveData($xml);
	$OrderID = $notify->data["out_trade_no"];
	$tid = $notify->data["transaction_id"];
	
	$rsOrder=$DB->GetRs("user_order","Users_ID,User_ID,Order_Status,Order_ID","WHERE Order_Code='".$OrderID."'");
	$Status = $rsOrder["Order_Status"];
	if(!$rsOrder){
		echo "订单不存在";
		exit;
	}
	$UsersID = $rsOrder["Users_ID"];
	$UserID = $rsOrder["User_ID"];
	$Status = $rsOrder["Order_Status"];
	$oid = $rsOrder["Order_ID"];
	$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
	$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
	define("APPID" , trim($rsUsers["Users_WechatAppId"]));
    define("APPSECRET", trim($rsUsers["Users_WechatAppSecret"]));
    define("MCHID",trim($rsPay["PaymentWxpayPartnerId"]));
    define("KEY",trim($rsPay["PaymentWxpayPartnerKey"]));
    define("JS_API_CALL_URL","http://".$_SERVER['HTTP_HOST']."/pay/wxpay2/sendto_pintuan.php?UsersID=".$UsersID."_".$oid);
    define("NOTIFY_URL","http://".$_SERVER['HTTP_HOST']."/pay/wxpay2/pintuan_url.php");
    define("CURL_TIMEOUT",30);
    define("SSLCERT_PATH",$_SERVER["DOCUMENT_ROOT"].$rsPay["PaymentWxpayCert"]);
    define("SSLKEY_PATH",$_SERVER["DOCUMENT_ROOT"].$rsPay["PaymentWxpayKey"]);
	
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	if($notify->checkSign() == TRUE){
		if ($notify->data["return_code"] == "FAIL") {
			echo "【通信出错】";
		}elseif($notify->data["result_code"] == "FAIL"){
			echo "【业务出错】";
		}else{
        	if($Status==1){
        	    $pay_order = new pay_order($DB,$oid);
    			$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$UserID);
    			$info = payDone($UsersID,$oid,"微支付",$tid);
        	    if($info["status"]==1){
        	        echo "SUCCESS";
        	        exit;
        	    }else{
        	        echo $info["msg"];
        	        exit;
        	    }
        	}else{
        	    echo "SUCCESS";
        	    exit;
        	}
		}
	}
}else{
	$OrderID = isset($_GET["OrderID"]) ? $_GET["OrderID"] : 0;
    $rsOrder=$DB->GetRs("user_order","Users_ID,Order_Status","where Order_ID='".$OrderID."'");
	if(!$rsOrder){
		echo "订单不存在";
		exit;
	}
	$UsersID = $rsOrder["Users_ID"];
	$Status = $rsOrder["Order_Status"];
	$info = payDone($UsersID,$OrderID,"微支付",true);
    
	echo '<!doctype html><html><head><title>'.$info['msg'].'</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">';
    echo '<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script><script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script></head><body>';
	echo '<script>layer.msg("'.$info['msg'].'",{icon:1,time:3000},function(){ window.location="'.$info['url'].'";});</script>';
	echo "</body></html>";
	exit;
}
?>