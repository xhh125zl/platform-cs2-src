<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

require_once('vertify.php');
$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='{$UsersID}'");
if(!$rsConfig){
	header("location:config.php");
}

if($_POST){	
	$Data=array(		
		"rules"=>$_POST['Description']
	);
	$Flag=$DB->Set("hongbao_config",$Data,"where usersid='{$UsersID}'");
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location="rules.php";</script>';
	}else{
		echo '<script language="javascript">alert("设置失败");window.location="rules.php";</script>';
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
        K.create('textarea[name="Description"]', {
            themeType : 'simple',
			filterMode : false,
            uploadJson : '/member/upload_json.php?TableField=web_column',
            fileManagerJson : '/member/file_manager_json.php',
            allowFileManager : true,
			items : [
				'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
        });
    });
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <script type='text/javascript' src='/static/member/js/hongbao.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
		<li class="cur"><a href="rules.php">活动规则</a></li>
        <li class=""><a href="prize.php">红包管理</a></li>
		<li class=""><a href="users.php">用户列表</a></li>
      </ul>
    </div>
    <div id="rules" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">$(document).ready(hongbao_obj.form_submit);</script>
      <form class="r_con_form" method="post" action="rules.php" id="form_submit">
        <div class="rows" id="Description_rows">
          <label>活动规则</label>
          <span class="input">
          <textarea name="Description" style="width:100%;height:400px;visibility:hidden;"><?php echo $rsConfig["rules"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="#" onclick="history.go(-1);" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>