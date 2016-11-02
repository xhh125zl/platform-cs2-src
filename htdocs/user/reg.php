<?php
require_once 'config.inc.php';

require_once CMS_ROOT . '/include/api/users.class.php';

$Users_ID = 'pl2hu3uczz';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {

    //手机验证码校验开始
	$Account = $mobile = isset($_POST['Mobile']) ? $_POST['Mobile'] : '';
	$smsMobileKey = isset($_POST['Mobile']) ? 'reg' . $_POST['Mobile'] : '';
	

	//发送手机验证码
	if (isset($_POST['do']) && ($_POST['do'] == 'send') && isset($_POST['Mobile']) && $_POST['Mobile']) {
		//手机号格式
		if (! is_mobile($mobile)) {
			$Data = array(
				"status" => 0,
				"msg" => "手机号格式非法"
			);
			echo json_encode($Data, JSON_UNESCAPED_UNICODE);
			exit;
		}


		//检查手机号是否已经存在
		$row = $DB->GetRs('biz', '*', "WHERE Biz_Phone='" . $mobile . "'");
		if (!empty($row)) {
			$Data = array(
				"status" => 0,
				"msg" => "手机号已被占用,请更新一个"
			);
			echo json_encode($Data, JSON_UNESCAPED_UNICODE);
			exit;
		}

		//发送验证码
		$code = sprintf("%'.04d", rand(0, 9999));
		$_SESSION[$smsMobileKey] = json_encode([
				'code' => $code,
				'time' => time() + 120,	//120秒
			]);

		require_once(CMS_ROOT.'/Framework/Ext/sms.func.php');
		$message = "手机验证码为：" . $code . "。120秒内有效，过期请重新获取。" ;
		$success = send_sms($mobile, $message);
		if ($success) {
			$Data = array(
				"status"=> 1,
				"msg"=>"短信发送成功"
			);
		} else {
			$Data = array(
				"status"=> 0,
				"msg"=>"短信发送失败"
			);
		}

		echo json_encode($Data, JSON_UNESCAPED_UNICODE);
		exit;	
	}



    if (isset($_POST['do']) && $_POST['do'] == 'reg') {

       	$Account = $mobile = isset($_POST['Mobile']) ? $_POST['Mobile'] : '';
        $password = isset($_POST['Password']) ? $_POST['Password'] : '';

		//为空判断
        if (empty($Account) || empty($password)) {
            $result = [
                'status' => 0,
                'msg' => '用户名或者密码不能为空'
            ];
            
            echo json_encode($result);
            exit();
        }


		//手机号格式
		if (! is_mobile($mobile)) {
			$Data = array(
				"status" => 0,
				"msg" => "手机号格式非法"
			);
			echo json_encode($Data, JSON_UNESCAPED_UNICODE);
			exit;
		}

		//验证码
		$captcha = isset($_POST['captcha']) ? $_POST['captcha'] : '';
		if (!isset($_SESSION[$smsMobileKey]) || empty($_SESSION[$smsMobileKey]) || empty($captcha)) {
			$Data = array(
				"status" => 0,
				"msg" => "手机验证码错误"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		
		} else {
			$cacheData = json_decode($_SESSION[$smsMobileKey], true);
			if ( ($cacheData['code'] != $captcha) || ($cacheData['time'] < time()) ) {
				$Data = array(
					"status" => 0,
					"msg" => "手机验证码错误"
				);
				echo json_encode($Data,JSON_UNESCAPED_UNICODE);
				exit;
			}
		}		


		$rsBiz=$DB->GetRs("biz","*","where Biz_Phone='" . $mobile . "'");
		
        if ($rsBiz) {
            $result =  [
                'status' => 0,
                'msg' => "此账号已被占用，请更换一个新账号"
            ];

			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
  
//远程注册新用户
$time = time();
$data = [
	'usersData' => [
		'Users_ID' => RandChar(10),
		'Users_Account' => $Account,
		'Users_Password' => md5($password),
		'Users_Phone' => $mobile,
		'Users_CreateTime' => $time,
		'Users_Status' => 1,
		'Users_ExpireDate' => $time + 86400 * 365 * 5,
		'Users_Right' => '{"web":["web"],"kanjia":["kanjia"],"zhuli":["zhuli"],"zhongchou":["zhongchou"],"games":["games"],"weicuxiao":["sctrach","fruit","turntable","battle"],"hongbao":["hongbao"],"votes":["votes"]}',
	]
];
$ret = users::addUsers($data);
if ($ret['errorCode'] != 0) {
	$result = [
		'status' => 1,
		'msg' => '注册失败'
	];
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
	exit;	
}

		$data = [
			'Users_ID' => $Users_ID,
			'Biz_Account' => $Account,
			'Biz_Name' => $Account,
			'Biz_PassWord' => md5($password),
			'Biz_Phone' => $mobile,
			'Biz_CreateTime' => $time,
		];

		$flag = $DB->Add('biz', $data);
		$shopConfig = $DB->GetRs('shop_config', 'Users_PayCharge', "where Users_ID = 'pl2hu3uczz'");
		$flagUpdate = true;
		if ($shopConfig && $shopConfig['Users_PayCharge'] > 0) {
			$updateData = ['Users_ExpiresTime' => $time + 86400 * 7];
			$flagUpdate = $DB->Set('biz', $updateData, "where Biz_Account = '". $Account ."'");
		}
		if ($flag && $flagUpdate) {

			$Biz_ID = $DB->insert_id();

			$data = [
				'Users_ID' => $Users_ID,
				'Biz_ID' => $Biz_ID,
			];
			$ret = users::addBizApply($data);
			if ($ret['errorCode'] != 0) {
				die('error');
			}

			$result = [
				'status' => 1,
				'msg' => '注册成功!',
				'url' => 'admin.php?act=store'
			];
		} else {
			$result = [
				'status' => 0,
				'msg' => '注册失败,请稍候重试'
			];
		}

		//查找绑定的会员ID
		$Biz_ID = $DB->insert_id();
		$rsBiz = $DB->GetRs("biz", "*", "WHERE Biz_ID=" . $Biz_ID);
				
		if ($rsBiz) {
                $_SESSION["BIZ_ID"]=$rsBiz["Biz_ID"];
                $_SESSION['Biz_Account'] = $rsBiz['Biz_Account'];
                $_SESSION["Users_ID"]=$rsBiz["Users_ID"];
		}

        echo json_encode($result);

    }
    exit();

}

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>注册</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<body>
<div class="w">
	<p class="login_title">注册</p>
    <div class="login_box">
	<form id="user_form" name="user_form">
       
        <input type="tel" name="Mobile" id="Mobile" value="" maxlength="11" class="reg_x" placeholder="请输入手机号码">
        <span class="l" style="width:60%;"><input type="text" name="captcha" id="captcha" value="" maxlength="4" class="reg_x1 reg_captcha_icon" placeholder="请输入验证码"></span>
        <span class="l" style="width:40%;"><input type="button" id="btn_send" state="0" class="reg_x2" value="获取验证码" maxlength="16"></span>
		<div class="clear"></div>
    	<input type="password" name="Password" id="Password" value="" maxlength="11" class="reg_x1" placeholder="登录密码" maxlength="16">
        <input type="password" name="ConfirmPassword" id="ConfirmPassword" value="" maxlength="16" class="reg_x1" placeholder="确认登录密码">
        <div class="clear"></div>		
        <div class="reg_t" style="display:none">
        	<textarea name="copyright" rows="3" readonly>我阅读并签署协议协议</textarea>
        </div>
         <label class="checkbox">
             <input type="checkbox"  name="agree">
              我阅读并签署协议
         </label>
		 <input type="hidden" id="do" name="do" value="reg">
        <input name="提交" type="button" class="reg_sub" value="立即注册">
</form>		
    </div>
</div>
<script type="text/javascript">
	var sec = 120;
	function jishi() {
		sec--;
		if (sec == 0) {
			$("#btn_send").val('获取验证码').attr('state', '0');
			sec = 120;
		} else {
			$("#btn_send").val(sec + ' 秒').attr('state', '1');
			setTimeout('jishi()', 1000);	
		}
		
	}
$(function(){
	$("#btn_send").click(function(){
		var state = $(this).attr("state");
		if (state == "1") return false;

		var Mobile = $("#Mobile").val();
		if (Mobile == '') {
			$("#Mobile").focus();
			alert("请输入手机号");
			return false;
		}

		var url = '?inajax=1&wxref=mp.weixin.qq.com';
		$.post(url, {do: 'send', Mobile:Mobile}, function(json) {
			if (json.status == "0") {
				alert(json.msg);
				return false;
			} else {
				$("#btn_send").val("120 秒").attr('state', '1');
				jishi();
			}
		}, 'json')

	})

	$("input[type='checkbox']").click(function(){
		if (! $("input[type='checkbox']").is(':checked')) {
			$(".reg_t").hide();
		} else {
			$(".reg_t").show();
		}
	})

	//注册新用户
	$(".reg_sub").click(function(){

		var Mobile=$('#user_form input[name=Mobile]').val();
		if(Mobile=='' || Mobile.length!=11){
			layer.open({
				content:'请正确填写手机号码！',
				time:2,
				end:function(){
					$('input[name=Mobile]').focus();
				} 
			});
			
			return false;
		}


		var captcha=$('#captcha').val();
		if(captcha=='' || captcha.length!=4){
			layer.open({
				content:'请填写四位验证码！',
				time:2,
				end:function(){
					$('#captcha').focus();
				} 
			});
			
			return false;
		}

		var password=$('#Password').val();
		var ConfirmPassword = $("#ConfirmPassword").val();
		if(password=='' || password.length<6){
			layer.open({
				content:'请填写登录密码，至少六位！',
				time:2,
				end:function(){
					$('#captcha').focus();
				} 
			});
			
			return false;
		}

		if(password != ConfirmPassword){
			layer.open({
				content:'两次输入的密码不一致',
				time:2,
				end:function(){
					$('#captcha').focus();
				} 
			});
			
			return false;
		}

		//协议
		if (! $("input[type='checkbox']").is(':checked')) {
			layer.open({
				content:'必须同意注册协议才允许注册',
				time:2,
				end:function(){
					$('#captcha').focus();
				} 
			});
			
			return false;		
		}

		$(this).attr('disabled', true);
		$.post('?inajax=1', $('#user_form').serialize(), function(data){
			if(data.status==1){
				layer.open({
					content:data.msg,
					time:2,
					end:function(){
						window.location=data.url;
					} 
				});
									
			}else{
				layer.open({
					content:data.msg,
					time:2,
					end:function(){
						$(".reg_sub").attr('disabled', false);
					} 
				});
			}
		}, 'json');
	})

})	
</script>   
</body>
</html>
