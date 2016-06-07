<?php

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$VotesID = isset($_GET["VotesID"]) ? $_GET["VotesID"] : 0;
$rsVotes = $DB->GetRs("votes","*","where Votes_ID=".$VotesID);
if(!$rsVotes){
	echo "该信息不存在";
	exit;
}
$Data = $DATE = $COUNT = array();

$DB->get("votes_item","*","where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID." order by Item_Votes desc, Item_Sorts asc, Item_ID asc");
while($r=$DB->fetch_assoc()){
	$DATE[] = $r["Item_Title"];
	$COUNT[0]["data"][] = intval($r["Item_Votes"]);
}
$COUNT[0]["name"] = $rsVotes["Votes_Title"];
$Data["date"] = $DATE; 
$Data["count"] = $COUNT;
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
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
        <ul>
            <li><a href="config.php">基本设置</a></li>
        	<li class="cur"><a href="votemanage.php">投票管理</a></li>
        </ul>
	</div>
    <script type='text/javascript' src='/static/js/plugin/highcharts/highcharts.js'></script>
    <script type='text/javascript' src='/static/member/js/votes.js' ></script>
    <link href='/static/member/css/votes.css' rel='stylesheet' type='text/css' />
    <script type="text/javascript">
		global_obj.chart_par.height='500';
		global_obj.chart_par.themes='bar';
		global_obj.chart_par.valueSuffix='票';
		var chart_data=<?php echo json_encode($Data,JSON_UNESCAPED_UNICODE);?>;
		//var chart_data={"date":["赞成","不表态","不赞成","中立"],"count":[{"data":[1,1,0,0],"name":"早恋调查"}]};
		$(document).ready(global_obj.chart);
	</script>
    <div id="vote" class="r_con_wrap">
        <div class="control_btn"><a href="votemanage.php" class="btn_cancel">返回</a></div>
        <div class="chart"></div>
        <div class="blank20"></div>
    </div>
  </div>
</div>
</body>
</html>