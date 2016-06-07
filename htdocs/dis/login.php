<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_SESSION["Distribute_Users_ID"])){
	header("Location:/dis/");
}
if($_POST){
	//开始事务定义
	$Flag=true;
	$msg="";

	$rsUser=$DB->GetRs("user","*","where User_Mobile='".$_POST["User_Mobile"]."' and User_Password='".md5($_POST["User_Password"])."' and Is_Distribute=1");

	if($rsUser){
		
		$Data=array(
			'status'=>1
		);
		
		$Users_ID =  $rsUser["Users_ID"];
		$_SESSION["Dis_Users_ID"] = $Users_ID;
		$_SESSION["Distribute_ID"] = $rsUser["User_ID"];
		
	}else{
		$Data=array(
			'status'=>2
		);
	}
	
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
	
}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>分销商后台</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/distribute/js/global.js'></script>
<script type="text/javascript" src="/static/js/jquery.placeholder.1.3.min.js"></script>
</head>

<body>
<link href='style/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/distribute/js/account.js'></script>
<script language="javascript">$(document).ready(account_obj.login_init);</script>
<div id="login">
	<form>
    	<div class="login_form">
            <div class="t">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分销商登录</div>
            <div class="form">
            	<div class="login_msg"></div>
                <div class="rows">
                    <span><input name="User_Mobile" id="Account" type="text" maxlength="30" value="" autocomplete="off" tabindex="1" placeholder="用户名" /></span>
                    <div class="clear"></div>
                </div>
                <div class="rows">
                    <span><input name="User_Password" id="Password" type="password" maxlength="20" value="" autocomplete="off" tabindex="2" placeholder="请输入您的密码" /></span>
                    <div class="clear"></div>
                </div>
                <div class="submit"><input type="submit" value="" name="submit"></div>
            </div>
        </div>
	</form>
</div>
</body>
</html>