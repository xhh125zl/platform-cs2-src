<?php
define('CMS_ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once(CMS_ROOT . '/Framework/Conn.php');

$Users_ID = isset($_GET['Users_ID']) ? $_GET['Users_ID'] : '';
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta property="qc:admins" content="2176451252454565401601765601176375" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<style type="text/css">html, body{background:#fff;}</style>
<div id="header_unlogin" class="wrap">
  <ul>
    <li class="home first"><a href="/api/1ek2n5gsut/shop/"></a></li>
    <li class="tel"><!--<a href="tel:"></a>--></li>
    <li class="lbs"><!--<a ajax_url="/api/1ek2n5gsut/user/lbs/"></a>--></li>
  </ul>
</div>
<script language="javascript">//$(document).ready(user_obj.user_login_init);</script>
<form action="" method="post" id="user_form">
  <div class="tips">您还未登录，请先登录！</div>
  <h1>没有帐号</h1>
  <div class="reg"><a href="/api/1ek2n5gsut/user/create/">注册只需10秒</a></div>
  <h1>已有帐号</h1>
  <div class="input">
    <input type="tel" name="Mobile" id="Mobile" value="" maxlength="11" placeholder="手机号码" pattern="[0-9]*" notnull />
  </div>
  <div class="input">
    <input type="password" name="Password" id="Password" value="" maxlength="16" placeholder="登录密码" notnull />
  </div>
  <div class="submit">
    <input name="提交" type="submit" value="立即登录" />
  </div>

<div class="thirdLogin" style="padding-top:20px; text-align:right; color:#666">
第三方登录 <a href="/login/third/qq/?users_id=<?php echo $Users_ID; ?>"><img valign=middle src="http://qzonestyle.gtimg.cn/qzone/vas/opensns/res/img/Connect_logo_3.png" border="0"></a>
</div>

</form>
<script type="text/javascript">

function chkMobile(tel) {
  
 var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
 if (reg.test(tel)) {
      return true
 }else{
      return false;
 };
}

  $(function(){
    $("#user_form").submit(function(){

    var tel = $("#Mobile").val();
    if (!chkMobile(tel)) {
        $("#Mobile").focus();
        alert('手机号填写错误!');
        return false;
    }
        
      
      if ($("#Password").empty()) {
        $("#Password").focus();
        alert('登录密码不能为空!');
        return false;
      }

    })

  })

</script>
</body>
</html>