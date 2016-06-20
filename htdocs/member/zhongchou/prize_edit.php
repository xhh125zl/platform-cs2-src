<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig = $DB->GetRs("zhongchou_config","*","where usersid='".$_SESSION["Users_ID"]."'");
if(!$rsConfig){
	header("location:config.php");
}
$prizeid=empty($_REQUEST['prizeid'])?0:$_REQUEST['prizeid'];
$item=$DB->GetRs("zhongchou_prize","*","where usersid='".$_SESSION["Users_ID"]."' and prizeid=".$prizeid);
if(!$item){
	echo '<script language="javascript">alert("要修改的信息不存在！");window.location="prize.php?projectid='.$projectid.'";</script>';
}
if($_POST){
	$money = empty($_POST["money"]) ? 0 : floatval($_POST["money"]);
	if($money<=0){
		echo '<script language="javascript">alert("支持金额必须大于0");history.back();</script>';
	}
	$maxtimes = empty($_POST["maxtimes"]) ? 0 : intval($_POST["maxtimes"]);
	if($maxtimes<0){
		echo '<script language="javascript">alert("每人最多支持次数不能为负数");history.back();</script>';
	}
	$Data=array(
		"money"=>$money,
		"title"=>$_POST["title"],
		"maxtimes"=>$maxtimes,
		"thumb"=>$_POST["ImgPath"],
		"introduce"=>$_POST["introduce"]
	);
	$Flag=$DB->Set("zhongchou_prize",$Data,"where usersid='".$_SESSION["Users_ID"]."' and prizeid=".$prizeid);
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="prize.php?projectid='.$item["projectid"].'";</script>';
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
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=zhongchou',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath').val(url);
					K('#ImgDetail').html('<img src="'+url+'" />');
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
    <script type='text/javascript' src='/static/member/js/zhongchou.js'></script>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
    <script language="javascript">$(document).ready(zhongchou_obj.form_submit);</script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="project.php">项目管理</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <form id="form_submit" class="r_con_form" method="post" action="prize_edit.php">
      	<div class="rows">
          <label>支持金额</label>
          <span class="input">
          <input name="money" value="<?php echo $item["money"];?>" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">* (支持金额必须大于零)</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>赠品名称</label>
          <span class="input">
          <input name="title" value="<?php echo $item["title"];?>" type="text" class="form_input" size="50" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>赠品图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：320*200px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail">
           <?php echo $item["thumb"] ? '<img src="'.$item["thumb"].'" style="margin-top:10px;">' : '';?>
          </div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>赠品简介</label>
          <span class="input">
           <textarea name="introduce" class="textarea"><?php echo $item["introduce"];?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人最多支持次数</label>
          <span class="input">
          <input name="maxtimes" value="<?php echo $item["maxtimes"];?>" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">(填0，表示不限制)</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="#" onclick="history.go(-1);" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $item["thumb"];?>" />
        <input type="hidden" name="prizeid" value="<?php echo $prizeid;?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>