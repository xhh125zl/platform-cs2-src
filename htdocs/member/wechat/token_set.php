<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){
	$Data=array(
		"Users_WechatName"=>trim($_POST["WechatName"]),
		"Users_WechatEmail"=>trim($_POST["WechatEmail"]),
		"Users_WechatID"=>trim($_POST["WechatID"]),
		"Users_WechatAccount"=>trim($_POST["WechatAccount"]),
		"Users_EncodingAESKey"=>trim($_POST["EncodingAESKey"]),
		"Users_EncodingAESKeyType"=>$_POST["EncodingAESKeyType"],
		"Users_WechatAppId"=>trim($_POST["WechatAppId"]),
		"Users_WechatAppSecret"=>trim($_POST["WechatAppSecret"]),
		"Users_WechatAuth"=>isset($_POST["WechatAuth"])?1:0,
		"Users_WechatVoice"=>isset($_POST["WechatVoice"])?1:0,
		"Users_WechatType"=>$_POST["WechatType"]
	);
	$Flag=$DB->Set("users",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag){
		echo '<script language="javascript">alert("保存成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else{
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
      </ul>
    </div>
    <script language="javascript">$(document).ready(wechat_obj.set_token_init);</script>
    <div id="token" class="r_con_wrap">
      <div class="tips_info">
      	1. 在公众平台申请接口使用的AppId和AppSecret，然后填入下边表单。<br />
		2. <font style="color:#F00; font-size:12px;">服务认证号</font>请在<font style="color:#F00; font-size:12px;">微信公众平台开发者中心->网页服务->网页账号->网页授权获取用户基本信息</font>设置授权回调页面域名为 <font style="color:#F00; font-size:12px;"><?php echo $_SERVER["HTTP_HOST"];?></font> <br />
		3. 请在<font style="color:#F00; font-size:12px;">微信公众平台公众号设置->功能设置->JS接口安全域名</font>设置域名为 <font style="color:#F00; font-size:12px;"><?php echo $_SERVER["HTTP_HOST"];?></font>
      </div>
      <form class="r_con_form" action="token_set.php" method="post">
        <div class="set_token_msg"></div>
        <div class="rows">
          <label>接口URL</label>
          <span class="input"><span class="tips">http://<?php echo $_SERVER['HTTP_HOST'].'/api/'.$_SESSION["Users_ID"]; ?>/</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>接口Token</label>
          <span class="input"><span class="tips"><?php echo $rsUsers["Users_WechatToken"]; ?></span></span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>公众号类型</label>
          <span class="input">
			<input type="radio" name="WechatType" value="0" id="type_0"<?php echo $rsUsers["Users_WechatType"]==0 ? " checked" : "";?>/><label for="type_0">订阅号未认证</label>&nbsp;&nbsp;
			<input type="radio" name="WechatType" value="1" id="type_1"<?php echo $rsUsers["Users_WechatType"]==1 ? " checked" : "";?>/><label for="type_1">订阅号已认证</label>&nbsp;&nbsp;
			<input type="radio" name="WechatType" value="2" id="type_2"<?php echo $rsUsers["Users_WechatType"]==2 ? " checked" : "";?>/><label for="type_2">服务号未认证</label>&nbsp;&nbsp;
			<input type="radio" name="WechatType" value="3" id="type_3"<?php echo $rsUsers["Users_WechatType"]==3 ? " checked" : "";?>/><label for="type_3">服务号已认证</label>
		  </span>
          <div class="clear"></div>
        </div>
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
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>公众号名称</label>
          <span class="input">
          <input name="WechatName" id="WechatName" value="<?php echo $rsUsers["Users_WechatName"]; ?>" type="text" class="form_input" size="35" maxlength="100" notnull>
          <span class="tips">例如：网中网</span></span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>公众号邮箱</label>
          <span class="input">
          <input name="WechatEmail" id="WechatEmail" value="<?php echo $rsUsers["Users_WechatEmail"]; ?>" type="text" class="form_input" size="35" maxlength="100" notnull>
          <span class="tips">例如：1580435795@qq.com</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>公众号原始ID</label>
          <span class="input">
          <input name="WechatID" id="WechatID" value="<?php echo $rsUsers["Users_WechatID"]; ?>" type="text" class="form_input" size="35" maxlength="100" notnull>
          <span class="tips">例如：gh_69cee2d10997</span></span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>微信号</label>
          <span class="input">
          <input name="WechatAccount" id="WechatAccount" value="<?php echo $rsUsers["Users_WechatAccount"]; ?>" type="text" class="form_input" size="35" maxlength="100" notnull>
          <span class="tips">例如：wangzhongwang</span></span> </span>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>EncodingAESKey</label>
          <span class="input">
          <input name="EncodingAESKey" id="EncodingAESKey" value="<?php echo $rsUsers["Users_EncodingAESKey"]; ?>" type="text" class="form_input" size="35" maxlength="100" />          
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>消息加解密方式</label>
          <span class="input">
          <select name="EncodingAESKeyType" id="EncodingAESKeyType">
			  <option value="0"<?php echo $rsUsers["Users_EncodingAESKeyType"]==0 ? " selected" : "";?>>明文模式</option>
			  <option value="1"<?php echo $rsUsers["Users_EncodingAESKeyType"]==1 ? " selected" : "";?>>兼容模式</option>
			  <option value="2"<?php echo $rsUsers["Users_EncodingAESKeyType"]==2 ? " selected" : "";?>>安全模式（推荐）</option>
		  </select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="设置" />
          </span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>