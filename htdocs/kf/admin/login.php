<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_SESSION["KFA_ID"])){
	header("Location:index.php");
}
if($_POST){
	$rsKf=$DB->GetRs("kf_account","*","where Account_Name='".$_POST["Account"]."' and Account_PassWord='".md5($_POST["Password"])."'");
	if($rsKf){
		$Data=array(
			'status'=>1
		);
		$_SESSION["KFA_ID"]=$rsKf["Account_ID"];
		$_SESSION["KFA_Name"]=$rsKf["Account_Name"];
	}else{
		$Data=array(
			'status'=>2
		);
	}
	echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
	exit;
}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微信公众平台客服系统</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type="text/javascript" src="/static/js/jquery.placeholder.1.3.min.js"></script>
</head>

<body>
<link href='/kf/css/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/kf/js/login.js'></script>
<script language="javascript">$(document).ready(login_obj.login_init);</script>
<div id="login">
	<form>
    	<div class="login_form">
            <div class="t"></div>
            <div class="form">
            	<div class="login_msg"></div>
                <div class="rows">
                    <span><input name="Account" id="Account" type="text" maxlength="30" value="" autocomplete="off" tabindex="1" placeholder="用户名" /></span>
                    <div class="clear"></div>
                </div>
                <div class="rows">
                    <span><input name="Password" id="Password" type="password" maxlength="20" value="" autocomplete="off" tabindex="2" placeholder="请输入您的密码" /></span>
                    <div class="clear"></div>
                </div>
                <div class="submit"><input type="submit" value="" name="submit"></div>
            </div>
        </div>
	</form>
</div>
</body>
</html>