<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $header_title; ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/web/skin/default/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/web/skin/<?php echo $rsConfig['Skin_ID'];?>/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/web/js/web.js?t=<?php echo time();?>'></script>
<?php if($rsConfig['Animation']==1){?>
<script type='text/javascript' src='/static/js/plugin/animation/snow.js'></script>
<?php }?>
<?php if($rsConfig['Animation']==2){?>
<script type='text/javascript' src='/static/js/plugin/animation/fireworks.js'></script>
<?php }?>
<script language="javascript">
var links=new Array('/api/<?php echo $UsersID ?>/web/index/');
$(document).ready(web_obj.page_init);
</script>
</head>
<body>
<div id="web_page_contents_loading">
	<img src="/static/api/web/images/loading.gif" />
</div>
<div id="header" class="wrap">
	<ul>
		<li class="home first"><a href="/api/<?php echo $UsersID ?>/web/"></a></li>
		<li class="back"><a href="javascript:;history.back();"></a></li>
        <?php if($rsConfig['CallEnable']==1 && $rsConfig['CallPhoneNumber']<>''){?>
		<li class="tel"><a href="tel:<?php echo $rsConfig['CallPhoneNumber'];?>" class="autotel"></a></li>
		<?php }?>
		<li class="lbs"><a href="/api/<?php echo $UsersID ?>/web/lbs/"></a></li>
	</ul>
</div>