<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$admin=$DB->GetRs("sysusers","*","where Users_ID='".$_SESSION["ADMINID"]."'");
if(empty($admin)){
	echo '<script language="javascript">alert("账号出现异常，请与本站联系！");window.location="javascript:history.back()";</script>';
	exit();
}
if($_POST){
	$opassword = trim($_POST["OPassWord"]);
	$npassword = trim($_POST["NPassWord"]);
	$qpassword = trim($_POST["QPassWord"]);
	if(!$opassword){
		echo '<script language="javascript">alert("请输入旧密码！");window.location="javascript:history.back()";</script>';
		exit();
	}
	if($admin["Users_Password"]!=md5($opassword)){
		echo '<script language="javascript">alert("输入的旧密码不匹配！");window.location="javascript:history.back()";</script>';
		exit();
	}
	if(!$npassword){
		echo '<script language="javascript">alert("请输入新密码！");window.location="javascript:history.back()";</script>';
		exit();
	}
	if($npassword != $qpassword){
		echo '<script language="javascript">alert("输入的新密码与确认密码不匹配！");window.location="javascript:history.back()";</script>';
		exit();
	}
	$Data=array(
		"Users_PassWord"=>md5($npassword)
	);
	$DB->Set("sysusers",$Data,"where Users_ID='".$_SESSION["ADMINID"]."'");
	echo '<script language="javascript">';
	echo 'alert("密码修改成功！");';
	echo '	window.open("index.php","_self");';
	echo '</script>';
	exit();
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>

<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
        <li class="cur"><a href="index.php">修改密码</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
		<form class="r_con_form" method="post" action="?">
        	<div class="rows_new">
                <label>登陆账号</label>
                <span class="input"><?php echo $admin["Users_Account"];?></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>旧登陆密码</label>
                <span class="input"><input type="password" name="OPassWord" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>新登陆密码</label>
                <span class="input"><input type="password" name="NPassWord" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>确认登陆密码</label>
                <span class="input"><input type="password" name="QPassWord" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
        </form>
     </div>
  </div>
</div>

</body>
</html>