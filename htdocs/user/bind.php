<?
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/include/api/b2cshopconfig.class.php');
    if ($_POST) {
        $openid = isset($_POST['openid']) ? htmlspecialchars($_POST['openid']) : '';
        $headurl = isset($_POST['headurl']) ? htmlspecialchars($_POST['headurl']) : '';
        $mobile = isset($_POST['Mobile']) ? htmlspecialchars(trim($_POST['Mobile'])) : '';
        $passwd = isset($_POST['passwd']) ? htmlspecialchars(trim($_POST['passwd'])) : '';
        $uuid = isset($_POST['uuid']) ? htmlspecialchars(trim($_POST['uuid'])) : '';
        $smsMobileKey = isset($_POST['Mobile']) ? 'reg' . htmlspecialchars($_POST['Mobile']) : '';
        if ($_GET['do'] == 'bind') {
            if (!isset($_POST['openid'])) {
                exit('缺少参数');
            }
            if (!isset($_POST['headurl'])) {
                exit('缺少参数');
            }


            //==========================判断绑定开始===========================================

            //手机号非法
            if (!is_mobile($mobile)) {
                $data = [
                    'status' => 0,
                    'msg' => '手机号格式非法',
                ];
                echo json_encode($data);
                exit();
            }
            if(empty($_POST['passwd'])){
                $Data=array(
                    'status'=>0,
                    'msg'=>'请填写密码'
                );
                echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请填写密码！'):$Data,JSON_UNESCAPED_UNICODE);
                exit;
            }
            if(empty($_POST['code'])){
                $Data=array(
                    'status'=>0,
                    'msg'=>'请填写短信验证码'
                );
                echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请填写短信验证码！'):$Data,JSON_UNESCAPED_UNICODE);
                exit;
            }

            //验证码
            $smsMobileKey = 'reg' . $mobile;
            if (!isset($_SESSION[$smsMobileKey]) || empty($_SESSION[$smsMobileKey])) {
                $Data = array(
                    "status" => 0,
                    "msg" => "手机验证码错误",
                );
                echo json_encode($Data,JSON_UNESCAPED_UNICODE);
                exit();
            }
            $smsMobileKeys = json_decode($_SESSION[$smsMobileKey],true);

            if ($smsMobileKeys['code'] != $_POST['code']) {
                $Data = array(
                    "status" => 0,
                    "msg" => "手机验证码错误",
                );
                echo json_encode($Data,JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                unset($_SESSION[$smsMobileKey]);
            }


            //验证是否存在手机号,如果存在,则直接更新对应的头像和openid到对应的记录里===========================================
            $isRegFlag = $DB->GetRs('biz', '*', "where Biz_Account = " . $mobile . " or Biz_Phone = " . $mobile);
            if ($isRegFlag) {
                $passwdVerify = $DB->GetRs('biz', '*', "where Biz_PassWord = '". md5($passwd) ."' and (Biz_Phone = $mobile or Biz_Account = $mobile)");
                if ($passwdVerify) {
                    $time = time();
                    //如果数据库已经存在输入的手机号,则把对应的openid更新到对应的记录里.方便以后实现微信登录
                    $updateTransData = ['Biz_Account' => $mobile, 'headurl' => $headurl, 'openid' => $openid, 'uuid' => $uuid, 'time' => $time+86400*30];
                    $resArr = b2cshopconfig::updateWxLogin($updateTransData);

                    if ($resArr['errorCode'] == 0) {

                        $_SESSION["BIZ_ID"]=$passwdVerify["Biz_ID"];
                        $_SESSION['Biz_Account'] = $passwdVerify['Biz_Account'];
                        $_SESSION["Users_ID"]=$passwdVerify["Users_ID"];
                        $data = [
                            'status' => 1,
                            'msg' => '绑定成功',
                            'url' => 'admin.php?act=store&time=' . $time . '&bizID=' . $_SESSION["BIZ_ID"],
                        ];
                    } elseif ($resArr['errorCode'] == 3) {
                        $data = [
                            'status' => 0,
                            'msg' => $resArr['msg'],
                        ];
                    } else {
                        $data = [
                            'status' => 0,
                            'msg' => '绑定失败',
                        ];
                    }
                } else {
                    $data = [
                        'status' => 0,
                        'msg' => '您输入的密码不正确,请确认',
                    ];
                }
                echo json_encode($data);
                exit();
            }

            //==============================手机号不存在,开始进行注册操作============================================
            $time = time();
            $Users_ID=RandChar(10);
            $usersData = array(
                'Biz_Account' => $mobile,
                'Biz_Phone' => $mobile,
                'Biz_PassWord'=> md5($_POST['passwd']),
                'Users_ID' => $Users_ID,
                'Biz_CreateTime' => time(),
                'User_HeadImg' => $headurl,
                'User_OpenID' => $openid,
                'uuid' => $uuid,
                'loginTime' => $time+86400*30,
                'Users_Right'=>'{"web":["web"],"kanjia":["kanjia"],"zhuli":["zhuli"],"zhongchou":["zhongchou"],"games":["games"],"weicuxiao":["sctrach","fruit","turntable","battle"],"hongbao":["hongbao"],"votes":["votes"]}'
                //'Group_ID'=> $Group_ID
            );

            $row = b2cshopconfig::addBiz(['usersData'=>$usersData]);

            if (!empty($row) && $row['errorCode'] == 0) {
                $shopConfig = $DB->GetRs('shop_config', 'Users_PayCharge', "where Users_ID = 'pl2hu3uczz'");
                $flagUpdate = true;
                if ($shopConfig && $shopConfig['Users_PayCharge'] > 0) {
                    $updateData = ['Users_ExpiresTime' => $time + 86400 * 7];
                    $flagUpdate = $DB->Set('biz', $updateData, "where Biz_Phone = '". $mobile ."'");
                }

                if ($flagUpdate) {
                    $result = [
                        'status' => 1,
                        'msg' => '绑定成功!',
                        'url' => 'admin.php?act=store&time=' . $time . '&bizID=' . $row['Biz_ID']
                    ];
                } else {
                    $result = [
                        'status' => 0,
                        'msg' => '绑定失败,请稍候重试'
                    ];
                }

                $rsBiz = $DB->GetRs("biz", "*", "WHERE Biz_Phone=" . $mobile);

                if ($rsBiz) {
                    $_SESSION["BIZ_ID"]=$rsBiz["Biz_ID"];
                    $_SESSION['Biz_Account'] = $rsBiz['Biz_Account'];
                    $_SESSION["Users_ID"]=$rsBiz["Users_ID"];
                }

                echo json_encode($result);
                exit;
            }

            //==========================判断绑定结束===========================================
        } elseif (isset($_GET['do']) && ($_GET['do'] == 'send') && isset($_POST['Mobile']) && $_POST['Mobile']) {
            //手机号格式
            if (! is_mobile($mobile)) {
                $Data = array(
                    "status" => 0,
                    "msg" => "手机号格式非法"
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
            exit;
        }
    } else {
        if (!isset($_GET['openid'])) {
            exit('缺少参数');
        }
        if (!isset($_GET['headurl'])) {
            exit('缺少参数');
        }
        if (!isset($_GET['uuid'])) {
            exit('缺少参数');
        }
        $openid = (isset($_GET['openid']) && strlen($_GET['openid']) > 5) ? htmlspecialchars($_GET['openid']) : 0;
        $headurl = (isset($_GET['headurl']) && strlen($_GET['headurl']) > 5) ? htmlspecialchars($_GET['headurl']) : 0;
        $uuid = (isset($_GET['uuid']) && strlen($_GET['uuid']) > 5) ? htmlspecialchars($_GET['uuid']) : 0;
    }
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>绑定手机号</title>
</head>
<body>
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<style>
*{margin:0px; padding:0px;}
ul, li, dt, dd, ol, dl{list-style-type:none;}
.w{width:100%; margin:0 auto;}
.login_box{ width:90%; margin:10px auto}
.left{float:left}
.clear{clear:both;}
.login_title{ width:100%; line-height:50px; border-bottom:1px #ddd  solid; text-align:center; font-size:15px; color:#666;}
.login_x{line-height: 45px;margin-top:20px;font-size: 14px;width: 100%;background:url(../static/user/images/pho.png) no-repeat;background-position: 5px 10px;background-size: 20px 20px;text-indent: 30px;color: #333;border-bottom: 1px #ddd solid;border-top:none;border-left:none;border-right:none;outline:none }
.login_x1{line-height: 45px;margin-top:20px;font-size: 14px;width: 100%;background: url(../static/user/images/mm.png) no-repeat;background-position: 5px 10px;background-size: 20px 20px;text-indent: 30px;color: #333;border-bottom: 1px #ddd solid;border-top:none;border-left:none;border-right:none;outline:none }
.login_x2{line-height: 45px;margin-top:20px;font-size: 14px;width: 100%;background: url(../static/user/images/mm.png) no-repeat;background-position: 5px 10px;background-size: 20px 20px;text-indent: 30px;color: #333;border-bottom: 1px #ddd solid;border-top:none;border-left:none;border-right:none;outline:none }
.login_sub{line-height: 43px;height: 43px;width: 100%;color: #ffffff;background-color: #08a5ec;font-size: 16px;text-align: center;display: inline-block;margin: 0 auto;border-radius: 5px;margin-top: 40px;border:none;outline:none}
.reg_x1{line-height: 45px;margin-top:10px;font-size: 14px;width: 100%;background: url(../static/user/images/yzm.png) no-repeat;background-position: 5px 10px;background-size: 20px 20px;text-indent: 30px;color: #333;border-bottom: 1px #ddd solid;border-top:none;border-left:none;border-right:none;outline:none }
.reg_x2{line-height: 45px;margin-top:10px;font-size: 14px;width: 100%;color: #333;border-bottom: 1px #ddd solid;border-top:none;border-right:none;outline:none;border-left:1px #ddd solid;text-align:center;background:none;color:#666}
.head_prox{ margin:0 auto; margin-top:30px; text-align:center}
.head_prox span.win_img{ width:30%;}
.head_prox span.win_img img{ width:60px; height:60px; border-radius:50%;}
.head_prox span.switch_x{ width:30%;}
.head_prox span.switch_x img{ width:60px; height:60px;}
</style>

<script type="text/javascript">
    var sec = 120;
    function jishi() {
        sec--;
        if (sec == 0) {
            $("#getYzm").val('获取验证码').attr('state', '0');
            sec = 120;
        } else {
            $("#getYzm").val(sec + ' 秒').attr('state', '1');
            setTimeout('jishi()', 1000);
        }

    }
    $(function(){
        $("#submit").click(function(){
            $.ajax({
                type:"POST",
                url:"?do=bind",
                dataType:"JSON",
                data:{Mobile:$("input[name=Mobile]").val(), code:$("input[name=yzm]").val(), passwd:$("input[name=passwd]").val(), repasswd:$("input[name=repasswd]").val(),openid:$("input[name=openid]").val(),headurl:$("input[name=headurl]").val(), uuid:$("input[name=uuid]").val()},
                success:function(data){
                    layer.open({
                        content:data.msg,
                        time:2,
                        end:function(){
                            if (data.status == 1) {
                                window.location = data.url;
                            }
                        }
                    })
                }
            })
        });
        $("#getYzm").click(function(){
            var state = $(this).attr("state");
            if (state == "1") return false;

            var Mobile = $("input[name=Mobile]").val();
            if (Mobile == '') {
                $("input[name=Mobile]").focus();
                layer.open({
                    content:"请输入手机号",
                    time:2,
                });
                return false;
            }

            var url = '?do=send';
            $.post(url, {Mobile:Mobile}, function(json) {
                if (json.status == "0") {
                    alert(json.msg);
                    return false;
                } else {
                    $("#getYzm").val("120 秒").attr('state', '1');
                    jishi();
                }
            }, 'json')
        })
    })
</script>
<div class="w">
    <p class="login_title">绑定手机</p>
    <div class="login_box">
        <div class="head_prox">
            <span class="win_img"><img src="<?=$headurl?>"></span>
            <span class="switch_x"><img src="../static/user/images/exchange.png"></span>
            <span class="win_img"><img src="../static/user/images/2p-5_03.jpg"></span>
            <div class="clear"></div>
        </div>
        <input type="tel" name="Mobile" value="" maxlength="11" class="login_x" placeholder="请输入手机号码">
        <span class="left" style="width:60%;"><input type="text" name="yzm" value="" maxlength="16" class="reg_x1" placeholder="请输入验证码"></span>
        <span class="left" style="width:40%;"><input type="button" class="reg_x2" state="0"  value="获取验证码" id="getYzm"></span>
        <input type="password" name="passwd" value="" maxlength="16" class="login_x2" placeholder="输入密码">
        <input type="password" name="repasswd" value="" maxlength="16" class="login_x2" placeholder="确认密码">
        <input type="hidden" name="openid" value="<?=$openid?>" />
        <input type="hidden" name="headurl" value="<?=$headurl?>" />
        <input type="hidden" name="uuid" value="<?=$uuid?>" />
        <input name="提交" id="submit" class="login_sub" value="立即绑定">
    </div>
</div>
</body>
</html>