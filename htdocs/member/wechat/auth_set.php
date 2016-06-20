<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if($_POST)
{	
	$Data=array(
		"Users_WechatAppId"=>$_POST["WechatAppId"],
		"Users_WechatAppSecret"=>$_POST["WechatAppSecret"],
		"Users_WechatAuth"=>isset($_POST["WechatAuth"])?1:0,
		"Users_WechatVoice"=>isset($_POST["WechatVoice"])?1:0,
	);
	$Flag=$DB->Set("users",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag)
	{
		echo '<script language="javascript">alert("保存成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else
	{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
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
        <li class=""><a href="/member/wechat/token_set.php">微信接口配置</a></li>
      </ul>
    </div>
    <div id="wechat_info" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(wechat_obj.auth_init);</script>
      <div class="tips_info"> 1. 您的公众平台帐号类型必须为<span>服务号</span>。<br />
        2. 在公众平台申请接口使用的<span>AppId</span>和<span>AppSecret</span>，然后填入下边表单。 </div>
      <form action="auth_set.php" method="post" class="r_con_form">
        <div class="rows">
          <label>AppId <span class="fc_red">*</span></label>
          <span class="input">
          <input name="WechatAppId" value="<?php echo $rsUsers["Users_WechatAppId"] ?>" type="text" class="form_input" size="35" maxlength="18" notnull>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>AppSecret <span class="fc_red">*</span></label>
          <span class="input">
          <input name="WechatAppSecret" value="<?php echo $rsUsers["Users_WechatAppSecret"] ?>" type="text" class="form_input" size="35" maxlength="32" notnull>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>微信认证</label>
          <span class="input">
          <input type="checkbox" value="1" name="WechatAuth"<?php echo $rsUsers["Users_WechatAuth"]?" checked":""; ?> />
          <span class="tips">如果您的公众号已通过微信认证，请勾选此选项</span><br />
          <input type="checkbox" value="1" name="WechatVoice"<?php echo $rsUsers["Users_WechatVoice"]?" checked":""; ?> />
          <span class="tips">开启语音关键词回复，需同时开启微信认证选项</span>
          <div class="oauth_tips">
            <h1><strong>这个有什么用？</strong></h1>
            通过微信认证的公众号可以使用微信最新推出的9大新接口<br />
            请在微信公众平台高级接口处的"<span class="fc_red">OAuth2.0网页授权</span>​"设置授权回调页面域名为"<span class="fc_red"><?php echo $_SERVER['HTTP_HOST'];?></span>"<br />
            <span class="fc_red">开微信认证选项后，客户端中所有需要用户登录的页面，将直接读取用户的微信资料进行一键登录，免去用户注册的步骤</span><br />
            <span class="fc_red">开启语音关键词回复，您的微信帐号必须已通过微信认证并在高级接口中开启了语音识别，系统将自动识别出语音内容并启用模糊匹配方式进行关键字回复</span> </div>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          </span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>