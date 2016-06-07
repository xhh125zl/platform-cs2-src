<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(!strpos($_SERVER['REQUEST_URI'],"OpenID=")){
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}else{
	$url_arr = explode("OpenID=",$_SERVER['REQUEST_URI']);
	$endpos = explode("&",$url_arr[1]);
	$_SESSION[$UsersID.'OpenID']=$endpos[0];	
}

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
if(empty($_SESSION[$UsersID."User_ID"]) || !isset($_SESSION[$UsersID."User_ID"])){
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/money_record/?wxref=mp.weixin.qq.com";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}
$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
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
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time()?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<script language="javascript">$(document).ready(user_obj.money_init);</script>
<div id="integral_header">
  <div class="l"><span><?php echo $rsUser["User_Money"] ?></span><br />
    我的余额
  </div>
</div>
<div id="integral_get_use">
  <div class="border_r"><a href="/api/<?php echo $UsersID ?>/user/charge/">充值</a></div>
  <div><a href="/api/<?php echo $UsersID ?>/user/paymoney/">实体店消费</a></div>
</div>
<div id="integral_record">
  <ul>
    <?php $DB->get("user_charge","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Status=1 order by Item_ID desc",20);
	while($rsRecord=$DB->fetch_assoc()){
		echo '<li>【'.date("Y/m/d",$rsRecord["CreateTime"]).'】'.$rsRecord["Operator"].'</li>';
	}?>
  </ul>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>