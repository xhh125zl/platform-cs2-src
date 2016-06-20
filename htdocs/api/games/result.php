<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["ResultID"])){
	$ResultID=$_GET["ResultID"];
}else{
	echo '缺少必要的参数';
	exit;
}

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsResult = $DB->GetRs("games_result","*","where ItemID=".$ResultID);
if(!$rsResult){
	echo "非法操作";
	exit;
}
$rsConfig = $DB->GetRs("games_config","*","where Users_ID='".$UsersID."'");
$rsGames = $DB->GetRs("games","*","where Users_ID='".$UsersID."' and Games_ID=".$rsResult["Games_ID"]);
if(!$rsGames || !$rsConfig){
	echo "此游戏不存在";
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsGames['Games_Name'];?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/games/js/games.js'></script>
<link href='/static/api/games/css/games.css' rel='stylesheet' type='text/css' />
</head>

<body>
<script language="javascript">$(document).ready(function(){games_obj.result_init();});</script>
<?php require_once("0".$rsGames["Model_ID"].".php");?>
<div class="blank"></div>
<div class="share_layer"><img src='/static/api/web/images/share.png' /></div>
<img src="/static/api/games/images/cover_<?php echo $rsGames["Model_ID"];?>.jpg" class="shareimg" />
<input type="hidden" name="ShareTitle" value="<?php echo $rsGames["Games_Name"] ?>" />
<?php if($rsGames["Games_AttentionImg"]){?><div id="subscribe"><?php if($rsGames["Games_AttentionLink"]){?><a href="<?php echo $rsGames["Games_AttentionLink"];?>"><?php }?><img src="<?php echo $rsGames["Games_AttentionImg"];?>" /><?php if($rsGames["Games_AttentionLink"]){?></a><?php }?></div><?php }?>
</body>
</html>