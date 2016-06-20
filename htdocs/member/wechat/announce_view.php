<?php

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$AnnounceID = isset($_GET["AnnounceID"]) ? intval($_GET["AnnounceID"]) : 0;
$Item = $DB->GetRs("announce","*","where Announce_ID=".$AnnounceID);
if(!$Item){
	echo "无相关信息！";
	exit;
}
$Category = $DB->GetRs("announce_category","*","where Category_ID=".$Item["Category_ID"]);
$Data = array(
	"Announce_Hits"=>($Item["Announce_Hits"]+1)
);
$DB->Set("announce",$Data,"where Announce_ID=".$AnnounceID);
$Item["Announce_Content"] = str_replace('&quot;','"',$Item["Announce_Content"]);
$Item["Announce_Content"] = str_replace("&quot;","'",$Item["Announce_Content"]);
$Item["Announce_Content"] = str_replace('&gt;','>',$Item["Announce_Content"]);
$Item["Announce_Content"] = str_replace('&lt;','<',$Item["Announce_Content"]);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
#announce h2{margin:0px; padding:0px; font-size:24px; height:44px; line-height:44px; text-align:center}
#announce .announce_other{height:30px; line-height:30px; font-size:12px; color:#696969; font-family:"宋体"; text-align:center}
#announce .announce_content{line-height:22px}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="announce.php">公告列表</a></li>
      </ul>
    </div>
    <div id="announce" class="r_con_wrap">
      <h2><?php echo $Item["Announce_Title"];?></h2>
      <div class="announce_other"><?php echo empty($Category["Category_Name"]) ? "" : "所属分类：".$Category["Category_Name"]."&nbsp;&nbsp;";?>发布时间：<?php echo date("Y-m-d H:i:s",$Item["Announce_CreateTime"]);?>&nbsp;&nbsp;浏览次数：<?php echo $Item["Announce_Hits"];?></div>
      <div class="announce_content"><?php echo $Item["Announce_Content"];?></div>
    </div>
  </div>
</div>
</body>
</html>