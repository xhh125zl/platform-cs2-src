<!DOCTYPE html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>注&nbsp;&nbsp;&nbsp;册</title>
<style type="text/css">
body {
	_behavior: url(<?php echo $output['_site_url']?>/static/pc/public/css/csshover.htc);
}
</style>
<link href="<?php echo $output['_site_url']?>/static/pc/public/css/home_login.css" rel="stylesheet" type="text/css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo $output['_site_url']?>/static/pc/public/js/html5shiv.js"></script>
      <script src="<?php echo $output['_site_url']?>/static/pc/public/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo $output['_site_url']?>/static/pc/public/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix');
</script>
<script>
// <![CDATA[
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand))
try{
    document.execCommand("BackgroundImageCache", false, true);
}
catch(e){
	
}
// ]]>
</script>
<![endif]-->
<script>
var SITEURL = '<?php echo $output['_site_url']?>';
var SHOP_SITE_URL = '<?php echo $output['_site_url']?>';
var RESOURCE_SITE_URL = 'http://<?php echo $output['_site_url']?>/static/pc';
</script>
</head>
<body>
<div class="header-wrap">
    <header class="public-head-layout wrapper">
	    <h1 class="site-logo"><a href="<?php echo url('index/index',array('UsersID'=>$output['UsersID']));?>"><img class="pngFix" src="<?php echo $output['shopConfig']['logo'];?>" style="max-width:220px;max-height:90px;"></a></h1>
	</header>
</div>
<div class="pre-login-layout">
	<div class="left-pic"><img src="<?php echo $output['shopConfig']['reg_bg'];?>" border="0"></div>
	<div class="pre-login">
		<div class="pre-login-title">
			<h3>用户注册</h3>
		</div>
		<div class="pre-login-content" id="demo-form-site">
			<form id="login_form" method="post" action="<?php echo url('public/register',array('UsersID'=>$output['UsersID']));?>" class="bg">
				<dl>
					<dt>手&nbsp;机&nbsp;号</dt>
					<dd style="min-height:54px;">
						<input value="" class="text valid" autocomplete="off" name="mobile" id="mobile" autofocus="" type="text">
						<label></label>
					</dd>
				</dl>
				<dl>
					<dt>设置密码 </dt>
					<dd style="min-height:54px;">
						<input class="text valid" name="password" autocomplete="off" id="password" type="password">
						<label></label>
					</dd>
				</dl>
				<dl>
					<dt>确认密码 </dt>
					<dd style="min-height:54px;">
						<input class="text valid" name="repassword" autocomplete="off" id="password" type="password">
						<label></label>
					</dd>
				</dl>
				<dl>
					<dt>&nbsp;</dt>
					<dd>
						<input class="submit" value="立即注册" type="submit">
					</dd>
				</dl>
			</form>
			<dl class="mt10 mb10">
				<dt>&nbsp;</dt>
				<dd>我已经注册过账号，立即<a title="" href="<?php echo url('public/login',array('UsersID'=>$output['UsersID']));?>" class="register">登录</a></dd>
			</dl>
		</div>
		<div class="pre-login-bottom"></div>
	</div>
</div>
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $output['_site_url'];?>/static/pc/public/js/jquery.validation.min.js"></script>
<script>
$(document).ready(function() {
	$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
		rules: {
			mobile: 'required',
			password: 'required',
			repassword: {
                required : true,
                equalTo  : '#password'
            },
		},
		messages: {
			mobile: '手机号不能为空',
			password: '密码不能为空',
			repassword: {
                required : '确认密码不能为空',
                equalTo  : '密码不一致'
            },
		}
	});
});
</script>
</body>
</html>