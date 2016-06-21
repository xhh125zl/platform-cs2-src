<?php
require_once('../global.php');
if($_POST){
	if(!is_numeric($_POST['Province']) || !is_numeric($_POST['City']) || !is_numeric($_POST['Area'])){
		echo '<script language="javascript">alert("请选择所在地区");window.location="address_edit.php";</script>';
	}
	$Data=array(
		"Biz_RecieveProvince"=>$_POST['Province'],
		"Biz_RecieveCity"=>$_POST['City'],
		"Biz_RecieveArea"=>$_POST['Area'],
		"Biz_RecieveAddress"=>$_POST['Address'],
		"Biz_RecieveName"=>$_POST['Name'],
		"Biz_RecieveMobile"=>$_POST['Mobile']
	);

	$Flag=$DB->Set("biz",$Data,"where Biz_ID=".$_SESSION["BIZ_ID"]);
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="address_edit.php";</script>';
	}else{
		echo '<script language="javascript">alert("修改失败");history.back();</script>';
	}
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<link href="/static/css/select2.css" rel="stylesheet"/>
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src="/static/js/select2.js"></script>
<script type="text/javascript" src="/static/js/location.js"></script>
<script type="text/javascript" src="/static/js/area.js"></script>

</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/biz/js/shop.js'></script>
    <script language="javascript">
		$(document).ready(function(){
			showLocation(<?php echo $rsBiz["Biz_RecieveProvince"];?>,<?php echo $rsBiz["Biz_RecieveCity"];?>,<?php echo $rsBiz["Biz_RecieveArea"];?>);
			shop_obj.biz_edit_init();
		});
	</script>
    <div class="r_nav">
      <ul>
        <li><a href="account.php">商家资料</a></li>
        <li><a href="account_edit.php">修改资料</a></li>
		<li class="cur"><a href="address_edit.php">收货地址</a></li>
                 <li><a href="bind_user.php">绑定会员</a></li>

        <li><a href="account_password.php">修改密码</a></li>
        <li><a href="account_payconfig.php">结算配置</a></li>
      </ul>
    </div>
    <div id="bizs" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" method="post" action="?" id="biz_edit">
        <div class="rows">
          <label>所在地区</label>
          <span class="input">
			<select name="Province"  id="loc_province" style="width:120px">
				<option>选择省份</option>
			</select>&nbsp;
			<select name="City" id="loc_city" style="width:120px">
				<option>选择城市</option>
			</select>
			<select name="Area"  id="loc_town" style="width:120px">
				<option>选择区县</option>
			</select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>详细地址</label>
          <span class="input">
          <input name="Address" id="Address" value="<?php echo $rsBiz["Biz_RecieveAddress"];?>" type="text" class="form_input" size="40" maxlength="100" notnull><font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>收货人</label>
          <span class="input">
          <input type="text" name="Name" value="<?php echo $rsBiz["Biz_RecieveName"];?>" class="form_input" size="35" notnull/>
          <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>收货人电话</label>
          <span class="input">
          <input type="text" name="Mobile" value="<?php echo $rsBiz["Biz_RecieveMobile"];?>" class="form_input" size="35" notnull/>
          <font class="fc_red">*</font>
          </span>
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