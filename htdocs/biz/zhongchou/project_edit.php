<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$itemid=empty($_REQUEST['itemid'])?0:$_REQUEST['itemid'];
$item=$DB->GetRs("zhongchou_project","*","WHERE usersid='{$UsersID}' AND Biz_ID={$BizID} AND itemid=".$itemid);
if(!$item){
	echo '<script language="javascript">alert("要修改的信息不存在！");window.location="project.php";</script>';
}

if($_POST){
	$amount = empty($_POST["Amount"]) ? 0 : floatval($_POST["Amount"]);
	if($amount<=0){
		echo '<script language="javascript">alert("筹款金额必须大于零");history.back();</script>';
	}
	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$fromtime=strtotime($Time[0]);
	$totime=strtotime($Time[1]);
	$_POST['Description'] = str_replace('"','&quot;',$_POST['Description']);
	$_POST['Description'] = str_replace("'","&quot;",$_POST['Description']);
	$_POST['Description'] = str_replace('>','&gt;',$_POST['Description']);
	$_POST['Description'] = str_replace('<','&lt;',$_POST['Description']);
	$Data=array(
		"title"=>$_POST["Title"],
		"thumb"=>$_POST["ImgPath"],
		"fromtime"=>$fromtime,
		"totime"=>$totime,
		"introduce"=>$_POST["Introduce"],
		"description"=>$_POST["Description"],
		"amount"=>$amount,
	    "status"=>0
	);
	$Flag=$DB->Set("zhongchou_project",$Data,"where usersid='{$UsersID}' AND Biz_ID={$BizID} AND itemid=$itemid");
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="project.php";</script>';
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
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=zhongchou',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true
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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <script type='text/javascript' src='/static/member/js/zhongchou.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="project.php">项目管理</a></li>
      </ul>
    </div>
    <div id="project" class="r_con_wrap"> 
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
      <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
      <script language="javascript">$(document).ready(zhongchou_obj.project_edit);</script>
      <form id="form_submit" action="project_edit.php" class="r_con_form" method="post">
        <div class="rows">
          <label>项目名称</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="<?php echo $item["title"];?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>形象图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：640*400px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail">
          <?php echo $item["thumb"] ? '<img src="'.$item["thumb"].'" />' : '';?>
          </div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动时间</label>
          <span class="input">
          <input name="Time" type="text" value="<?php echo date("Y-m-d H:i:s",$item["fromtime"])." - ".date("Y-m-d H:i:s",$item["totime"]) ?>" class="form_input" size="42" readonly notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>筹款金额</label>
          <span class="input">
          <input name="Amount" value="<?php echo $item["amount"];?>" type="text" class="form_input" size="15" maxlength="20" notnull> 元
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="Introduce" class="textarea"><?php echo $item["introduce"];?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>项目详情</label>
          <span class="input">
          <textarea  name="Description" id="Description"><?php echo $item["description"];?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="#" onClick="history.go(-1);" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>        
        <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $item["thumb"];?>" />
        <input type="hidden" name="itemid" value="<?php echo $itemid;?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>