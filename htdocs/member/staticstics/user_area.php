<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$Data = array();
$DB->Get("user","count(*) as num,User_Province","where Users_ID='".$_SESSION["Users_ID"]."' group by User_Province");
while($rsUser=$DB->fetch_assoc()){
	$provice = array();
	$provice[] = empty($rsUser["User_Province"]) ? "其他" : $rsUser["User_Province"];
	$provice[] = intval($rsUser["num"]);
	$Data[] = $provice;
}
$curid = 5;
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
    var pie_data=<?php echo json_encode($Data,JSON_UNESCAPED_UNICODE);?>;
    $(document).ready(global_obj.chart_pie);
    </script>
    <div class="r_con_wrap">
        <div class="chart"></div>
    </div>
  </div>
</div>
</body>
</html>