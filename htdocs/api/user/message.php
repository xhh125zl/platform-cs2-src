<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}	
if(isset($_GET['OpenID'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/user/message/?wxref=mp.weixin.qq.com");
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
if(isset($_SESSION[$UsersID."User_ID"])){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
}else{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/message/?wxref=mp.weixin.qq.com";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}
$lists = array();
$DB->get("user_message","*","where Users_ID='".$UsersID."'");
while($r=$DB->fetch_assoc()){
	$lists[] = $r;
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
<script language="javascript">$(document).ready(user_obj.message_init);</script>
<div id="message">
  <div class="t"><img src="/static/api/images/user/message.png" />系统消息</div>
  <?php
	foreach($lists as $rsMessage){
		$item = $DB->GetRs("user_message_record","*","where Message_ID=".$rsMessage["Message_ID"]." and User_ID=".$_SESSION[$UsersID."User_ID"]);
		echo '<div class="list'.(empty($item['User_ID'])?' not_read':' is_read').'" MessageID="'.$rsMessage["Message_ID"].'" Display="0">
		<h1>'.$rsMessage["Message_Title"].'</h1>
		<h2>'.date("Y-m-d H:i:s",empty($item["Record_CreateTime"]) ? $rsMessage["Message_CreateTime"] : $item["Record_CreateTime"]).'</h2>
		<div>'.(empty($item['User_ID'])?'未读':'').'</div>
		</div>';
	}?>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>