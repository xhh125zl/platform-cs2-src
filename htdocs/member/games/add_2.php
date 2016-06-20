<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
if($_POST){
	//开始事务定义
	$ModelID = $_POST["ModelID"];
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$_POST["Rules"] = str_replace('<','&lt;',$_POST["Rules"]);
	$_POST["Rules"] = str_replace('>','&gt;',$_POST["Rules"]);
	$_POST["Rules"] = str_replace('"','&quot;',$_POST["Rules"]);
	$_POST["AttentionLink"] = str_replace('<','&lt;',$_POST["AttentionLink"]);
	$_POST["AttentionLink"] = str_replace('>','&gt;',$_POST["AttentionLink"]);
	$_POST["AttentionLink"] = str_replace('"','&quot;',$_POST["AttentionLink"]);
	$Data=array(
		"Model_ID"=>$ModelID,
		"Users_ID"=>$_SESSION["Users_ID"],
		"Games_Name"=>$_POST["Name"],
		"Games_KeyWords"=>$_POST["ReplyKeyword"],
		"Games_IsClose"=>isset($_POST["IsClose"]) ? 1 : 0,
		"Games_Pattern"=>$_POST["Pattern"],
		"Games_ScoreRules"=>$_POST["Pattern"]==1 ? json_encode((isset($_POST["Integral"])?$_POST["Integral"]:array()),JSON_UNESCAPED_UNICODE) : '',
		"Games_AttentionImg"=>$_POST["AttentionImgPath"],
		"Games_AttentionLink"=>$_POST["AttentionLink"],
		"Games_Sorts"=>$_POST["Sorts"],
		"Games_Rules"=>$_POST["Rules"],
		"Games_CreateTime"=>time()	
	);
	$Add=$DB->Add("games",$Data);
	$TableID=$DB->insert_id();
	$Flag=$Flag&&$Add;
	
	$Material=array(
		"Title"=>$_POST["ReplyTitle"],
		"ImgPath"=>$_POST["ReplyImgPath"],
		"TextContents"=>$_POST["ReplyBriefDescription"],
		"Url"=>"/api/".$_SESSION["Users_ID"]."/games/detail/".$TableID."/"
	);
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Material_Table"=>"games",
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
		"Reply_Table"=>"games",
		"Reply_TableID"=>$TableID,
		"Reply_Display"=>0,
		"Reply_Keywords"=>$_POST["ReplyKeyword"],
		"Reply_PatternMethod"=>1,
		"Reply_MsgType"=>1,
		"Reply_MaterialID"=>$MaterialID,
		"Reply_CreateTime"=>time()
	);
	$Add=$DB->Add("wechat_keyword_reply",$Data);
	$Flag=$Flag&&$Add;
		
	if($Flag){
		mysql_query("commit");
		$Data=array(
			"status"=>1,
			"url"=>'lists.php',
			"msg"=>"保存成功"
		);
	}else{
		mysql_query("roolback");
		$Data=array(
			"status"=>0,
			"msg"=>"添加失败"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}else{
	$ModelID = isset($_GET["ModelID"]) ? $_GET["ModelID"] : 0;
	$model = $DB->GetRs("games_model","*","where Model_ID=".$ModelID);
	if(!$model){
		echo "此游戏不存在";
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
<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="Rules"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=games',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
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
    <script type='text/javascript' src='/static/member/js/games.js'></script>
    <div class="r_nav">
      <ul>
        <li><a href="config.php">基本设置</a></li>
		<li class="cur"><a href="lists.php">游戏管理</a></li>
      </ul>
    </div>
    <div id="games" class="r_con_wrap">
    
    <script language="javascript">$(document).ready(games_obj.games_edit_init);</script>
      <form id="games_form" class="r_con_form">
        <div class="rows">
          <label>重命名游戏名称</label>
          <span class="input">
          <input type="text" class="form_input" name="Name" value="<?php echo $model["Model_Name"];?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>触发关键词</label>
          <span class="input">
          <input type="text" class="form_input" name="ReplyKeyword" value="<?php echo $model["Model_Name"];?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息标题</label>
          <span class="input">
          <input type="text" class="form_input" name="ReplyTitle" value="<?php echo $model["Model_Name"];?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息封面</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input name="ReplyImgUpload" id="ReplyImgUpload" type="file" />
            </div>
            <div class="tips">图片建议尺寸：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ReplyImgDetail"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="ReplyBriefDescription" class="textarea">在直线下面点击宝宝们，不能让他们掉了</textarea>
          <span class="tips">显示在图文封面下方</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
			<label>关闭游戏</label>
			<span class="input"><input type="checkbox" value="1" name="IsClose"  /> <span class="tips">您可以使用本功能暂时关闭游戏</span></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>游戏模式</label>
			<span class="input">
              <select name='Pattern' >
                <option value='0' selected>推广模式</option>
                <option value='1' >积分模式</option>
              </select>
            </span>
			<div class="clear"></div>
		</div>
		<div class="rows integral">
			<label>获得积分</label>
			<span class="input">
				<table cellpadding="0" cellspacing="3">
					<tr>
						<td nowrap="nowrap" class="tips">5-10次：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="0" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">10-20次：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="0" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">20-35次：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="0" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">35-50次： 	</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="0" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">50次以上：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="0" /> <font class="fc_red">*</font></td>
					</tr>
				</table>
			</span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>提示关注图片</label>
			<span class="input">
				<span class="upload_file">
					<div>
						<div class="up_input"><input type="file" name="AttentionImgPathUpload" id="AttentionImgPathUpload" /></div>
						<div class="tips">图片尺寸：640*140px</div>
						<div class="clear"></div>
					</div>
					<div class="img" id="AttentionImgPathDetail"></div>
				</span>
			</span>
			<div class="clear"></div>
		</div>
        
		<div class="rows">
			<label>提示关注链接地址</label>
			<span class="input"><input type="text" class="form_input" name="AttentionLink" value="" maxlength="255" size="45" /> <span class="tips">显示在游戏结束页面最下方</span></span>
			<div class="clear"></div>
		</div>

        <div class="rows">
			<label>排序优先级</label>
			<span class="input"><input type="text" class="form_input" name="Sorts" value="0" size="5" notnull /></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>游戏规则</label>
			<span class="input"><textarea class="ckeditor" name="Rules" style="width:600px; height:300px;">在直线下面点击宝宝们，不能让他们掉了！</textarea></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label></label>
			<span class="input"><input type="submit" class="btn_ok" value="提交保存" name="submit_btn"><a href="lists.php" class="btn_cancel">返回</a></span>
			<div class="clear"></div>
		</div>
        <input type="hidden" name="ReplyImgPath" value="/static/api/games/images/cover_<?php echo $ModelID;?>.jpg" />
        <input type="hidden" name="AttentionImgPath" value="/static/api/games/images/subscribe.jpg" />
        <input type="hidden" name="ModelID" value="<?php echo $ModelID;?>">
      </form>
    </div>
  </div>
</div>
</body>
</html>