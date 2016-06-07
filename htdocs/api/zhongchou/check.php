<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
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

if(isset($_GET["itemid"])){
	$itemid = $_GET["itemid"];
	$item = $DB->GetRs("zhongchou_project","*","where usersid='".$UsersID."' and itemid=$itemid");
	if(!$item){
		echo '该项目不存在';
		exit;
	}else{
		if($item["fromtime"]>time()){
			echo '该项目未开始';
			exit;
		}
		if($item["totime"]<time()){
			echo '该项目已结束';
			exit;
		}
	}
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["prizeid"])){
	$prizeid = $_GET["prizeid"];
	$prize = $DB->GetRs("zhongchou_prize","*","where usersid='".$UsersID."' and projectid=$itemid and prizeid=$prizeid");
	if(!$prize){
		echo '该项目与支持金额不匹配';
		exit;
	}
}

$_SESSION[$UsersID."HTTP_REFERER"]="/api/zhongchou/check.php?UsersID=".$_GET["UsersID"]."&itemid=".$itemid."&prizeid=".$prizeid;

$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

require_once('skin/check.php');
?>
