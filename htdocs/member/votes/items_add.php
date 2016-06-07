<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
if($_POST)
{
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$VotesID = $_POST["VotesID"];
	$r = $DB->GetRs("votes","*","where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID);
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Votes_ID"=>$VotesID,
		"Item_Title"=>$_POST["Title"],		
		"Item_Votes"=>empty($_POST["Votes"]) ? 0 : $_POST["Votes"],
		"Item_Sorts"=>empty($_POST["Sorts"]) ? 0 : $_POST["Sorts"],
		"Item_ImgPath"=>empty($_POST["ImgPath"]) ? '' : $_POST["ImgPath"],
		"Item_Intro"=>empty($_POST["Intro"]) ? '' : $_POST["Intro"],
		"Item_CreateTime"=>time()		
	);
	$Add=$DB->Add("votes_item",$Data);
	
	$Flag=$Flag&&$Add;
	$Data = array(
		"Votes_Votes"=>$r["Votes_Votes"]	+ (isset($_POST["Votes"]) ? $_POST["Votes"] : 0)
	);
	$Set = $DB->Set("votes",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID);
	$Flag=$Flag&&$Set;
	if($Flag){
		mysql_query("commit");
		echo '<script language="javascript">alert("添加成功");window.location="items.php?VotesID='.$VotesID.'";</script>';
	}else{
		mysql_query("roolback");
		echo '<script language="javascript">alert("添加失败");history.back();</script>';
	}
	exit;
}else{
	$VotesID=empty($_GET['VotesID'])?0:$_GET['VotesID'];
	$rsVotes = $DB->GetRs("votes","*","where Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$VotesID);
	if(!$rsVotes){
		echo "该投票不存在";
		exit;
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
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="Intro"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=votes_items',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
	});
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=votes',
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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <script type='text/javascript' src='/static/member/js/votes.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="votemanage.php">投票管理</a></li>
      </ul>
    </div>
    <div id="reserve" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">$(document).ready(votes_obj.votes_init);</script>
      
      <form action="items_add.php" method="post" class="r_con_form" id="votes_form">
      	<div class="rows">
          <label>投票选项</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>初始票数</label>
          <span class="input">
          <input type="text" class="form_input" name="Votes" value="" size="10" />
          </span>
          <div class="clear"></div>
        </div>
        <?php if($rsVotes["Votes_Pattern"]==0){?>
        <div class="rows">
          <label>图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：320*320px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail" style="padding-top:5px"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>介绍</label>
          <span class="input"><textarea class="ckeditor" name="Intro" style="width:600px; height:300px;"></textarea></span>
          <div class="clear"></div>
        </div>
        <?php }?>
        <div class="rows">
          <label>排序优先级</label>
          <span class="input">
          <input type="text" class="form_input" name="Sorts" value="" size="10" />
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>&nbsp;</label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="ImgPath" name="ImgPath" value="" />
        <input type="hidden" name="VotesID" value="<?php echo $VotesID;?>" />        
      </form>
    </div>
  </div>
</div>
</body>
</html>