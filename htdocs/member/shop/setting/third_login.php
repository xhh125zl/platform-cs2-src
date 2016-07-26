<?php 
set_error_handler('myerror',E_STRICT);
error_reporting(7);

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');


$DB->showErr=TRUE;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if ($_POST) {

	$users_id = $_SESSION["Users_ID"];

	//微信登录
	$row = $DB->GetRs("third_login_config", '*', "WHERE users_id='" . $users_id . "' AND type='weixin'");
	$v = $_POST['config']['weixin'];

	if (!empty($row)) {

		$data = [
			'appid' => $v['appid'],
			'secret' => $v['secret'],
			'state' => $v['state'] ? 1 : 0,
		];

		$ret1 = $DB->Set('third_login_config', $data, "WHERE users_id='" . $users_id . "' AND type='weixin'");
	} else {
		$data = [
			'appid' => $v['appid'],
			'secret' => $v['secret'],
			'state' => $v['state'] ? 1 : 0,
			'type' => 'weixin',
			'users_id' => $users_id,
		];
		$ret1 = $DB->Add('third_login_config', $data);
	}
	

	//qq
	$row = $DB->GetRs("third_login_config", '*', "WHERE users_id='" . $users_id . "' AND type='qq'");
	$v = $_POST['config']['qq'];

	if (!empty($row)) {

		$data = [
			'appid' => $v['appid'],
			'secret' => $v['secret'],
			'state' => $v['state'] ? 1 : 0,
			'type' => 'qq',
		];

		$ret1 = $DB->Set('third_login_config', $data, "WHERE users_id='" . $users_id . "' AND type='qq'");
	} else {
		$data = [
			'appid' => $v['appid'],
			'secret' => $v['secret'],
			'state' => $v['state'] ? 1 : 0,
			'type' => 'qq',
			'users_id' => $users_id,
		];
		$ret1 = $DB->Add('third_login_config', $data);
	}

	
	echo '<script language="javascript">alert("保存成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	
	exit;
}else{

	$query =$DB->Get("third_login_config", "*", "WHERE users_id='".$_SESSION["Users_ID"]."' LIMIT 2");

	$rsConfig = [];
	while($row = $DB->fetch_assoc($query)) {
		$rsConfig[$row['type']] = $row;
	}

}
?>


<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/shop.js?date=20140731'></script>
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
<script type='text/javascript'>
$(document).ready(shop_obj.pay_shipping_config_init);
</script>
<style type="text/css">
#web_payment_form .up_input{width:100px; position:absolute; top:8px; right:80px}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js?date=20140731'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="shopping.php">三方登录</a></li>
		
      </ul>
    </div>
    <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script> 
    <script language="javascript">//$(document).ready(shop_obj.shopping_init);</script>
    <div id="shopping" class="r_con_wrap">
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">

        <tbody>
          <tr>
            <td valign="top" class="payment" width="34%"><form action="" method="post" id="web_payment_form"  >
                <ul>
                
							<li style="display:none">
								<h1><label>微信登录<span><input type="checkbox" value="1" id="check_0" name="config[weixin][state]" <?php echo $rsConfig["weixin"]['state'] ? 'checked' : '';?> />启用</span></label></h1>
								<dl id="pay_0" style="display:<?php echo $rsConfig["weixin"]['state'] ? 'block' : 'none';?>">
									<dd>商户APPID：<input type="text" name="config[weixin][appid]" value="<?php echo $rsConfig["weixin"]['appid']; ?>" maxlength="10" /></dd>
									<dd>&nbsp;密钥Secret：<input type="text" name="config[weixin][secret]" value="<?php echo $rsConfig["weixin"]['secret']; ?>" maxlength="32" /></dd>
								</dl>
							</li>
							<li>
								<h1><label>QQ登录<span><input type="checkbox" value="1"  id="check_1" name="config[qq][state]" <?php echo $rsConfig["qq"]['state'] ? 'checked' : '';?> />启用</span></label></h1>
								<dl id="pay_1" style="display:<?php echo $rsConfig["qq"]['state'] ? 'block' : 'none';?>">
									<dd>&nbsp;&nbsp;商户APPID：<input type="text" name="config[qq][appid]" value="<?php echo $rsConfig["qq"]['appid'] ?>" /></dd>
									<dd>&nbsp;&nbsp;私钥Secret：<input type="text" name="config[qq][secret]" value="<?php echo $rsConfig["qq"]['secret'] ?>" /></dd>
								</dl>
							</li>
                    
						</ul>
                <div class="submit">
                  <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
                </div>
                <input type="hidden" name="action" value="payment">
              </form></td>
            <td width="33%"></td>
            
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	$("h1 span input").click(function(){
		$(this).parent().parent().parent().parent().find("dl").toggle();	
	})

})
</script>
</body>
</html>