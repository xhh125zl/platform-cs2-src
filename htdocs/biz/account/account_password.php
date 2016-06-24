<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["BIZ_ID"])){
	header("location:/biz/login.php");
}

if($_POST){
	$rsBiz=$DB->GetRs("biz","*","where Biz_ID=".$_SESSION["BIZ_ID"]);
	if(empty($_POST["PassWordY"])){
		echo '<script language="javascript">alert("请输入原始密码！");history.back();</script>';
		exit;
	}else{
		if($rsBiz["Biz_PassWord"] != md5($_POST["PassWordY"])){
			echo '<script language="javascript">alert("原始密码不正确！");history.back();</script>';
			exit;
		}else{
			if(empty($_POST["PassWord"])){
				echo '<script language="javascript">alert("请输入新密码");history.back();</script>';
				exit;
			}else{
				if($_POST["PassWord"]!=$_POST["PassWordA"]){
					echo '<script language="javascript">alert("商家登录密码与确认密码不相同！");history.back();</script>';
					exit();
				}else{
					$Data = array(
						"Biz_PassWord" => md5($_POST["PassWord"])
					);
					$Flag=$DB->Set("biz",$Data,"where Biz_ID=".$_SESSION["BIZ_ID"]);
					if($Flag){
						echo '<script language="javascript">alert("修改成功");window.location="account.php";</script>';
						exit;
					}else{
						echo '<script language="javascript">alert("修改失败");history.back();</script>';
						exit;
					}
				}
			}
		}
	}
}
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/biz/js/shop.js'></script>
    <script language="javascript">$(document).ready(shop_obj.biz_edit_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="account.php">商家资料</a></li>
        <li><a href="account_edit.php">修改资料</a></li>
		<li><a href="address_edit.php">收货地址</a></li>
             <li><a href="bind_user.php">绑定会员</a></li>
        <li class="cur"><a href="account_password.php">修改密码</a></li>
        <li><a href="account_payconfig.php">结算配置</a></li>
      </ul>
    </div>
    <div id="bizs" class="r_con_wrap">
      <form class="r_con_form" method="post" action="?" id="biz_edit">
        <div class="rows">
          <label>原始密码</label>
          <span class="input">
          <input type="password" name="PassWordY" value="" class="form_input" size="35" maxlength="50" notnull/>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>新密码</label>
          <span class="input">
          <input type="password" name="PassWord" value="" class="form_input" size="35" maxlength="50" notnull/>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>确认新密码</label>
          <span class="input">
          <input type="password" name="PassWordA" value="" class="form_input" size="35" maxlength="50" notnull/>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>