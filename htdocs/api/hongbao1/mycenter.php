<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');


if(isset($_GET["UsersID"])){
	if(!strpos($_SERVER['REQUEST_URI'],"OpenID=")){
		$UsersID = $_GET["UsersID"];
		$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$UsersID."'");
		if(!$rsConfig){
			echo '未开通抢红包';
			exit;
		}
	}else{
		header("location:/api/hongbao/mycenter.php?UsersID=".$_GET["UsersID"]."&wxref=mp.weixin.qq.com");
	}
}else{
	echo '缺少必要的参数';
	exit;
}
$_SESSION[$UsersID."HTTP_REFERER"]="/api/hongbao/mycenter.php?UsersID=".$_GET["UsersID"];
$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");

$is_login = 1;
$shopConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$shopConfig = array_merge($shopConfig,$dis_config);
$owner = get_owner($shopConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$myinfo = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
$total = $DB->GetRs("hongbao_act","sum(money) as money","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]);
$money = $total["money"];
$total = $DB->GetRs("hongbao_act","sum(money) as money","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]." and status=1");
$money_1 = $total["money"];
$total = $DB->GetRs("hongbao_act","sum(money) as money","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]." and status=0");
$money_0 = empty($total["money"]) ? "0.00" : $total["money"];
require_once('skin/mycenter.php');
?>
