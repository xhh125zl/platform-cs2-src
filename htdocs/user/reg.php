<?php
define('USER_PATH', dirname(__FILE__) . '/');

include USER_PATH . '../Framework/Conn.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    if (isset($_GET['do']) && $_GET['do'] == 'login') {

        $account = isset($_POST['Account']) ? $_POST['Account'] : '';
        $password = isset($_POST['Password']) ? $_POST['Password'] : '';

        if (empty($account) || empty($password)) {
            $result = [
                'status' => 0,
                'msg' => '用户名或者密码不能为空'
            ];
            
            echo json_encode($result);
            exit();
        }


        $rsBiz=$DB->GetRs("biz","*","where Biz_Account='" . $_POST["Account"] . "' and Biz_PassWord='" . md5($_POST["Password"]) . "'");
        if ($rsBiz) {
            if ($rsBiz["Biz_Status"] == 1) {
                $result =  [
                    'status' => 0,
                    'msg' => "账号已被禁用，无法登录"
                ];
            } else {
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
                
                $result =  [
                    'status' => 1,
                    'url' => "/user/admin.php?act=store"
                ];
            }
        } else {
            $result =  [
                'status' => 0,
                'msg' => "登录账号或密码错误！"
            ];
        }

        echo json_encode($result);

    }
    exit();

}

//退出登录
if (isset($_GET['do']) && $_GET['do'] == 'logout') {
    //session.destory();
    	session_unset();
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
<body>
<div class="w">
	<p class="login_title">注册</p>
    <div class="login_box">
    	<input type="tel" name="Mobile" value="" maxlength="11" class="reg_x" placeholder="请输入手机号码">
        <span class="l" style="width:60%;"><input type="password" name="Password" value="" maxlength="4" class="reg_x1" placeholder="请输入验证码"></span>
        <span class="l" style="width:40%;"><input type="button" class="reg_x2" value="获取验证码" maxlength="16"></span>
        <div class="clear"></div>
        <input type="password" name="Password" value="" maxlength="11" class="reg_x1" placeholder="登录密码" maxlength="16">
        <input type="password" name="ConfirmPassword" value="" maxlength="16" class="reg_x1" placeholder="确认登录密码">
        <div class="clear"></div>
        <div class="reg_t">
        	<textarea name="copyright" rows="3">我阅读并签署协议协议</textarea>
        </div>
         <label class="checkbox">
             <input type="checkbox" checked="" name="agree">
              我阅读并签署协议协议
         </label>
        <input name="提交" type="submit" class="reg_sub" value="立即注册">
    </div>
</div>
</body>
</html>
