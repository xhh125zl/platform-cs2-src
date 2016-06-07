<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
	$rsweixin=$DB->GetRs("Users","*","where Users_ID='".$UsersID."'");
}else{
	echo '缺少必要的参数';
	exit;
}

if(empty($_SESSION[$UsersID."HTTP_REFERER"])){
	$HTTP_REFERER="/api/".$UsersID."/user/";
}else{
	$HTTP_REFERER=$_SESSION[$UsersID."HTTP_REFERER"];

}

$user_url = '/api/'.$UsersID.'/user/';
$rsConfig = shop_config($UsersID);
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
$owner = get_owner($rsConfig,$UsersID);


if($owner['id'] != '0'){
	$user_url = $user_url.$owner['id'].'/';
}
 
if(empty($_SESSION[$UsersID."User_ID"])){
	
	 
	if(empty($_SESSION[$UsersID."OpenID"])){
		$_SESSION[$UsersID."OpenID"]=0;
	}
	
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_OpenID='".$_SESSION[$UsersID."OpenID"]."'");
	
	if($rsUser){
	
		$_SESSION[$UsersID."User_ID"]=$rsUser["User_ID"];
		$_SESSION[$UsersID."User_Name"]=$rsUser["User_Name"];
		$_SESSION[$UsersID."User_Mobile"]=$rsUser["User_Mobile"];
		$_SESSION[$UsersID."HTTP_REFERER"]="";
		
		//如果是砍价来源的url
		if(isset($_SESSION[$UsersID."is_kan"])){
			$HTTP_REFERER .= $rsUser["User_ID"].'/';
		}
		header("location:".$HTTP_REFERER);
		exit;
	}else{
		if($_POST){
			if(empty($_POST["Mobile"])){
				echo '<script language="javascript">alert("手机号码必须填写！");history.back();</script>';
				exit();
			}
			if(empty($_POST["Password"])){
				echo '<script language="javascript">alert("登录密码必须填写！");history.back();</script>';
				exit();
			}
			$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_Mobile='".$_POST["Mobile"]."'");
			
			if($rsUser){
				if(md5($_POST["Password"])==$rsUser["User_Password"]){
					if($rsUser["User_Status"]){
						$_SESSION[$UsersID."User_ID"]=$rsUser["User_ID"];
						$_SESSION[$UsersID."User_Name"]=$rsUser["User_Name"];
						$_SESSION[$UsersID."User_Mobile"]=$rsUser["User_Mobile"];
						$_SESSION[$UsersID."HTTP_REFERER"]="";

						//如果是砍价来源的url	
						if(isset($_SESSION[$UsersID.'is_kanjia'])){
							$HTTP_REFERER .= $_SESSION[$UsersID."User_ID"].'/';
						}

						header("location:".$HTTP_REFERER);
						exit;
					}else{
						echo '<script language="javascript">alert("帐号锁定，禁止登录！");history.back();</script>';
						exit();
					}
				}else{
					echo '<script language="javascript">alert("登录密码错误！");history.back();</script>';
					exit();
				}
			}else{
				echo '<script language="javascript">alert("手机号码不存在！请重新输入！");history.back();</script>';
				exit();
			}
		}
	}
}

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
	exit;
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
<script type='text/javascript' src='/static/api/js/user.js?t=<?php echo time();?>'></script>
</head>

<body>
<style type="text/css">html, body{background:#fff;}</style>
<div id="header_unlogin" class="wrap">
  <ul>
    <li class="home first"><a href="/api/<?php echo $UsersID ?>/user/"></a></li>
    <li class="tel"><!--<a href="tel:"></a>--></li>
    <li class="lbs"><!--<a ajax_url="/api/<?php echo $UsersID ?>/user/lbs/"></a>--></li>
  </ul>
</div>
<script language="javascript">//$(document).ready(user_obj.user_login_init);</script>
<form action="" method="post" id="user_form">
  <div class="tips">您还未登录，请先登录！</div>
  <h1>没有帐号</h1>
  <div class="reg"><a href="<?=$user_url?>create/">注册只需10秒</a></div>
  <h1>已有帐号</h1>
  <div class="input">
    <input type="tel" name="Mobile" value="" maxlength="11" placeholder="手机号码" pattern="[0-9]*" notnull />
  </div>
  <div class="input">
    <input type="password" name="Password" value="" maxlength="16" placeholder="登录密码" notnull />
  </div>
  <div class="submit">
    <input name="提交" type="submit" value="立即登录" />
  </div>
</form>
</body>
</html>