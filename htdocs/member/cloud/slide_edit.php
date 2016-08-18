<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$id = empty($_REQUEST['id']) ? 0 : $_REQUEST['id'];
$rsSlide = $DB->GetRs("cloud_slide","*","where Users_ID='".$_SESSION["Users_ID"]."' and id=".$id);

if($_POST){
	if(!isset($_POST["img"])){
		echo '<script language="javascript">alert("请上传商品图片");history.back();</script>';
		exit;
	}
	
	$Data = array(
		"Users_ID"=>$_SESSION['Users_ID'],
		"slide_index"=>$_POST['index'],
		"slide_title"=>$_POST['title'],
		"slide_link"=>$_POST['link'],
		"slide_img"=>$_POST['img'],
	);
	
	$Flag = $DB->Set("cloud_slide",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and id=".$id);
	if($Flag) {
		echo '<script language="javascript">alert("修改成功");window.location="slide_list.php";</script>';
	}else {
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>


KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		if(K('#PicDetail').children().length>=5){
			alert('您上传的图片数量已经超过5张，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail').html('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a> <span>删除</span><input type="hidden" name="img" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#PicDetail div span').click(function(){
		K(this).parent().remove();
	});
})
function insertRow(){
	var newrow=document.getElementById('wholesale_price_list').insertRow(-1);
	newcell=newrow.insertCell(-1);
	newcell.innerHTML='数量： <input type="text" name="JSON[Wholesale]['+(document.getElementById('wholesale_price_list').rows.length-2)+'][Qty]" value="" class="form_input" size="5" maxlength="3" /> 价格：￥ <input type="text" name="JSON[Wholesale]['+(document.getElementById('wholesale_price_list').rows.length-2)+'][Price]" value="" class="form_input" size="5" maxlength="10" /><a href="javascript:;" onclick="document.getElementById(\'wholesale_price_list\').deleteRow(this.parentNode.parentNode.rowIndex);"> <img src="/static/member/images/ico/del.gif" hspace="5" /></a>';
}
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<script type='text/javascript' src='/static/member/js/shop.js'></script> 
		<script type='text/javascript'>
    	$(document).ready(shop_obj.products_edit_init);
        </script>
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="slide_list.php">首页幻灯片</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
			<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
			<form class="r_con_form" id="product_edit_form" method="post" action="slide_edit.php">
			    <input type="hidden" name="id" value="<?php echo $rsSlide["id"];?>"/>
				<div class="rows">
					<label>标题</label>
					<span class="input">
					<input type="text" name="title" value="<?php echo $rsSlide["slide_title"] ?>" class="form_input" size="35" maxlength="100" />
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>排序</label>
					<span class="input">
					<input type="text" name="index" value="<?php echo $rsSlide["slide_index"] ?>" class="form_input" size="10" maxlength="100" />
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
						<div class="tips">共可上传<span id="pic_count">1</span>张图片，图片大小建议：640*180像素</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail">
						<?php if(isset($rsSlide["slide_img"])){ ?>
						<div><a target="_blank" href="<?php echo $rsSlide["slide_img"] ?>"> <img src="<?php echo $rsSlide["slide_img"] ?>"></a><span>删除</span>
							<input type="hidden" name="img" value="<?php echo $rsSlide["slide_img"] ?>">
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>Url链接</label>
					<span class="input">
					<input type="text" name="link" value="<?php echo $rsSlide["slide_link"] ?>" class="form_input" size="35" maxlength="200"/>&nbsp;<span style="color:#666">例如：http://<?php echo $_SERVER['HTTP_HOST'];?></span>
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="rows">
					<label></label>
					<span class="input">
						<input type="hidden" name="id" value="<?php echo $rsSlide["id"] ?>">
						<input type="submit" class="btn_green" name="submit_button" value="提交保存" />
						<a href="" class="btn_gray">返回</a>
					</span>
					<div class="clear"></div>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>