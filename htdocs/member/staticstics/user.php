<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$starttime = strtotime(date("Y-m-d")." 00:00:00");
$endtime = strtotime(date("Y-m-d")." 23:59:59");

$date = $count_member = array();
for($i=20;$i>0;$i--){
	$fromtime = $starttime-($i-1)*86400;
	$totime = $endtime-($i-1)*86400;
	$date[] = date("m-d", $fromtime);
	$r = $DB->GetRs("user","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."' and User_CreateTime>=".$fromtime." and User_CreateTime<=".$totime);
	$count_member[] = intval($r["num"]);
}
$Data1[] = array(
	"name" => "每天新注册会员数量统计",
	"data" => $count_member
);
$Data = array(
	"count" => $Data1,
	"date" => $date
);
$curid = 4;
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
    <?php require_once($_SERVER["DOCUMENT_ROOT"].'/member/staticstics/stat_menubar.php');?>
    <script type='text/javascript' src='/static/js/plugin/highcharts/highcharts.js'></script>
    <script type='text/javascript' src='/static/member/js/statistics.js' ></script>
    <link href='/static/member/css/statistics.css' rel='stylesheet' type='text/css' />
    <script language="javascript">
    var chart_data=<?php echo json_encode($Data,JSON_UNESCAPED_UNICODE);?>;
    $(document).ready(statistics_obj.stat_init);
    </script>
    <div class="r_con_wrap">
    	<div class="chart_btn"><a href="javascript:void(0);" class="tab_bar">切换<span>曲线图</span></a></div>
        <div class="chart"></div>
    </div>
  </div>
</div>
</body>
</html>