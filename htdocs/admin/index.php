<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="logout")
	{
		session_unset();
		header("location:login.php");
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SiteName;?></title>
<link href="/static/admin/style.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
</head>

<body>
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.mousewheel.js'></script> 
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.jscrollpane.js'></script>
<link href='/static/js/plugin/jscrollpane/jquery.jscrollpane.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/admin/js/main.js'></script>
<script language="javascript">$(document).ready(main_obj.page_init); window.onresize=main_obj.page_init;</script>
<div id="header">
 <div class="m">
  <div class="logo"><img src="<?php echo $SiteLogo ? $SiteLogo : '/static/admin/images/main/header_logo.png';?>" /></div>
 </div>
</div>
<div class="b80"></div>
<div id="main">
 <div class="m">
  <div class="menu">
    <div class="menu_top"></div>
    <ul>
     <li class="ico-1"><a href="oem/index.php" class="current" target="iframe">系统设置</a></li>
	 <li class="ico-2"><a href="slide/index.php" target="iframe">首页幻灯片设置</a></li>
     <li class="ico-3"><a href="industry/index.php" target="iframe">行业管理</a></li>
     <li class="ico-4"><a href="users/index.php" target="iframe">商家管理</a></li>
     <li class="ico-5"><a href="user/index.php" target="iframe">会员管理</a></li>
     <li class="ico-6"><a href="orders/index.php" target="iframe">订单管理</a></li>
     <li class="ico-9"><a href="announce/index.php" target="iframe">公告管理</a></li>
     <li class="ico-10"><a href="guide/index.php" target="iframe">操作指南</a></li>
	 <li class="ico-10"><a href="update/index.php" target="iframe">系统</a></li>
     <li class="ico-7"><a href="my/index.php" target="iframe">修改密码</a></li>
     <li class="ico-11"><a href="oem/renewal_record.php" target="iframe">续费管理</a></li>
	 <li class="ico-12"><a href="record/send_record.php" target="iframe">短信记录</a></li>
     <li class="ico-8"><a href="index.php?action=logout" target="iframe">退出登录</a></li>
    </ul>
  </div>
  <div class="iframe">
    <iframe src="oem/index.php" name="iframe" frameborder="0" scrolling="auto"></iframe>
  </div>
  <div class="clear"></div>
 </div>
</div>
<div id="footer">
  <div class="oem"><?php echo $Copyright;?></div>
</div>
</body>
</html>