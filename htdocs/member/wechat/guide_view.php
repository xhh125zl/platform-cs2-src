<?php

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$GuideID = isset($_GET["GuideID"]) ? intval($_GET["GuideID"]) : 0;
$Item = $DB->GetRs("guide","*","where Guide_ID=".$GuideID);
if(!$Item){
	echo "无相关信息！";
	exit;
}
$Data = array(
	"Guide_Hits"=>($Item["Guide_Hits"]+1)
);
$DB->Set("guide",$Data,"where Guide_ID=".$GuideID);

$Item["Guide_Content"] = str_replace('&quot;','"',$Item["Guide_Content"]);
$Item["Guide_Content"] = str_replace("&quot;","'",$Item["Guide_Content"]);
$Item["Guide_Content"] = str_replace('&gt;','>',$Item["Guide_Content"]);
$Item["Guide_Content"] = str_replace('&lt;','<',$Item["Guide_Content"]);
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
#guide h2{margin:0px; padding:0px; font-size:24px; height:44px; line-height:44px; text-align:center}
#guide .guide_other{height:30px; line-height:30px; font-size:12px; color:#696969; font-family:"宋体"; text-align:center}
#guide .guide_content{line-height:22px}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="guide.php">操作指南列表</a></li>
      </ul>
    </div>
    <div id="guide" class="r_con_wrap">
      <h2><?php echo $Item["Guide_Title"];?></h2>
      <div class="guide_other">发布时间：<?php echo date("Y-m-d H:i:s",$Item["Guide_CreateTime"]);?>&nbsp;&nbsp;浏览次数：<?php echo $Item["Guide_Hits"];?></div>
      <div class="guide_content"><?php echo $Item["Guide_Content"];?></div>
    </div>
  </div>
</div>
</body>
</html>