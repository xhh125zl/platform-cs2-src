<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}

if($_POST){
	$Data=array(
		"alipay_partner"=>$_POST['alipay_partner'],
		"alipay_key"=>$_POST['alipay_key'],
		"alipay_selleremail"=>$_POST['alipay_selleremail']
	);
	$DB->Set("setting",$Data,"where id=1");
	echo '<script language="javascript">';
	echo 'alert("设置成功！");';
	echo '	window.open("pay.php","_self");';
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
        <li><a href="index.php">系统设置</a></li>
		<li class="cur"><a href="pay.php">支付设置</a></li>
		<li><a href="sms.php">短信设置</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
		<form class="r_con_form" method="post" action="?">
        	
            <div class="rows">
            	<label>支付宝合作ID</label>
                <span class="input"><input type="text" name="alipay_partner" value="<?php echo $alipay_partner;?>" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
            	<label>支付宝密钥</label>
                <span class="input"><input type="text" name="alipay_key" value="<?php echo $alipay_key;?>" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
             <div class="rows">
            	<label>支付宝收款账号</label>
                <span class="input"><input type="text" name="alipay_selleremail" value="<?php echo $alipay_selleremail;?>" size="30" class="form_input" /></span>
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