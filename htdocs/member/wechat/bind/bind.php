<?php
require_once('BindClass.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if($_POST)
{
	$errbiao = array(
		"-1"=>"系统错误",
		"-2"=>"帐号或密码错误",
		"-3"=>"密码错误",
		"-4"=>"不存在该帐户",
		"-5"=>"访问受限",
		"-6"=>"需要输入验证码",
		"-7"=>"此帐号已绑定私人微信号，不可用于公众平台登录",
		"-8"=>"邮箱已存在",
		"-32"=>"验证码输入错误",
		"-200"=>"因频繁提交虚假资料，该帐号被拒绝登录",
		"-94"=>"请使用邮箱登陆",
		"10"=>"该公众会议号已经过期，无法再登录使用"
	);
	if(!empty($_POST["pwun"])&&!empty($_POST["pwps"])){
		$pwun = $_POST["pwun"];
		$pwps = $_POST["pwps"];
		//判断该帐号是否已被绑定
		$bind = new Bind($pwun, $pwps);
		var_dump($bind);
	}
	exit;
}
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/wechat.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="/member/wechat/attention_reply.php">首次关注设置</a></li>
        <li class=""><a href="/member/wechat/menu.php">自定义菜单设置</a></li>
        <li class=""><a href="/member/wechat/keyword_reply.php">关键词回复</a></li>
        <li class="cur"><a href="/member/wechat/token_set.php">微信接口配置</a></li>
        <li class=""><a href="/member/wechat/auth_set.php">微信授权配置</a></li>
      </ul>
    </div>
    <div id="token" class="r_con_wrap">
      <div class="tips_info"></div>
      <form class="r_con_form" method="post" action="?">
        <div class="set_token_msg"></div>
        <div class="rows">
          <label style="width:200px;">微信公众平台用户名:</label>
          <span class="input"><input type="text" name="pwun" class="form_input" value="" size="30" notnull /></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label style="width:200px;">微信公众平台密码:</label>
          <span class="input"><input type="password" name="pwps" class="form_input" value="" size="30" notnull /></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label style="width:200px;"></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="绑定" />
          </span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>