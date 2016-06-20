<?php
basename($_SERVER['PHP_SELF'])=='global.php'&&header('Location:http://'.$_SERVER['HTTP_HOST']);
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["BIZ_ID"])){
	header("location:/biz/login.php");
}
$rsBiz=$DB->GetRs("biz","*","where Biz_ID=".$_SESSION["BIZ_ID"]);
if(!$rsBiz){
	echo '<script language="javascript">alert("此商家不存在！");history.back();</script>';
	exit();
}
$rsGroup = $DB->GetRs("biz_group","Group_Name,Group_IsStore","where Group_ID=".$rsBiz["Group_ID"]);
if($rsGroup){
	$IsStore = $rsGroup["Group_IsStore"];
}else{
	$IsStore = 0;
}
?>