<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$TypeID=empty($_GET["TypeID"])?0:$_GET["TypeID"];	
if(isset($_GET['OpenID'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/user/my/profile/".(empty($TypeID)?'':$TypeID.'/')."?wxref=mp.weixin.qq.com");
	exit;
}else{
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
if(isset($_SESSION[$UsersID."User_ID"])){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
}else{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/my/profile/";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<div id="my_header">
  <div class="face"><?php echo $rsUser["User_HeadImg"] ? '<img src="'.$rsUser["User_HeadImg"].'" />' : '';?></div>
  <ul>
    <li><?php echo $rsUser["User_Name"] ?></li>
    <li>余额: <?php echo $rsUser["User_Money"] ?>元</li>   
  </ul>
</div>
<script language="javascript">$(document).ready(user_obj.user_zhuanzhang_init);</script>
<?php if($rsUser["User_Money"]<=0){?>
<div style="width:100%; height:40px; line-height:40px; font-size:14px; color:#696969; text-align:center">余额不足，请先<a href="/api/<?php echo $UsersID;?>/user/charge/" style="color:#F00"> 充值 </a></div>
<?php }else{?>
<div id="my">
  <form id="user_form">
    <div class="profile">会员转帐</div>
    <div class="input">
      <input type="text" name="Amount" value="" placeholder="转帐金额" notnull />
    </div>
	<div class="input">
      <input type="text" name="No" value="" placeholder="对方会员卡号" notnull />
    </div>
	<div class="input">
      <input type="password" name="PayPassWord" value="" placeholder="支付密码" notnull />
    </div>
	<div style="padding-bottom:8px; font-size:12px; color:red">注：支付密码默认123456</div>
    <div class="submit">
      <input type="button" class="submit_btn" value="提交" />
      <a href="#" onclick="history.go(-1);" class="cancel">取消</a>
	</div>
    <input type="hidden" name="action" value="zhuanzhang" />
  </form>
  
</div>
<?php }?>
<?php require_once('footer.php'); ?>
</body>
</html>