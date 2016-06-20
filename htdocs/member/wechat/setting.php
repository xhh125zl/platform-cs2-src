<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

if($_POST){
	$_POST['copyright'] = str_replace('"','&quot;',$_POST['copyright']);
	$_POST['copyright'] = str_replace("'","&quot;",$_POST['copyright']);
	$Data=array(
		"sys_name"=>$_POST["name"],
		"sys_copyright"=>$_POST["copyright"],
		"sys_logo"=>$_POST["Img"],
		"sys_baidukey"=>$_POST["baidukey"]
	);
	$Flag = $DB->Set("setting",$Data,"where id=1");
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else{
		echo '<script language="javascript">alert("设置失败");history.go(-1);</script>';
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
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=admin&Users_ID=<?php echo $_SESSION["Users_ID"];?>',
		fileManagerJson : '/member/file_manager_json.php',
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
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
        <li class="cur"><a href="index.php">系统设置</a></li>
		<li><a href="sms.php">短信设置</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
		<form class="r_con_form" method="post" action="?">
        	<div class="rows">
                <label>系统名称</label>
                <span class="input"><input type="text" name="name" value="<?php echo $SiteName;?>" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
              <label>logo图</label>
              <span class="input"> <span class="upload_file">
              <div>
                <div class="up_input">
                  <input type="button" id="ImgUpload" value="上传图片" style="width:80px;" />
                </div>
                <div class="tips">图片建议尺寸：150*50px</div>
                <div class="clear"></div>
              </div>
              <div class="img" id="ImgDetail" style="padding-top:8px;">
              <?php if($SiteLogo){?>
                <img src="<?php echo $SiteLogo;?>" />
              <?php }?>
              </div>
              </span></span>
              <div class="clear"></div>
            </div>
            <div class="rows">
                <label>百度地图密钥</label>
                <span class="input"><input type="text" name="baidukey" value="<?php echo $ak_baidu;?>" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>版权信息</label>
                <span class="input"><textarea name="copyright" style="width:300px;" rows="5"><?php echo $Copyright;?></textarea><br /><font style="font-size:12px; color:red">注：支持HTML代码</font></span> 
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
                <div class="clear"></div>
            </div>
            <input type="hidden" name="Img" id="Img" value="<?php echo $SiteLogo;?>" />
        </form>
     </div>
  </div>
</div>
</body>
</html>