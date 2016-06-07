<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	//$_SESSION[$UsersID."HTTP_REFERER"]="/api/zhongchou/index.php?UsersID=".$_GET["UsersID"];
	$UsersID = $_GET["UsersID"];
	$rsConfig = $DB->GetRs("zhongchou_config","*","where usersid='".$UsersID."'");
	if(!$rsConfig){
		echo '未开通微众筹';
		exit;
	}
}else{
	echo '缺少必要的参数';
	exit;
}
require_once('../share.php');
$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$projects = array();
$DB->get("zhongchou_project","*","where usersid='".$UsersID."' order by itemid desc");
while($r = $DB->fetch_assoc()){
	$lists[] = $r;
}
require_once('skin/index.php');
?>
