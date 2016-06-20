<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/verifycode/verifycode.class.php');

if(isset($_SESSION["Users_ID"])){
	header("Location:/member/");
}

if(isset($_GET["action"])){
	if($_GET["action"] == "verifycode" && isset($_GET["t"])){
		wzwcode::$useNoise = true;
		wzwcode::$useCurve = true;
		wzwcode::entry();
	}
}
if($_POST){
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$verifycode = wzwcode::check($_POST["VerifyCode"]);
	if(!$verifycode){
		$Data=array(
			'status'=>4
		);
	}else{
		$rsUsers=$DB->GetRs("users","*","where Users_Account='".$_POST["Account"]."' and Users_Password='".md5($_POST["Password"])."'");
		if($rsUsers){
			if($rsUsers["Users_Status"]==0){
				$Data=array(
					'status'=>0
				);
			}else{
				if($rsUsers["Users_ExpireDate"]<time()){
					$Data=array(
						'status'=>2
					);
				}else{
					$Data=array(
						'status'=>1
					);
					$_SESSION["Users_ID"]=$rsUsers["Users_ID"];
					$_SESSION["Users_WechatToken"]=$rsUsers["Users_WechatToken"];
					$_SESSION["Users_Account"]=$rsUsers["Users_Account"];
				}
			}
		}else{
			$Data=array(
				'status'=>3
			);
		}
		if($Flag){
			mysql_query("commit");
		}else{
			mysql_query("roolback");
		}
	}
	echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
	exit;
}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $SiteName;?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<link href='/static/member/css/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/account.js'></script>
<script language="javascript">$(document).ready(account_obj.login_init);$(document).ready(account_obj.verifycode_init);</script>
<div class="login_box">
  <div class="tab_box">
    <h2 class="cur" onclick="window.location.href='/member/login.php';">登陆</h2>
    <h2 onclick="window.location.href='/reg.php';">注册</h2>
    <div class="clear"></div>
    <div class="login_con tab_con_1">
        <form>
		    <input type="text" id="Account" name="Account" value="" class="name" />
			<input type="password" id="Password" name="Password" value="" class="password" />
			<p><img class="verifyimg" id="verifyimg" /><input type="text" name="VerifyCode" id="VerifyCode" value="" placeholder="验证码" class="verifycode"/><div class="clear"></div></p>
			<input type="submit" value="登陆" class="login_btn">
		</form>
        <div class="login_msg"></div>
	</div>
  </div>
  <div class="alpha"></div>
</div>
</body>
</html>