<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsConfig = $DB->GetRs("games_config","*","where Users_ID='".$UsersID."'");
if(!$rsConfig){
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
<title><?php echo $rsConfig["Games_Name"];?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/games/js/games.js'></script>
<link href='/static/api/games/css/games.css' rel='stylesheet' type='text/css' />
</head>

<body>
<div id="header">
	<?php if(!empty($rsConfig["Games_Logo"])){?><div><a href="/api/<?php echo $UsersID;?>/games/"><img src="<?php echo $rsConfig["Games_Logo"];?>"></a></div><?php }?>
</div>
<div id="game_list">
<?php
$DB->get("games","*","where Users_ID='".$UsersID."'");
while($r=$DB->fetch_assoc()){
?>
  <div class="item">
		<div class="img"><a href="/api/<?php echo $UsersID;?>/games/detail/<?php echo $r["Games_ID"];?>/?start=1"><img src="/static/api/games/images/cover_<?php echo $r["Model_ID"];?>.jpg"></a></div>
	<div class="clear"></div>
	  <div class="go">
		  <div><?php echo $r["Games_Name"]?></div>
		  <a href="/api/<?php echo $UsersID;?>/games/detail/<?php echo $r["Games_ID"];?>/?start=1">开始游戏</a>
	  </div>
	</div>
<?php }?>
</div>
</body>
</html>