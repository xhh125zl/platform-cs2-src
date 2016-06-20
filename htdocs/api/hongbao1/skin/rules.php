<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>活动规则 - <?php echo $rsConfig["name"];?></title>
<link href='/static/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/hongbao/css/hongbao.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
</head>

<body>
<div class="rules_top"><img src="/static/api/hongbao/images/rules.jpg" /></div>
<div class="rules_time">
 <p>活动开始时间：<?php echo date("Y-m_d H:i:s", $rsConfig["fromtime"]);?></p>
 <p>活动结束时间：<?php echo date("Y-m_d H:i:s", $rsConfig["totime"]);?></p>
</div>
<div class="rules">
 <?php echo $rsConfig["rules"];?>
</div>
<a href="/api/<?php echo $UsersID;?>/hongbao/" class="goback">返回</a>
</body>
</html>