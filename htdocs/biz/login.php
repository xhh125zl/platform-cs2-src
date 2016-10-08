<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_SESSION["BIZ_ID"])){
	header("Location:index.php");
}
if($_POST){
	$rsBiz=$DB->GetRs("biz","*","where Biz_Account='".$_POST["Account"]."' and Biz_PassWord='".md5($_POST["Password"])."'");
	if($rsBiz){
		if($rsBiz["Biz_Status"]==1){
			echo '<script language="javascript">alert("账号已被禁用，无法登录");window.location="login.php";</script>';
		}else{
			$_SESSION["BIZ_ID"]=$rsBiz["Biz_ID"];
			$_SESSION['Biz_Account'] = $rsBiz['Biz_Account'];
			$_SESSION["Users_ID"]=$rsBiz["Users_ID"];

			//查找绑定的会员ID
			if ($rsBiz['UserID']) {
				$rsUser=$DB->GetRs("user", "*", "WHERE User_ID=" . intval($rsBiz['UserID']));
						
				if ($rsUser) {
					$UsersID = $rsBiz['Users_ID'];
					$_SESSION[$UsersID."User_ID"]=$rsUser["User_ID"];
					$_SESSION[$UsersID."User_Name"]=$rsUser["User_Name"];
					$_SESSION[$UsersID."User_Mobile"]=$rsUser["User_Mobile"];
				}
			}
			
			header("Location:index.php");
		}
	}else{
		echo '<script language="javascript">alert("登录账号或密码错误！");window.location="login.php";</script>';
	}
	exit;
}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $SiteName;?>商家后台</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type="text/javascript" src="/static/js/jquery.placeholder.1.3.min.js"></script>
</head>

<body>
<link href='style/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/biz/js/account.js'></script>
<script language="javascript">$(document).ready(account_obj.login_init);</script>
<div id="login">
	<form id="login" method="post" action="?">
    	<div class="login_form">
            <div class="t"></div>
            <div class="form">
            	<div class="login_msg"></div>
                <div class="rows">
                    <span><input name="Account" id="Account" type="text" maxlength="30" value="" autocomplete="off" tabindex="1" placeholder="用户名" notnull /></span>
                    <div class="clear"></div>
                </div>
                <div class="rows">
                    <span><input name="Password" id="Password" type="password" maxlength="20" value="" autocomplete="off" tabindex="2" placeholder="请输入您的密码" notnull /></span>
                    <div class="clear"></div>
                </div>
                <div class="submit"><input type="submit" value="" name="submit"></div>
            </div>
        </div>
	</form>
</div>
</body>
</html>