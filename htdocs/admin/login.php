<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_SESSION["ADMINID"])){
	header("Location:/admin/index.php");
}
if($_POST){
	
	$Flag=true;
	$msg="";
	mysql_query("begin");
	
	$admin=$DB->GetRs("sysusers","*","where Users_Account='".$_POST["Account"]."'");
	if($admin){
		if($admin["Users_Password"] == md5($_POST["Password"])){
			$Data=array(
				'status'=>1
			);
			$_SESSION["ADMINID"]=$admin["Users_ID"];
			$_SESSION["ADMINACCOUNT"]=$admin["Users_Account"];
		}else{
			$Data=array(
				'status'=>2
			);
		}
	}else{
		$Data=array(
			'status'=>2
		);
	}
	if($Flag){
		mysql_query("commit");
	}else{
		mysql_query("roolback");
	}
	echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
	exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $SiteName ? $SiteName.'-' : '';?>后台管理系统</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
<script type="text/javascript" src="/static/js/jquery.placeholder.1.3.min.js"></script>
</head>

<body>
<link href='/static/admin/css/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/admin/js/account.js'></script>
<script language="javascript">$(document).ready(account_obj.login_init);</script>
<div id="login">
	<form>
    	<div class="login_form">
            <div class="t"></div>
            <div class="form">
                <div class="login_msg"></div>
                <div class="rows">
                    <span><input name="Account" id="Account" type="text" maxlength="30" value="" autocomplete="off" tabindex="1" placeholder="用户名" /></span>
                </div>
                <div class="rows">
                    <span><input name="Password" id="Password" type="password" maxlength="20" value="" autocomplete="off" tabindex="2" placeholder="请输入您的密码" /></span>
                </div>
                <div class="submit"><input type="submit" value="" name="submit"></div>
            </div>
        </div>
	</form>
</div>
</body>
</html>