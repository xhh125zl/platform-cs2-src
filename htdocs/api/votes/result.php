<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["VotesID"])){
	$VotesID=$_GET["VotesID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["ItemID"])){
	$ItemID=$_GET["ItemID"];
}else{
	echo '缺少必要的参数';
	exit;
}

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsVotes=$DB->GetRs("votes","*","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Votes_StartTime<=".time()." and Votes_EndTime>=".time());
$rsItem = $DB->GetRs("votes_item","*","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Item_ID=".$ItemID);
if(!$rsVotes || !$rsItem){
	echo "该信息不存在";
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsVotes["Votes_Title"];?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/votes.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/votes.js'></script>
<script language="javascript">
var UsersID = '<?php echo $UsersID;?>';
$(document).ready(votes_obj.votes_init);
</script>
<?php if($rsVotes["Votes_BgColor"]){?>
<style type="text/css">body,html{background:<?php echo $rsVotes["Votes_BgColor"];?>;}</style>
<?php }?>
</head>

<body>
<div id="main" >
    <div class="banner">
    	<div class="home"><a href="/api/<?php echo $UsersID;?>/votes/<?php echo $VotesID;?>/"><img src="/static/api/images/vote/home.png" /></a></div>
        <div class="title m1"><span><?php echo $rsVotes["Votes_Title"];?></span></div>
	</div>
    <div class="section3 m1 clean">
    <?php
	$i=0;
	$DB->get("votes_item","*","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." order by Item_Votes desc, Item_Sorts asc, Item_ID asc");
	while($r=$DB->fetch_assoc()){
	?>
    	<?php if($i>0){?><div class="item3 borderB"></div><?php }?>
    	<div class="item1"><?php echo $i+1;?>. <?php echo $r["Item_Title"];?></div>
		<div class="item2">
			<div class="porcessbg"><div class="porcess" style="width:<?php echo $rsVotes["Votes_Votes"]==0 ? 0 : ($r["Item_Votes"]*100/$rsVotes["Votes_Votes"])?>%;"></div></div>
			<div class="vote_num">
				<span<?php echo $r["Item_ID"]==$ItemID ? ' class="fc_red"' : ''?>><?php echo $r["Item_Votes"];?>票</span>
			</div>
			<div class="clean"></div>
		</div>
	<?php $i++;}?>
		<div class="item3"></div>
    </div>
    <div class="h"></div>
</div>
</body>
</html>