<?php
require_once('../global.php');
if($_POST){
	if(!is_numeric($_POST['Province']) || !is_numeric($_POST['City']) || !is_numeric($_POST['Area'])){
		echo '<script language="javascript">alert("请选择所在地区");window.location="account_edit.php";</script>';
	}
	$_POST['Introduce'] = htmlspecialchars($_POST['Introduce'], ENT_QUOTES);
	$_POST['Kfcode'] = htmlspecialchars($_POST['Kfcode'], ENT_QUOTES);
	$Data=array(
		"Biz_Province"=>empty($_POST['Province']) ? 0 : $_POST['Province'],
		"Biz_City"=>empty($_POST['City']) ? 0 : $_POST['City'],
		"Biz_Area"=>empty($_POST['Area']) ? 0 : $_POST['Area'],
		"Biz_Address"=>$_POST['Address'],
		"Biz_Introduce"=>$_POST['Introduce'],
		"Biz_Contact"=>$_POST['Contact'],
		"Biz_Phone"=>$_POST['Phone'],
		"Biz_SmsPhone"=>$_POST['SmsPhone'],
		"Biz_Email"=>$_POST['Email'],
		"Biz_Homepage"=>$_POST['Homepage'],
		"Biz_Logo"=>$_POST['LogoPath'],
		"Biz_Kfcode"=>$_POST['Kfcode']
	);

	$Flag=$DB->Set("biz",$Data,"where Biz_ID=".$_SESSION["BIZ_ID"]);
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="account.php";</script>';
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src="/static/js/select2.js"></script>
<script type="text/javascript" src="/static/js/location.js"></script>
<script type="text/javascript" src="/static/js/area.js"></script>
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="Introduce"]', {
        themeType : 'simple',
		filterMode : false,
        uploadJson : '/biz/upload_json.php?TableField=biz',
        fileManagerJson : '/biz/file_manager_json.php',
        allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
    });
	var editor = K.editor({
		uploadJson : '/biz/upload_json.php?TableField=biz',
		fileManagerJson : '/biz/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#LogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#LogoPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#LogoPath').val(url);
					K('#LogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
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
			showLocation(<?php echo $rsBiz["Biz_Province"];?>,<?php echo $rsBiz["Biz_City"];?>,<?php echo $rsBiz["Biz_Area"];?>);
			shop_obj.biz_edit_init();
		});
    </script>
    <div class="r_nav">
      <ul>
        <li><a href="account.php">商家资料</a></li>
        <li class="cur"><a href="account_edit.php">修改资料</a></li>
		<li><a href="address_edit.php">收货地址</a></li>
        <li><a href="account_password.php">修改密码</a></li>
      </ul>
    </div>
    <div id="bizs" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" method="post" action="?" id="biz_edit">
        <div class="rows">
          <label>商家名称</label>
          <span class="input">
          <input type="text" name="Name" value="<?php echo $rsBiz["Biz_Name"];?>" class="form_input" size="35" maxlength="50" readonly/>
          </span>
          <div class="clear"></div>
        </div>
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
          <label>商家地址</label>
          <span class="input">
          <input name="Address" id="Address" value="<?php echo $rsBiz["Biz_Address"];?>" type="text" class="form_input" size="40" maxlength="100" notnull><font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家logo</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="LogoUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：100*100px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="LogoDetail" style="margin-top:8px">
          <?php 
		  if($rsBiz["Biz_Logo"]){
			  echo '<img src="'.$rsBiz["Biz_Logo"].'" />';
          }
		  ?>
          </div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>接受短信手机</label>
          <span class="input">
          <input type="text" name="SmsPhone" value="<?php echo $rsBiz["Biz_SmsPhone"];?>" class="form_input" size="30" pattern="[0-9]*" notnull/>
          <font class="fc_red">*</font> <span class="tips">当用户下单时，系统会自动发短信到该手机</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>联系人</label>
          <span class="input">
          <input type="text" name="Contact" value="<?php echo $rsBiz["Biz_Contact"];?>" class="form_input" size="35" notnull/>
          <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>联系电话</label>
          <span class="input">
          <input type="text" name="Phone" value="<?php echo $rsBiz["Biz_Phone"];?>" class="form_input" size="35" notnull/>
          <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>电子邮箱</label>
          <span class="input">
          <input type="text" name="Email" value="<?php echo $rsBiz["Biz_Email"];?>" class="form_input" size="35"/>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>公司主页</label>
          <span class="input">
          <input type="text" name="Homepage" value="<?php echo $rsBiz["Biz_Homepage"];?>" class="form_input" size="35" />
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>店铺客服代码</label>
          <span class="input">
          <textarea name="Kfcode" class="briefdesc"><?php echo $rsBiz["Biz_Kfcode"];?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家介绍</label>
          <span class="input">
          <textarea class="ckeditor" name="Introduce" style="width:600px; height:300px;"><?php echo $rsBiz["Biz_Introduce"];?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="LogoPath" name="LogoPath" value="<?php echo $rsBiz["Biz_Logo"];?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>