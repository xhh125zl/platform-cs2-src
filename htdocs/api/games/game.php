<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["GamesID"])){
	$GamesID=$_GET["GamesID"];
}else{
	echo '缺少必要的参数';
	exit;
}



$rsConfig = $DB->GetRs("games_config","*","where Users_ID='".$UsersID."'");
$rsGames = $DB->GetRs("games","*","where Users_ID='".$UsersID."' and Games_ID=".$GamesID);
if(!$rsGames || !$rsConfig){
	echo "此游戏不存在";
	exit;
}

if($rsGames["Games_Pattern"]=="1"){
	$is_login = 1;
}

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

if($rsGames["Games_IsClose"]=="1"){
	echo '对不起,游戏已关闭! ';
	exit;
}
$JSON = $rsGames['Games_Json'] ? json_decode($rsGames['Games_Json'],true) : array();
require_once($rsGames["Model_ID"].".php");
?>
