<?php
require_once('../global.php');
if($_POST){
	if(empty($_POST["Width"])){
		echo '<script language="javascript">alert("请填写正确的运单模板宽度");history.back();</script>';
		exit;
	}
	
	if(empty($_POST["Height"])){
		echo '<script language="javascript">alert("请填写正确的运单模板高度");history.back();</script>';
		exit;
	}
	
	if(empty($_POST["Top"])){
		$_POST["Top"] = 0;
	}
	
	if(empty($_POST["Left"])){
		$_POST["Left"] = 0;
	}
	$Data=array(
		"usersid"=>$rsBiz["Users_ID"],
		"bizid"=>$_SESSION["BIZ_ID"],
		"title"=>$_POST["Title"],		
		"companyid"=>$_POST["Company"],
		"width"=>number_format($_POST["Width"],2,'.',''),
		"height"=>number_format($_POST["Height"],2,'.',''),
		"offset_top"=>number_format($_POST["Top"],2,'.',''),
		"offset_left"=>number_format($_POST["Left"],2,'.',''),
		"thumb"=>$_POST["Thumb"],
		"enabled"=>$_POST["Enabled"],
		"createtime"=>time()
	);
	$Flag=$DB->Add("shop_shipping_print_template",$Data);
	if($Flag){
		echo '<script language="javascript">alert("添加成功");window.location.href="printtemplate.php";</script>';
	}else{
		echo '<script language="javascript">alert("添加失败");history.back();</script>';
	}
	exit;
}else{
	$companys = array();
	$DB->Get("shop_shipping_company","Shipping_ID,Shipping_Name","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]);
	while($r = $DB->fetch_assoc()){
		$companys[$r["Shipping_ID"]] = $r;
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src='/biz/js/shipping.js'></script>
<script>
$(document).ready(shipping_obj.printtemplate_init);
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/biz/upload_json.php?TableField=printtemplate',
		fileManagerJson : '/biz/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ThumbUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#Thumb').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#Thumb').val(url);
					K('#ThumbDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})

</script>
</head>

<body>
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li><a href="config.php">运费设置</a></li>
        <li><a href="company.php">快递公司管理</a></li>
        <li><a href="template.php">快递模板</a></li>
		<li class="cur"><a href="printtemplate.php">运单模板</a></li>
      </ul>
    </div>
    <div id="printtemplate" class="r_con_wrap">
      
      <script language="javascript"></script>
      <form id="printtemplate_form" class="r_con_form" method="post" action="?">
        <div class="rows">
          <label>模板名称</label>
          <span class="input">
          <input name="Title" value="" type="text" class="form_input" size="40" maxlength="100" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>物流公司</label>
          <span class="input">
          <select name="Company" notnull>
          	<?php
            	foreach($companys as $key=>$value){
			?>
            <option value="<?php echo $value["Shipping_ID"];?>"><?php echo $value["Shipping_Name"];?></option>
            <?php }?>
          </select>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>模板图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ThumbUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">请上传扫描好的运单图片，图片尺寸必须与快递单实际尺寸相符</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ThumbDetail"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>宽度</label>
          <span class="input">
          <input name="Width" value="" type="text" class="form_input" size="10" notnull> mm
          <font class="fc_red">*</font><span class="tips"> 运单模板宽度，单位毫米（mm）</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>高度</label>
          <span class="input">
          <input name="Height" value="" type="text" class="form_input" size="10" notnull> mm
          <font class="fc_red">*</font><span class="tips"> 运单模板高度，单位毫米（mm）</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>上偏移量</label>
          <span class="input">
          <input name="Top" value="" type="text" class="form_input" size="10" notnull> mm
          <font class="fc_red">*</font><span class="tips"> 运单模板上偏移量，单位毫米（mm）</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>左偏移量</label>
          <span class="input">
          <input name="Left" value="" type="text" class="form_input" size="10" notnull> mm
          <font class="fc_red">*</font><span class="tips"> 运单模板左偏移量，单位毫米（mm）</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
            <label>是否启用</label>
            <span class="input">
                <input type="radio" name="Enabled" value="0" checked="checked" />否&nbsp;&nbsp;
                <input type="radio" name="Enabled" value="1"  />是
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="Thumb" name="Thumb" value="" />
      </form>
    </div>
  </div>
</div>
</body>
</html>