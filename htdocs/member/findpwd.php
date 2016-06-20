<?php

$DB->showErr=false;

if(isset($_GET["action"])){
	if($_GET["action"] == "smscode" && isset($_GET["mobile"])){
		$mobile = trim($_GET["mobile"]);
		if(!$mobile){
			$Data = array(
				"status"=> 0,
				"msg"=>"请输入手机号码"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!isset($_SESSION['mobile_send']) || empty($_SESSION['mobile_send'])){
			$_SESSION['mobile_send'] = 0;
		}
		
		if(isset($_SESSION['mobile_time']) && !empty($_SESSION['mobile_time'])){
			if(time() - $_SESSION['mobile_time'] < 300){
				$Data = array(
					"status"=> 0,
					"msg"=>"发送短信过快"
				);
				echo json_encode($Data,JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		
		if($_SESSION['mobile_send'] > 4){
			$Data = array(
				"status"=> 0,
				"msg"=>"发送短信次数频繁"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}		
		
		function randcode($length=6){
			$chars = '0123456789';
			$temchars = '';
			for($i=0;$i<$length;$i++)
			{
				$temchars .= $chars[ mt_rand(0, strlen($chars) - 1) ];
			}
			return $temchars;
		}
		$code = randcode(6);
		
		$_SESSION['mobile'] = $mobile;
		$_SESSION['mobile_code'] = md5($mobile.'|'.$code);
		$_SESSION['mobile_time'] = time();
		$_SESSION['mobile_send'] = $_SESSION['mobile_send'] + 1;
		
		$message = $SiteName."验证码：".$code."。此验证码仅使用于找回密码,十分钟有效。";
		require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
		$result = send_sms($mobile,$message);
		if($result==1){
			$Data = array(
				"status"=> 1,
				"msg"=>"发送成功，验证码十分钟有效。"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 0,
				"msg"=>"发送失败"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
}

if($_POST){
	if(isset($_POST["step"])){
		$step = $_POST["step"];
	}else{
		$step = 0;
	}
	
	if($step == 0){
		if(empty($_POST["Users_Account"])){
			$Data=array(
				'status'=>0,
				'msg'=>'请填写用户名'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		$rsUsers=$DB->GetRs("users","*","where Users_Account='".$_POST["Users_Account"]."'");
		
		if(!$rsUsers){
			$Data=array(
				'status'=>0,
				'msg'=>'该用户不存在'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!$rsUsers["Users_Mobile"]){
			$Data=array(
				'status'=>0,
				'msg'=>'该用户没有绑定手机号，请联系客服找回'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		$Data=array(
			'status'=>1,
			'msg'=>'提交成功',
			'url'=>'/member/findpwd.php?step=1&findid='.(time().$rsUsers["Users_ID"])
		);
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($step == 1){
		if(empty($_POST["findid"])){
			$Data=array(
				'status'=>0,
				'msg'=>'请填写用户名'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		$UsersID = substr($_POST["findid"],10);
		
		$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
		
		if(!$rsUsers){
			$Data=array(
				'status'=>0,
				'msg'=>'该用户不存在'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!$rsUsers["Users_Mobile"]){
			$Data=array(
				'status'=>0,
				'msg'=>'该用户没有绑定手机号，请联系客服找回'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!$_POST["Users_Mobile"]){
			$Data=array(
				'status'=>0,
				'msg'=>'请输入手机号码'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if($rsUsers["Users_Mobile"] != $_POST["Users_Mobile"]){
			$Data=array(
				'status'=>0,
				'msg'=>'手机号和用户名不匹配'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!$_POST["sms_code"]){
			$Data=array(
				'status'=>0,
				'msg'=>'请输入手机验证码'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!preg_match("/[0-9]{6}/", $_POST['sms_code']) || $_SESSION['mobile_code'] != md5($_POST['Users_Mobile'].'|'.$_POST['sms_code'])){
			$Data=array(
				'status'=>0,
				'msg'=>'手机验证码不正确'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(empty($_POST["Users_PasswordA"]) || empty($_POST["Users_PasswordB"])){
			$Data=array(
				'status'=>0,
				'msg'=>'登录密码和确认密码都必须填写'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;		
		}
		if($_POST["Users_PasswordA"]!=$_POST["Users_PasswordB"]){
			$Data=array(
				'status'=>0,
				'msg'=>'登录密码和确认密码不一致，请修改'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		$Data = array(
			"Users_Password"=>md5($_POST["Users_PasswordA"])
		);
		$flag = $DB->Set("users",$Data,"where Users_ID='".$UsersID."'");
		if($flag){
			$Data=array(
				'status'=>1,
				'msg'=>'操作成功',
				'url'=>'/member/login.php'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data=array(
				'status'=>0,
				'msg'=>'操作失败'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}		
	}	
}else{
	if(isset($_GET["step"])){	
		$step = $_GET["step"];
		if($step==1){
			if(isset($_GET["findid"])){
				$findid = $_GET["findid"];
				$UsersID = substr($findid,10);
				$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
				if(!$rsUsers){
					echo '<script language="javascript">alert("该用户不存在");window.location.href="/member/login.php";</script>';
					exit;
				}
				if(!$rsUsers["Users_Mobile"]){
					echo '<script language="javascript">alert("该用户没有绑定手机号，请联系客服找回");window.location.href="/member/login.php";</script>';
					exit;
				}
			}else{
				echo "缺少必要的参数";
				exit;
			}
		}
	}else{
		$step = 0;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>找回密码 - <?php echo $SiteName;?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/account.js'></script>
<script language="javascript">$(document).ready(account_obj.findpwd_init);</script>
</head>
<body>
<div class="login-box">
  <div class="tab_box">
    <h2 onclick="window.location.href='/member/findpwd.php';" style="font-size:18px;">找回密码</h2>
    <div class="clear"></div>
    <div class="reg_con tab_con_2">
	<?php if($step==0){?>
      <form>
      	<div class="split"></div>
	    <input type="text" name="Users_Account" value="" class="name" placeholder="用户名" style="height: 56px; line-height: 56px; width: 100%; text-indent: 10px; font-size: 16px;" />
    <div class="split"></div>
		<input type="submit" value="下一步" class="reg_btn">
      </form>
	<?php }elseif($step==1){?>
	  <form>
	    <input type="text" disabled="disabled" value="<?php echo $rsUsers["Users_Account"];?>" class="name" placeholder="用户名"  style="height: 56px; line-height: 56px; text-indent: 10px; font-size: 16px; width:270px;"  />
		<input type="text" readonly="readonly" name="Users_Mobile" value="<?php echo $rsUsers["Users_Mobile"];?>" class="mobile" id="mobile" style="height: 56px; line-height: 56px; text-indent: 10px; font-size: 16px; width:270px; float:right;" />
		<div class="split"></div>
		<p><input type="text" name="sms_code" id="VerifyCode" class="input virtrfycode" placeholder="短信验证码"  /><span class="faxinxi" id="sendsms">发送验证码</span><div class="clear"></div></p>
		<input type="password" name="Users_PasswordA" value="" class="password" placeholder="新密码" style="height: 56px; line-height: 56px; text-indent: 10px; font-size: 16px; width:270px;" />
		<input type="password" name="Users_PasswordB" value="" class="password" placeholder="确认密码" style="height: 56px; line-height: 56px; text-indent: 10px; font-size: 16px; width:270px; float:right;" />
		<input type="hidden" name="findid" value="<?php echo $findid;?>" />
		<input type="hidden" name="step" value="<?php echo $step;?>" />
		<div class="clear"></div>
		<div class="split"></div>
		<input type="submit" value="下一步" class="reg_btn">
      </form>
	<?php }?>
	</div>
  </div>
  <div class="alpha"></div>
</div>
</body>
</html>