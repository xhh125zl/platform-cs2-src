<?php
 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/verifycode/verifycode.class.php');

if(isset($_SESSION['user_type'])){
	if(isset($_SESSION['employee_id']) && isset($_SESSION['employee_id'])){
		header("Location:/member/");
	}
}else{
	if(isset($_SESSION["Users_ID"])){
		header("Location:/member/");
	}
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
		$safearr = array("'","=","#");
		foreach($safearr as $str){
		if(strpos($_POST['Account'],$str)){
		$Data=array(
							'status'=>3
						);
						echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
						exit;	
		}
		}
		$employee = false;
		if(!empty($_POST['Account'])){
			if(isset($_POST['login_type']) && $_POST['login_type'] == 'employee'){
					$employee = true;
					$employee_users=$DB->GetRs("users_employee","*","where employee_login_name='".$_POST['Account']."' and status='1' and employee_pass='".md5($_POST["Password"])."'");
					
					if($employee_users){
						$role=$DB->GetRs("users_roles","*","where id='".$employee_users["role_id"]."' and status='1'");
							$Data=array(
								'status'=>1
							);
							$_SESSION['user_type'] = 'employee';
							$_SESSION['employee_id'] = $employee_users['id'];
							$_SESSION['employee_name'] = $employee_users['employee_name'];
							$_SESSION['role_id'] = $employee_users['role_id'];
							//$_SESSION['role_name'] = $role['role'];
							//$_SESSION['role_right'] = $role['role_right'];
					}else{
						$Data=array(
							'status'=>3
						);
						echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
						exit;
					}
					$rsUsers=$DB->GetRs("users","","where Users_Account='".$employee_users["users_account"]."'");
				}else{
					$rsUsers=$DB->GetRs("users","","where Users_Account='".$_POST["Account"]."' and Users_Password='".md5($_POST["Password"])."'");
				}
		}else{
			$Data=array(
					'status'=>0
				);
		}

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
<script type='text/javascript' src='/static/member/js/global.js?t=<?php echo time();?>'></script>
</head>

<body>
<link href='/static/member/css/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/account.js?t=<?php echo time();?>'></script>
<script language="javascript">$(document).ready(account_obj.login_init);$(document).ready(account_obj.verifycode_init);</script>
<div class="login-box">
	<div class="login-title">
		<h1>登录</h1>
		<span class="login-english">login in</span>
	</div>

	<div class="login-form">
		<form action="?">
			<label for="Account" class="label"><div class="label-content username"></div></label>
  			<input type="text" name="Account" id="Account" class="input" placeholder="账号"  />
			<div class="split"></div>

  			<label for="Password" class="label"><div class="label-content password"></div></label>
  			<input type="password" name="Password" id="Password" class="input" placeholder="密码"  />
			<div class="split"></div>

			<label for="VerifyCode" class="label"><div class="label-content virtrfy"></div></label>
  			<input type="text" name="VerifyCode" id="VerifyCode" class="input virtrfycode" placeholder="验证码"  /><img class="verifyimg" id="verifyimg" />
			<div class="split split-min"></div>
  			
  			<label for="manage" class="manage">
  			<input type="checkbox" name="login_type" id="manage" value="employee" />管理登录
  			</label>
			<div class="split split-min"></div>
  			<input type="submit" value="登 录" class="submit login_btn">
		</form>
	</div>

	<!-- <a class="tips-login" href="http://kefu6.kuaishang.cn/bs/im.htm?cas=48389___440052&fi=52129">注册</a> -->

	<div class="login_msg"></div>
	<div class="forget_password"><a href="/member/findpwd.php">忘记密码？</a></div>
</div>
</body>
</html>