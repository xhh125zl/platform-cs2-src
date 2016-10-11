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

        //if (is_mobile($account)) {
            $rsBiz=$DB->GetRs("biz","*","where Biz_Phone='" . $_POST["Account"] . "' and Biz_PassWord='" . md5($_POST["Password"]) . "'");
        //} else {
//            $rsBiz=$DB->GetRs("biz","*","where Biz_Account='" . $_POST["Account"] . "' and Biz_PassWord='" . md5($_POST["Password"]) . "'");
        //}
        
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
                // if ($rsBiz['UserID']) {
                //     $rsUser=$DB->GetRs("user", "*", "WHERE User_ID=" . intval($rsBiz['UserID']));
                            
                //     if ($rsUser) {
                //         $UsersID = $rsBiz['Users_ID'];
                //         $_SESSION[$UsersID."User_ID"]=$rsUser["User_ID"];
                //         $_SESSION[$UsersID."User_Name"]=$rsUser["User_Name"];
                //         $_SESSION[$UsersID."User_Mobile"]=$rsUser["User_Mobile"];
                //     }
                // }
                
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
<title>用户登录</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type='text/javascript' src='../static/js/plugin/layer_mobile/layer.js'></script>
<body>
<div class="w">
	<p class="login_title">登录</p>
    <div class="login_box">
    <form id="form1" name="form1" method="post">
    	<input type="tel" name="Account" id="Account" value="" maxlength="11" class="login_x" placeholder="手机号" notnull="">
        <input type="password" name="Password" id="Password" value="" maxlength="16" class="login_x1" placeholder="登录密码" notnull="">
        <input name="提交" type="button" class="login_sub" value="立即登录">
            <div class="login_t">
               <span class="l"><a href="forget.php">忘记密码？</a></span>
               <span class="r"><a href="reg.php">立即注册</a></span>
        </div>
    </form>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".login_sub").click(function(){
        var account = $("#Account").val();
        var password = $("#Password").val();

        if (account.length != 11) {
            layer.open({
                content: "请填写11位手机号", 
                time:2, 
                end:function(){
                   $("#Account").focus();
                }
            })
            return false;
        }
        if (account == '' || password == '') {
            layer.open({
                content: "用户名和登录密码不能为空", 
                time:2, 
                end:function(){
                    $("#Account").focus();
                }
            }) 
            return false;
        }     

        $(this).attr('disabled', true).val('登录中');
        $.post('?do=login&inajax=1', $("#form1").serialize(), function(json) {
            if (json.status == '0') {
                layer.open({
                    content: json.msg, 
                    time:2, 
                    end:function(){
                        $(".login_sub").removeAttr("disabled").val('登录');
                    }
                })
            } else {
                location.href = json.url;
            }
        },'json')
    })

})
</script>
</body>
</html>