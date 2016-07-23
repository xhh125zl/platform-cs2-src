<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

//拼团配置信息
$rsConfig = $DB->GetRs('pintuan_config','*','where Users_ID = "'.$UsersID.'"');
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
 
?>