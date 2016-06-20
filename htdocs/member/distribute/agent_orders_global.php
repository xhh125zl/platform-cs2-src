<?php
basename($_SERVER['PHP_SELF'])=='global.php'&&header('Location:http://'.$_SERVER['HTTP_HOST']);
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$rsAgentod=$DB->GetRs("agent_order","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if(!$rsAgentod){
	echo '<script language="javascript">alert("没人申请！");history.back();</script>';
	exit();
}
$dis_config = Dis_Config::find($_SESSION['Users_ID']);
?>