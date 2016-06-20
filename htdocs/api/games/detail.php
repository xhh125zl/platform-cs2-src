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
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig = $DB->GetRs("games_config","*","where Users_ID='".$UsersID."'");
$rsGames = $DB->GetRs("games","*","where Users_ID='".$UsersID."' and Games_ID=".$GamesID);
if(!$rsGames || !$rsConfig){
	echo "此游戏不存在";
	exit;
}
$JSON = $rsGames['Games_ScoreRules'] ? json_decode($rsGames['Games_ScoreRules'],true) : array();
$arrays = array();
$arrays[1] = array('10-50个','50-100个','100-150个','150-200个','200个以上');
$arrays[2] = array('5-10次','10-20次','20-35次','35-50次','50次以上');
$arrays[3] = array('1000-4000分','4000-10000分','10000-30000分','30000-40000分','40000以上');
$arrays[4] = array('10-60秒','60-120秒','120-240秒','240-300秒','300秒以上');
$arrays[5] = array('10-20张','20-30张','30-40张','40-50张','50张以上');
$rulesitem = $arrays[$rsGames["Model_ID"]];
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
<script language="javascript">
$(document).ready(function(){
	games_obj.detail_init(<?php echo $rsGames["Model_ID"];?>);
});
var GameMethod=<?php echo $rsGames["Games_Pattern"];?>;
</script>
<div id="header">
	<?php if(!empty($rsConfig["Games_Logo"])){?><div><a href="/api/<?php echo $UsersID;?>/games/"><img src="<?php echo $rsConfig["Games_Logo"];?>"></a></div><?php }?>
</div>

<div id="detail">
	<?php if($rsGames["Games_IsClose"]==1){?>
	<div class="nostart"><div>游戏已关闭</div></div>
	<?php }else{?>
	<div class="start" rel="/api/<?php echo $UsersID;?>/games/game/<?php echo $GamesID;?>/"><div>开始游戏</div></div>
	<?php }?>
	<div class="img"><img src="/static/api/games/images/cover_<?php echo $rsGames["Model_ID"];?>.jpg"></div>
	<div class="blank3"></div>
	<div class="box">
		<h1>游戏规则</h1>
		<div class="txt">
			<div class="rule"><?php echo $rsGames["Games_Rules"];?></div>
		</div>
	</div>
	<div class="blank9"></div>
	<?php if(!empty($rsGames["Games_ScoreRules"]) && $rsGames["Games_Pattern"]=="1"){?>
	<div class="box">
		<h1>奖励规则</h1>
		<div class="txt">
			<div class="integral">
			 <ul>
			  <?php foreach($rulesitem as $k=>$v){?>
			   <li class="i<?php echo $k;?>"><?php echo $v;?>：<?php echo empty($JSON[$k]) ? 0 : $JSON[$k];?>个积分</li>
			  <?php }?>
			 </ul>			
			</div>
		</div>
	</div>
	<div class="blank9"></div>
	<?php }?>
</div>
</body>
</html>