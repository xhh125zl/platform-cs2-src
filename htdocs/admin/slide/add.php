<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$DB->showErr=false;
if($_POST){
	$Data=array(
		"linkurl"=>$_POST["linkurl"],
		"thumb"=>$_POST["Img"],
		"listorder"=>isset($_POST["listorder"]) ? intval($_POST["listorder"]) : 0
	);
	$flag=$DB->Add("slide",$Data);
	if($flag){
		echo '<script language="javascript">alert("添加成功！");window.open("index.php","_self");</script>';
		exit();
	}else{
		echo '<script language="javascript">alert("添加失败！");window.location="javascript:history.back()";</script>';
		exit();
	}
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/admin/upload_json.php?TableField=admin&Users_ID=<?php echo $_SESSION["ADMINID"];?>',
		fileManagerJson : '/admin/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#Img').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#Img').val(url);
					K('#ImgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>

<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
#ImgDetail img{width:200px; height:100px}
</style>
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
	  <ul>
        <li><a href="index.php">幻灯片管理</a></li>
        <li class="cur"><a href="add.php">添加图片</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
        <form class="r_con_form" method="post" action="?">
        	<div class="rows">
                <label>图片链接</label>
                <span class="input"><input type="text" name="linkurl" value="" size="30" class="form_input" /> 注：已“http://”开头</span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
              <label>图片</label>
              <span class="input"> <span class="upload_file">
              <div>
                <div class="up_input">
                  <input type="button" id="ImgUpload" value="上传图片" style="width:80px;" />
                </div>
                <div class="tips">图片建议尺寸：640*320px</div>
                <div class="clear"></div>
              </div>
              <div class="img" id="ImgDetail" style="padding-top:8px;">
              </div>
              </span></span>
              <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>排序</label>
                <span class="input"><input type="text" name="listorder" value="" size="5" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
            <input type="hidden" name="Img" id="Img" value="" />
        </form>
    </div>
  </div>
</div>
</body>
</html>