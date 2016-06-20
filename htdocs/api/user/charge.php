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
<script language="javascript">$(document).ready(user_obj.user_charge_init);</script>
<div id="my">
  <form id="user_form">
    <div class="profile">会员充值</div>
    <div class="input">
      <input type="text" name="Amount" value="" placeholder="充值金额" notnull />
    </div>
	<div class="charge_type">
	  <ul>
          <li>
            <input type="radio" name="Operator" value="1" id="wzf" checked />
            <label for="wzf"><strong>微支付</strong></label>
          </li>
          <li>
            <input type="radio" name="Operator" value="2" id="zfb" />
            <label for="zfb"><strong>支付宝</strong></label>
          </li>
	  </ul>
	</div>
    <div class="submit">
      <input type="button" class="submit_btn" value="提交" />
      <!--<a href="#" onclick="history.go(-1);" class="cancel">取消</a>-->
	  <a href="../" class="cancel">取消</a>
	</div>
    <input type="hidden" name="action" value="charge" />
  </form>
  
</div>
<?php require_once('footer.php'); ?>
</body>
</html>