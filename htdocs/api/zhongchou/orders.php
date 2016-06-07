<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	
	$UsersID = $_GET["UsersID"];
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/zhongchou/orders.php?UsersID=".$_GET["UsersID"];
	$rsConfig = $DB->GetRs("zhongchou_config","*","where usersid='".$UsersID."'");
	if(!$rsConfig){
		echo '未开通微众筹';
		exit;
	}
}else{
	echo '缺少必要的参数';
	exit;
}

$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$lists = array();
$DB->get("user_order","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type like '%zhongchou_%' and Order_Status=2 order by Order_ID desc");
while($r = $DB->fetch_assoc()){
	$lists[] = $r;
}

require_once('skin/orders.php');
?>