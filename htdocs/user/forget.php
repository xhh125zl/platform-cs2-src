<?php
define('USER_PATH', dirname(__FILE__) . '/');

include USER_PATH . '../Framework/Conn.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';
    $mobile = isset($_POST['Mobile']) ? $_POST['Mobile'] : '';
    
    $smsMobileKey = 'forget_' . $mobile;

    //发送手机验证码
    if ($do == 'send') {

        if (!is_mobile($mobile)) {
			$Data = array(
				"status"=> 0,
				"msg"=>"手机号格式非法"
			);
			echo json_encode($Data, JSON_UNESCAPED_UNICODE);
			exit;  
        }

        //检查手机号是否为注册用户
		$row = $DB->GetRs('biz', '*', "WHERE Biz_Phone='" . $mobile . "'");
		if (empty($row)) {
			$Data = array(
				"status"=> 0,
				"msg"=>"手机号未注册本站用户"
			);
			echo json_encode($Data, JSON_UNESCAPED_UNICODE);
			exit;
		}


		$code = sprintf("%'.04d", rand(0, 9999));
		$_SESSION[$smsMobileKey] = json_encode([
				'code' => $code,
				'time' => time() + 120,	//120秒
			]);

		require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
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

    } else if ($do == 'get') {
        //校验
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
                    "msg" => "手机验证码错误",
                );
                echo json_encode($Data,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        //手机验证码校验结束

        $password = isset($_POST['Password']) ? $_POST['Password'] : '';
        $ConfirmPassword = isset($_POST['ConfirmPassword']) ? $_POST['ConfirmPassword'] : '';

        if (strlen($password) < 6) {
            $Data = [
                "status" => 0,
                "msg" => "密码长度至少6位",
            ];
            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($password != $ConfirmPassword) {
            $Data = [
                "status" => 0,
                "msg" => "两次密码不一致",
            ];
            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
            exit;		
        }

        $data = [
            "Biz_PassWord" => md5($password),
        ];
        $DB->Set("biz", $data, "WHERE Biz_Phone='" . $mobile . "'");

        $Data=array(
            "status" => 1,
            "msg" => "修改密码成功，请使用新密码登录！",
            "url" => '/user/login.php',
        );

        
        echo json_encode($Data,JSON_UNESCAPED_UNICODE);
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
<title>忘记密码</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<body>
<div class="w">
	<p class="login_title">找回密码</p>
    <div class="login_box">
    <form id="form1" name="form1">
    	<input tabIndex=1  type="tel" id="Mobile" name="Mobile" value="" maxlength="11" class="reg_x" placeholder="请输入手机号码" notnull="">
        <span tabIndex=2 class="l" style="width:60%;"><input type="text" id="captcha" name="captcha" value="" maxlength="4" class="reg_x1" placeholder="请输入验证码" notnull=""></span>
        <span tabIndex=3 class="l" style="width:40%;"><input state="0"  pattern="[0-9]*" type="button" class="reg_x2 btn_send" value="获取验证码"></span>
        <div class="clear"></div>
        <input tabIndex=4 type="password" name="Password" id="Password" value="" maxlength="11" class="reg_x1" placeholder="请输入新的登录密码" notnull="">
        <input tabIndex=5 type="password" name="ConfirmPassword" id="ConfirmPassword" value="" maxlength="16" class="reg_x1" placeholder="确认新密码" notnull="">
        <div class="clear"></div>
        <input name="提交" type="button" class="reg_sub" style="margin:20px 0" value="修改密码">
    </form>
    </div>
</div>
<script type="text/javascript">

	var sec = 120;
	function jishi() {
		sec--;
		if (sec == 0) {
			$(".btn_send").val('获取验证码').attr('state', '0');
			sec = 120;
		} else {
			$(".btn_send").val(sec + ' 秒').attr('state', '1');
			setTimeout('jishi()', 1000);	
		}
		
	}

$(function(){
	$(".btn_send").click(function(){
		var state = $(this).attr("state");
		if (state == "1") return false;

		var Mobile = $("#Mobile").val();
		if (Mobile == '') {
			$("#Mobile").focus();
			alert("请输入手机号");
			return false;
		}
        
		var url = '?inajax=1&do=send';
		$.post(url, {Mobile:Mobile}, function(json) {
			if (json.status == "0") {
				alert(json.msg);
				return false;
			} else {
				$(".btn_send").html("60秒").attr('state', '1');
				jishi();
			}
		}, 'json')

	})

	//提交
	$('input.reg_sub').click(function(){
        var Mobile = $("#Mobile").val();
        if (Mobile.length != 11) {
            alert('请输入手机号码');
            return false;
        }

        var captcha = $("#captcha").val();
        if (captcha.length != 4) {
            alert('请输入手机验证码');
            return false;
        }

        var Password = $("#Password").val();
        var ConfirmPassword = $("#ConfirmPassword").val();

        if (Password == '' || Password.length < 6 || (Password != ConfirmPassword)) {
            alert('新密码输入有误');
            return false;
        }		

		$(this).attr('disabled', true);
		$.post('?inajax=1&do=get', $('#form1').serialize(), function(data){
			if (data.status==1) {
				layer.open({
                    content:data.msg,
                    time:2,
                    end: function(){
                        window.location=data.url;
                    }
                });
				
			}else{
				layer.open({
                    content:data.msg,
                    time:2,
                    end: function(){
                        $('#form1 input.reg_sub').attr('disabled', false)
                    }
                });
			};
		}, 'json');
	});	
})	



</script>            

</body>
</html>
