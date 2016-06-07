<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["KFA_ID"]))
{
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
$expiretime = time()-86400;
$rsKF=$DB->GetRs("kf_account","*","where Account_ID='".$_SESSION["KFA_ID"]."'");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SiteName;?>客服系统</title>
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.mousewheel.js'></script> 
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.jscrollpane.js'></script>
<link href='/static/js/plugin/jscrollpane/jquery.jscrollpane.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/kf/js/main.js'></script>
<link href="/kf/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
var UsersID = '<?php echo $rsKF["Users_ID"];?>';
var KfId=<?php echo $_SESSION["KFA_ID"];?>;
var chatto=0;
$(document).ready(function(){
	main_obj.page_init();
	main_obj.showUser();
});
</script>
</head>
<body>
<div id="header">
  <div class="logo"><img src="/kf/images/main/header_logo.png" /></div>
  <ul>
    <li class="logout"><a href="?action=logout">退出登录</a></li>
  </ul>
</div>
<div id="main">
  <div class="menu">
    <dl>
      <dt group="wechat" class="cur"><span class="img0"></span>当前对话</dt>
      <dd style="display:block" id="UserList">
      	<?php
		 $first = $DB->GetRs("kf_message","*","where Message_LastTime>=".$expiretime." and KF_Account='".$rsKF["Account_Name"]."' order by Message_ID ASC");
         $DB->Get("kf_message","*","where Message_LastTime>=".$expiretime." and KF_Account='".$rsKF["Account_Name"]."' order by Message_ID ASC");
		 while($r=$DB->fetch_assoc()){
		?>
      	<div><a href="chat.php?UserId=<?php echo $r["Message_ID"];?>" target="iframe">用户<?php echo $r["Message_ID"];?></a></div>
        <?php
		 }
		?>
      </dd>
    </dl>
  </div>
  <div class="iframe">
    <iframe src="chat.php?UserId=<?php echo $first["Message_ID"];?>" name="iframe" id="chatto" frameborder="0" scrolling="auto"></iframe>
  </div>
  <div class="clear"></div>
</div>
<div id="footer">
  <div class="oem"><?php echo $Copyright;?></div>
</div>
</body>
</html>