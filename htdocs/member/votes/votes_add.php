<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
if($_POST)
{
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime=strtotime($Time[0]);
	$EndTime=strtotime($Time[1]);
	$_POST['ListType'] = 0;
	$Data=array(
		"Votes_Title"=>$_POST["Name"],
		"Votes_Keyword"=>$_POST["Keywords"],
		"Votes_Intro"=>$_POST["TextContents"],
		"Votes_Pattern"=>$_POST["Pattern"],
		"Votes_BgColor"=>empty($_POST["BgColor"]) ? "" : $_POST["BgColor"],
		"Votes_StartTime"=>$StartTime,
		"Votes_EndTime"=>$EndTime,
		"Votes_TotalCounts"=>empty($_POST["TotalCounts"]) ? 0 : $_POST["TotalCounts"] ,
		"Votes_DayCounts"=>empty($_POST['DayCounts']) ? 0 : $_POST['DayCounts'],
		"Votes_ListType"=>$_POST['ListType'],
		"Votes_Banner"=>$_POST['Banner'],		
		"Votes_Votes"=>0,
		"Votes_CreateTime"=>time(),
		"Users_ID"=>$_SESSION["Users_ID"]
	);
	$Add=$DB->Add("votes",$Data);
	$TableID=$DB->insert_id();
	$Flag=$Flag&&$Add;
	
	$Material=array(
		"Title"=>$_POST["Title"],
		"ImgPath"=>$_POST["ImgPath"],
		"TextContents"=>$_POST["TextContents"],
		"Url"=>"/api/".$_SESSION["Users_ID"]."/votes/".$TableID."/"
	);
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Material_Table"=>"votes",
		"Material_TableID"=>$TableID,
		"Material_Display"=>0,
		"Material_Type"=>0,
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE),
		"Material_CreateTime"=>time()
	);
	$Add=$DB->Add("wechat_material",$Data);
	$MaterialID=$DB->insert_id();
	$Flag=$Flag&&$Add;
	
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Reply_Table"=>"votes",
		"Reply_TableID"=>$TableID,
		"Reply_Display"=>0,
		"Reply_Keywords"=>$_POST["Keywords"],
		"Reply_PatternMethod"=>0,
		"Reply_MsgType"=>1,
		"Reply_MaterialID"=>$MaterialID,
		"Reply_CreateTime"=>time()
	);
	$Add=$DB->Add("wechat_keyword_reply",$Data);
	$Flag=$Flag&&$Add;
		
	if($Flag){
		mysql_query("commit");
		echo '<script language="javascript">alert("添加成功");window.location="votemanage.php";</script>';
	}else{
		mysql_query("roolback");
		echo '<script language="javascript">alert("添加失败");history.back();</script>';
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
		uploadJson : '/member/upload_json.php?TableField=reserve',
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
	K('#BannerUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#Banner').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#Banner').val(url);
					K('#BannerDetail').html('<img src="'+url+'" />');
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
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
      <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
      <script type='text/javascript' src='/static/js/plugin/colorpicker/js/colorpicker.js' ></script>
	  <link href='/static/js/plugin/colorpicker/css/colorpicker.css' rel='stylesheet' type='text/css'  />
      <script language="javascript">$(document).ready(votes_obj.votes_init);</script>
      
      <form action="votes_add.php" method="post" class="r_con_form" id="votes_form">
      	<div class="rows">
          <label>投票名称</label>
          <span class="input">
          <input type="text" class="form_input" name="Name" value="" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>触发关键词</label>
          <span class="input">
          <input type="text" class="form_input" name="Keywords" value="" size="25" notnull />
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息标题</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息封面</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail" style="padding-top:5px">
           <img src="/static/api/images/cover_img/votes.jpg" />
          </div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="TextContents" class="textarea"></textarea><br>
          <span class="tips">显示在图文封面下方</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>投票形式</label>
          <span class="input">
           <input type="radio" name="Pattern" value="0" id="p_0" checked /><label for="p_0">图片形式</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="Pattern" value="1" id="p_1" /><label for="p_1">文字形式</label>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>背景颜色</label>
          <span class="input">
          <input type="text" class="form_input" name="BgColor" value="" maxlength="100" size="10" />
          </span>
          <div class="clear"></div>
        </div> 
        <div class="rows">
          <label>起止时间</label>
          <span class="input">
          <input name="Time" type="text" value="" class="form_input" size="42" readonly="readonly" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>       
        <div class="rows">
          <label>每人可投票的总次数</label>
          <span class="input">
          <input type="text" class="form_input" name="TotalCounts" value="" maxlength="100" size="10" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人对某选项可投票的总次数</label>
          <span class="input">
          <input type="text" class="form_input" name="DayCounts" value="" maxlength="100" size="10" />
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows" id="photo">
          <label>Banner图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="BannerUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片宽度建议：640px*自定义</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="BannerDetail" style="padding-top:5px"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>&nbsp;</label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="ImgPath" name="ImgPath" value="/static/api/images/cover_img/votes.jpg" />
        <input type="hidden" id="Banner" name="Banner" value="" />        
      </form>
    </div>
  </div>
</div>
</body>
</html>