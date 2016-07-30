<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

if($_POST) {
	if(!isset($_POST["img"])){
		echo '<script language="javascript">alert("请上传图片");history.back();</script>';
		exit;
	}


	$Data=array(
		"Users_ID"=>$_SESSION['Users_ID'],
		"slide_index"=>$_POST['index'],
		"slide_title"=>$_POST['title'],
		"slide_link"=>$_POST['link'],
		"slide_img"=>$_POST['img'],
	);
  	
	$Flag = $DB->Add("cloud_slide",$Data);
	
	if($Flag)
	{
		echo '<script language="javascript">alert("添加成功");window.location="slide_list.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<script>
$(document).ready(shop_obj.products_init);
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		if(K('#PicDetail').children().length>1){
			alert('只能上传一张图片');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a> <span>删除</span><input type="hidden" name="img" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#PicDetail div span').click(function(){
		K(this).parent().remove();
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
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="slide_list.php">首页幻灯片</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
			<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
			<form id="product_add_form" class="r_con_form" method="post" action="slide_add.php">
				<div class="rows">
					<label>名称</label>
					<span class="input">
					<input type="text" name="title" value="" class="form_input" size="35" maxlength="100"/>
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="rows">
					<label>排序</label>
					<span class="input">
					<input type="text" name="index" value="0" class="form_input" size="10" maxlength="100" />
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="rows">
					<label>产品图片</label>
					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">上传<span id="pic_count">1</span>张图片，图片大小建议：640*180像素</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail"></div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>Url链接</label>
					<span class="input">
					<input type="text" name="link" value="" class="form_input" size="35" maxlength="200"/>&nbsp;<span style="color:#666">例如：http://<?php echo $_SERVER['HTTP_HOST'];?></span>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label></label>
					<span class="input">
					<input type="submit" class="btn_green" name="submit_button" value="提交保存" />
					<a href="" class="btn_gray">返回</a></span>
					<div class="clear"></div>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>