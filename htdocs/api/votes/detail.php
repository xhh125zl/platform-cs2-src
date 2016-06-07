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

if(empty($_SESSION[$UsersID.'subcribe'])){
	$_SESSION[$UsersID.'subcribe'] = 0;
}
	
if(!empty($_SESSION[$UsersID.'OpenID'])){
	$r = $DB->GetRs("http_raw_post_data","count(*) as num","where HTTP_RAW_POST_DATA LIKE '%".$_SESSION[$UsersID.'OpenID']."%' and HTTP_RAW_POST_DATA LIKE '%subscribe%'");
	if($r["num"]>0){
		$_SESSION[$UsersID.'subcribe'] = 1;
	}else{
		$_SESSION[$UsersID.'subcribe'] = 0;
	}	
}


$rsVotes=$DB->GetRs("votes","*","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Votes_StartTime<=".time()." and Votes_EndTime>=".time());
$rsItem = $DB->GetRs("votes_item","*","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Item_ID=".$ItemID);
if(!$rsVotes || !$rsItem){
	echo "该信息不存在";
	exit;
}

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_config["title"] = $rsItem["Item_Title"];
	$share_config["desc"] = $rsItem["Item_Title"];
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].($rsItem["Item_ImgPath"] ? $rsItem["Item_ImgPath"] : '/static/api/images/cover_img/votes.jpg');
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
<title><?php echo $rsItem["Item_Title"];?> - <?php echo $rsVotes["Votes_Title"];?></title>
<link href='/static/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/css/votes.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/votes.js?t=<?php echo time();?>'></script>
<script language="javascript">
var UsersID = '<?php echo $UsersID;?>';
var subcribe = <?php echo $_SESSION[$UsersID.'subcribe'];?>;
var keyword = '<?php echo $rsVotes["Votes_Keyword"];?>';
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
    <div class="detail">
        <div class="photo m1"><img src="<?php echo $rsItem["Item_ImgPath"];?>" /></div>
        <div class="wrap m2 clean">
            <div class="vote_name"><?php echo $rsItem["Item_Title"];?></div>
            <div class="vote_info">
                <span class="vi3"><img src="/static/api/images/vote/heart.png" /></span>
                <span class="vi2"><?php echo $rsItem["Item_Votes"];?>票</span>
            </div>
        </div>
    </div>
    <div class="desc"><?php echo $rsItem["Item_Intro"];?></div>
    <div class="voteBtn m3" VId="<?php echo $VotesID;?>" LId="<?php echo $ItemID;?>"><span>投票</span></div>
</div>
<?php if(!empty($share_config)){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_config["appId"];?>",   
		   timestamp:<?php echo $share_config["timestamp"];?>,
		   nonceStr:"<?php echo $share_config["noncestr"];?>",
		   url:"<?php echo $share_config["url"];?>",
		   signature:"<?php echo $share_config["signature"];?>",
		   title:"<?php echo $share_config["title"];?>",
		   desc:"<?php echo str_replace(array("\r\n", "\r", "\n"), "", $share_config["desc"]);?>",
		   img_url:"<?php echo $share_config["img"];?>",
		   link:""
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
</body>
</html>