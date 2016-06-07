<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){	
	$_POST['BaoZhengJin'] = str_replace('"','&quot;',$_POST['BaoZhengJin']);
	$_POST['BaoZhengJin'] = str_replace("'","&quot;",$_POST['BaoZhengJin']);
	$_POST['BaoZhengJin'] = str_replace('>','&gt;',$_POST['BaoZhengJin']);
	$_POST['BaoZhengJin'] = str_replace('<','&lt;',$_POST['BaoZhengJin']);
	
	$_POST['NianFei'] = str_replace('"','&quot;',$_POST['NianFei']);
	$_POST['NianFei'] = str_replace("'","&quot;",$_POST['NianFei']);
	$_POST['NianFei'] = str_replace('>','&gt;',$_POST['NianFei']);
	$_POST['NianFei'] = str_replace('<','&lt;',$_POST['NianFei']);
	
	$_POST['JieSuan'] = str_replace('"','&quot;',$_POST['JieSuan']);
	$_POST['JieSuan'] = str_replace("'","&quot;",$_POST['JieSuan']);
	$_POST['JieSuan'] = str_replace('>','&gt;',$_POST['JieSuan']);
	$_POST['JieSuan'] = str_replace('<','&lt;',$_POST['JieSuan']);
	
	$Data = array(
		"BaoZhengJin"=>$_POST['BaoZhengJin'],
		"NianFei"=>$_POST['NianFei'],
		"JieSuan"=>$_POST['JieSuan']
	);
		
	$Flag=$DB->Set("biz_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag){
		echo '<script language="javascript">alert("编辑成功");window.location="apply_config.php";</script>';
	}else{
		echo '<script language="javascript">alert("编辑失败");history.back();</script>';
	}
	exit;
}else{
	$item = $DB->GetRs("biz_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	if(!$item){
		$Data = array(
			"Users_ID"=>$_SESSION["Users_ID"],
			"BaoZhengJin"=>"",
			"NianFei"=>"",
			"JieSuan"=>""
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
	K.create('textarea[name="BaoZhengJin"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&UsersID=<?php echo $_SESSION['Users_ID'];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
	
	K.create('textarea[name="NianFei"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&UsersID=<?php echo $_SESSION['Users_ID'];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
	
	K.create('textarea[name="JieSuan"]', {
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
    <div class="r_nav">
      <ul>
        <li><a href="index.php">商家列表</a></li>
        <li><a href="group.php">商家分组</a></li>
		<li><a href="apply.php">入驻申请列表</a></li>
		<li class="cur"><a href="apply_config.php">入驻设置</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" id="group_edit" method="post" action="?">
        
        <div class="rows">
          <label>保证金</label>
          <span class="input">
          <textarea class="ckeditor" name="BaoZhengJin" style="width:700px; height:300px;"><?php echo $item["BaoZhengJin"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
		
		<div class="rows">
          <label>平台使用年费</label>
          <span class="input">
          <textarea class="ckeditor" name="NianFei" style="width:700px; height:300px;"><?php echo $item["NianFei"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
		
		<div class="rows">
          <label>产品供货价结算</label>
          <span class="input">
          <textarea class="ckeditor" name="JieSuan" style="width:700px; height:300px;"><?php echo $item["JieSuan"] ?></textarea>
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