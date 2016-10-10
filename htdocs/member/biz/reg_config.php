<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){	
	$_POST['join_desc'] = str_replace('"','&quot;',$_POST['join_desc']);
	$_POST['join_desc'] = str_replace("'","&quot;",$_POST['join_desc']);
	$_POST['join_desc'] = str_replace('>','&gt;',$_POST['join_desc']);
	$_POST['join_desc'] = str_replace('<','&lt;',$_POST['join_desc']);
	

	$Data = array(
		"join_desc"=>$_POST['join_desc'],
		"bannerimg"=>$_POST['bannerimg'],
	);
		
	$Flag=$DB->Set("biz_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag){
		echo '<script language="javascript">alert("编辑成功");window.location="reg_config.php";</script>';
	}else{
		echo '<script language="javascript">alert("编辑失败");history.back();</script>';
	}
	exit;
}else{
	$item = $DB->GetRs("biz_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	if(!$item){
		$Data = array(
			"Users_ID"=>$_SESSION["Users_ID"],
			"join_desc"=>"1",
		);
		$DB->Add("biz_config",$Data);
		$item = $Data;
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
<script type='text/javascript' src='/static/member/js/products_attr_helper.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>


KindEditor.ready(function(K) {
	K.create('textarea[name="join_desc"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&UsersID=<?php echo $_SESSION['Users_ID'];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
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
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
    <script type='text/javascript' src='/static/member/js/biz.js'></script>
    <script language="javascript">$(document).ready(biz_obj.group_edit);</script>
	<script>
	KindEditor.ready(function(K) {
			var editor = K.editor({
			uploadJson : '/member/upload_json.php?TableField=web_article',
			fileManagerJson : '/member/file_manager_json.php',
			showRemote : true,
			allowFileManager : true,
		});
		K('#bannerimgUpload').click(function(){
			editor.loadPlugin('image', function(){
				editor.plugin.imageDialog({
					imageUrl : K('#bannerimgPath').val(),
					clickFn : function(url, title, width, height, border, align){
						K('#bannerimgPath').val(url);
						K('#bannerimgDetail').html('<img src="'+url+'" />');
						editor.hideDialog();
					}
				});
			});
		});
	})

	</script>
    <div class="r_nav">
      <ul>

	<li class=""><a href="apply_config.php">入驻描述设置</a></li>
	 <li class="cur"><a href="reg_config.php">注册页面设置</a></li>
        <li class=""><a href="apply_other.php">年费设置</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" id="group_edit" method="post" action="?">
        
        <div class="rows">
          <label>banner图</label>
          <span class="input">
          <div class="col-sm-5 must field">
                                    <input type="hidden" id="bannerimgPath" value="<?php echo !empty($item['bannerimg'])?$item['bannerimg']:''?>" data-validate="required:banner图必须填写" name="bannerimg" />
                                    <input type="button" id="bannerimgUpload" value="添加图片" style="width:80px;" /><span class="tips">&nbsp;&nbsp;尺寸：1920px*542px</span>
                                    <div class="img" id="bannerimgDetail" style="margin-top:8px">
                                    <?php 
                                            if(!empty($item["bannerimg"])){
                                                    echo '<img src="'.$item["bannerimg"].'" />';
                                    }
                                    ?>
                                    </div>
                                </div>
                                
                                <div class="col-sm-5">
                                    <p class="form-control-static">
                                        <span class="help-inline"></span>
                                    </p>
                                </div>
          </span>
          <div class="clear"></div>
        </div>
		
 
		
		<div class="rows">
          <label>为什么要加入我们描述</label>
          <span class="input">
          <textarea class="ckeditor" name="join_desc" style="width:700px; height:300px;"><?php echo $item["join_desc"] ?></textarea>
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