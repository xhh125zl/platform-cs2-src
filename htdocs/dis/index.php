<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(empty($_SESSION["Distribute_ID"]))
{
	header("location:/dis/login.php");
}

if(isset($_GET["action"]))
{
	if($_GET["action"]=="logout")
	{
	    unset($_SESSION['Dis_Users_ID']);
		unset($_SESSION['Distribute_ID']);
		header("location:/dis/login.php");
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>分销商后台</title>
<link href="/static/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="/static/style.css" rel="stylesheet" type="text/css" />

<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.mousewheel.js'></script> 
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.jscrollpane.js'></script>
<link href='/static/js/plugin/jscrollpane/jquery.jscrollpane.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/main.js'></script> 
<script language="javascript">$(document).ready(main_obj.page_init); window.onresize=main_obj.page_init;</script>
<div id="header">
  <div class="logo"><img src="/uploadfiles/1001/image/554097f011.png" /></div>
  <ul>
   	  <li class="ico-0"><a href="/dis/self.php" target="iframe">分销账号</a></li>
      <li class="ico-5"><a id='logout' href="?action=logout">退出登录</a></li>
  </ul>
</div>
<div id="main">
  <div class="menu">
    <dl>
   
    
    <dt group="self">
	  <a href="/dis/self.php" target="iframe">分销账号</a>  
	</dt>
	
    <dt group="posterity">
		<a href="/dis/posterity.php" target="iframe">下属分销商</a>
	</dt>
     
	<dt group="record">
		<a href="/dis/distribute_record.php" target="iframe">分销记录</a>
	</dt>
	
	<dt group="withdraw">
		<a href="/dis/withdraw_record.php" target="iframe">提现记录</a>
	</dt> 
	 
     
      
     
    </dl>
  </div>
  <div class="iframe">
    <iframe src="self.php" name="iframe" frameborder="0" scrolling="auto"></iframe>
  </div>
  <div class="clear"></div>
</div>
<div id="footer">
  <div class="oem"><?php echo $Copyright;?></div>
</div>
</body>
</html>