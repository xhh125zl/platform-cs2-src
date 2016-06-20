<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
if($_POST){
	$price = array();
	if(!empty($_POST["min"])){
		foreach($_POST["min"] as $k=>$v){
			if($_POST["price"][$k]){
				$price[] = array(
					'min'=>$v,
					'max'=>empty($_POST["max"][$k]) ? 0 : $_POST["max"][$k],
					'price'=>$_POST["price"][$k]
				);
			}else{
				continue;
			}
		}
	}
	$Data=array(
		"sms_enabled"=>$_POST['sms_enabled'],
		"sms_account"=>$_POST['sms_account'],
		"sms_pass"=>$_POST['sms_pass'],
		"sms_sign"=>$_POST['sms_sign'],
		"sms_price"=>empty($price) ? '' : json_encode($price,JSON_UNESCAPED_UNICODE)
	);
	$DB->Set("setting",$Data,"where id=1");
	echo '<script language="javascript">';
	echo 'alert("设置成功！");';
	echo '	window.open("sms.php","_self");';
	echo '</script>';
	exit();
}else{
	$price = json_decode($setting["sms_price"],true);
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
<style type="text/css">#man_panel li.item{margin-right:5px; margin-top:5px;}</style>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
        <li><a href="index.php">系统设置</a></li>
		<li><a href="pay.php">支付设置</a></li>
		<li class="cur"><a href="sms.php">短信设置</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
		<form class="r_con_form" method="post" action="?">
        	
			<div class="rows">
                <label>是否启用</label>
                <span class="input">
                <label><input name="sms_enabled" type="radio" value="1"<?php echo $setting["sms_enabled"]==1 ? " checked" : "";?>>启用</label>&nbsp;&nbsp;
                <label><input name="sms_enabled" type="radio" value="0"<?php echo $setting["sms_enabled"]==0 ? " checked" : "";?>>关闭</label>
                </span>
                <div class="clear"></div>
            </div>
			
            <div class="rows">
            	<label>短信宝平台账号</label>
                <span class="input"><input type="text" name="sms_account" value="<?php echo $setting["sms_account"];?>" size="30" class="form_input" /><span class="tips">&nbsp;
				&nbsp;目前只支持“<font style="color:red">短信宝</font>”</span></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
            	<label>短信宝平台密码</label>
                <span class="input"><input type="text" name="sms_pass" value="<?php echo $setting["sms_pass"];?>" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
             <div class="rows">
            	<label>短信签名</label>
                <span class="input"><input type="text" name="sms_sign" value="<?php echo $setting["sms_sign"];?>" size="30" class="form_input" /><span class="tips">&nbsp;
				&nbsp;例如“<font style="color:red">【<?php echo $SiteName;?>】</font>”,必须加【】</span></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
			  <label>短信价格</label>
			  <span class="input">
				<a href="javascript:void(0);" id="add_man" class="red">添加</a>
				<ul id="man_panel">
				<?php
					if(!empty($price)){
						foreach($price as $k=>$v){
				?>
					<li class="item">
					<input name="min[]" value="<?php echo $v["min"];?>" class="form_input" size="3" maxlength="10" type="text">
					< 购买条数 <=
					<input name="max[]" value="<?php echo $v["max"];?>" class="form_input" size="3" maxlength="10" type="text">&nbsp;&nbsp; 价格
					<input name="price[]" value="<?php echo $v["price"];?>" class="form_input" size="3" maxlength="10" type="text"> 元/条
					<a><img src="/static/member/images/ico/del.gif" hspace="5"></a>
					</li>
				<?php }}else{?>
					<li class="item">
					<input name="min[]" value="" class="form_input" size="3" maxlength="10" type="text">
					< 购买条数 <=
					<input name="max[]" value="" class="form_input" size="3" maxlength="10" type="text">&nbsp;&nbsp; 价格
					<input name="price[]" value="" class="form_input" size="3" maxlength="10" type="text"> 元/条
					<a><img src="/static/member/images/ico/del.gif" hspace="5"></a>
					</li>
				<?php }?>
					<li class="clear"></li>
				</ul>
				<p><span class="tips">(注：客户购买短信价格设置，设置格式如100<购买条数<=200 价格0.1元/条，则表示购买数量在100-200条之间，每条0.1元；若1000条上，则格式为1000<购买条数<=0，并且其后面设置的价格方案无效)</span></p>
			  </span>
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
<script type="text/javascript">
$("#add_man").click(function(){
	var li_item = '<li class="item"><input name="min[]" value="" class="form_input" size="3" maxlength="10" type="text"> < 购买条数 <= <input name="max[]" value="" class="form_input" size="3" maxlength="10" type="text">&nbsp;&nbsp; 价格 <input name="price[]" value="" class="form_input" size="3" maxlength="10" type="text"> 元/条<a> <img src="/static/member/images/ico/del.gif" hspace="5"></a></li>';
	$("ul#man_panel").append(li_item);
});
		
$("#man_panel li.item a").live('click',function(){
	$(this).parent().remove();
});
</script>
</body>
</html>