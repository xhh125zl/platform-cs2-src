<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
if($_POST){
	//开始事务定义
	$GamesID = $_POST["GamesID"];
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
		"Games_Name"=>$_POST["Name"],
		"Games_KeyWords"=>$_POST["ReplyKeyword"],
		"Games_IsClose"=>isset($_POST["IsClose"]) ? 1 : 0,
		"Games_Pattern"=>$_POST["Pattern"],
		"Games_ScoreRules"=>$_POST["Pattern"]==1 ? json_encode((isset($_POST["Integral"])?$_POST["Integral"]:array()),JSON_UNESCAPED_UNICODE) : '',
		"Games_AttentionImg"=>$_POST["AttentionImgPath"],
		"Games_AttentionLink"=>$_POST["AttentionLink"],
		"Games_Sorts"=>$_POST["Sorts"],
		"Games_Rules"=>$_POST["Rules"],
		"Games_Json"=>json_encode((isset($_POST["Property"])?$_POST["Property"]:array()),JSON_UNESCAPED_UNICODE)
	);
	$Set=$DB->Set("games",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Games_ID=".$GamesID);
	$Flag=$Flag&&$Set;
	
	$Material=array(
		"Title"=>$_POST["ReplyTitle"],
		"ImgPath"=>$_POST["ReplyImgPath"],
		"TextContents"=>$_POST["ReplyBriefDescription"],
		"Url"=>"/api/".$_SESSION["Users_ID"]."/games/detail/".$GamesID."/"
	);
	$Data=array(
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE)
	);
	$Set=$DB->Set("wechat_material",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='games' and Material_TableID=".$GamesID);
	$Flag=$Flag&&$Set;
	
	$Data=array(
		"Reply_Keywords"=>$_POST["ReplyKeyword"]
	);
	$Set=$DB->Set("wechat_keyword_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='games' and Reply_TableID=".$GamesID);
	$Flag=$Flag&&$Set;
		
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
	$games = $DB->GetRs("games","*","where Users_ID='".$_SESSION["Users_ID"]."' and Model_ID=".$ModelID);
	if(!$model || !$games){
		echo "此游戏不存在";
		exit;
	}
	$ScoreRules = $games['Games_ScoreRules'] ? json_decode($games['Games_ScoreRules'],true) : array();
	$JSON = $games['Games_Json'] ? json_decode($games['Games_Json'],true) : array();
	$material=$DB->GetRs("wechat_material","Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='games' and Material_TableID=".$games["Games_ID"]);
	$Material_Json=json_decode($material['Material_Json'],true);
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
      <script language="javascript">
		  $(document).ready(function(){
			  global_obj.file_upload($('#_GameConfigImgUpload'), $('#games_form input[name=Property\\\[img\\\]]'), $('#_GameConfigImgDetail'));
			  $('#_GameConfigImgDetail').html(global_obj.img_link($('#games_form input[name=Property\\\[img\\\]]').val()));
			  
			  global_obj.file_upload($('#_GameConfigBgImgUpload'), $('#games_form input[name=Property\\\[bgimg\\\]]'), $('#_GameConfigImgDetail'));
			  $('#_GameConfigBgImgDetail').html(global_obj.img_link($('#games_form input[name=Property\\\[bgimg\\\]]').val()));
		  });
	  </script>
      <form id="games_form" class="r_con_form">
        <div class="rows">
          <label>重命名游戏名称</label>
          <span class="input">
          <input type="text" class="form_input" name="Name" value="<?php echo $games["Games_Name"];?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>触发关键词</label>
          <span class="input">
          <input type="text" class="form_input" name="ReplyKeyword" value="<?php echo $games["Games_KeyWords"];?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>图文消息标题</label>
          <span class="input">
          <input type="text" class="form_input" name="ReplyTitle" value="<?php echo !empty($Material_Json["Title"]) ? $Material_Json["Title"] : '' ;?>" maxlength="100" size="35" notnull />
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
          <div class="img" id="ReplyImgDetail">          	
          </div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="ReplyBriefDescription" class="textarea"><?php echo !empty($Material_Json["TextContents"]) ? $Material_Json["TextContents"] : '点击最下面的小苹果开始，20秒内看你能吃掉多少个小苹果！';?></textarea>
          <span class="tips">显示在图文封面下方</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
			<label>关闭游戏</label>
			<span class="input"><input type="checkbox" value="1" name="IsClose"<?php echo $games["Games_IsClose"]==1 ? ' checked' : '';?>/> <span class="tips">您可以使用本功能暂时关闭游戏</span></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>游戏模式</label>
			<span class="input">
              <select name='Pattern' >
                <option value='0'<?php echo $games["Games_Pattern"]==0 ? ' selected' : '';?>>推广模式</option>
                <option value='1'<?php echo $games["Games_Pattern"]==1 ? ' selected' : '';?>>积分模式</option>
              </select>
            </span>
			<div class="clear"></div>
		</div>
		<div class="rows integral"<?php echo $games["Games_Pattern"]==1 ? ' style="display:block"' : '';?>>
			<label>获得积分</label>
			<span class="input">
				<table cellpadding="0" cellspacing="3">
					<tr>
						<td nowrap="nowrap" class="tips">10-50个：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="<?php echo empty($ScoreRules[0]) ? 0 : $ScoreRules[0];?>" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">50-100个：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="<?php echo empty($ScoreRules[1]) ? 0 : $ScoreRules[1];?>" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">100-150个：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="<?php echo empty($ScoreRules[2]) ? 0 : $ScoreRules[2];?>" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">150-200个：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="<?php echo empty($ScoreRules[3]) ? 0 : $ScoreRules[3];?>" /> <font class="fc_red">*</font></td>
					</tr>
					<tr>
						<td nowrap="nowrap" class="tips">200个以上：</td>
						<td nowrap="nowrap"><input type="text" name="Integral[]" class="form_input" size="5" maxlength="5" value="<?php echo empty($ScoreRules[4]) ? 0 : $ScoreRules[4];?>" /> <font class="fc_red">*</font></td>
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
					<div class="img" id="AttentionImgPathDetail">
                    	
                    </div>
				</span>
			</span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>提示关注链接地址</label>
			<span class="input"><input type="text" class="form_input" name="AttentionLink" value="<?php echo $games["Games_AttentionLink"];?>" maxlength="255" size="45" /> <span class="tips">显示在游戏结束页面最下方</span></span>
			<div class="clear"></div>
		</div>

        <div class="rows">
            <label>游戏时长</label>
            <span class="input"><input type="text" class="form_input" name="Property[time]" value="<?php echo !empty($JSON["time"]) ? $JSON["time"] : 20;?>" size="5" notnull /><span class="tips">秒</span> <font class="fc_red">*</font> <span class="tips">游戏的总时间，建议设置为20</span></span>
            <div class="clear"></div>
        </div>
        <div class="rows">
            <label>游戏动作名称</label>
            <span class="input"><input type="text" class="form_input" name="Property[dongzuo]" value="<?php echo !empty($JSON["dongzuo"]) ? $JSON["dongzuo"] : '吃';?>" size="5" notnull /> <font class="fc_red">*</font> <span class="tips">如：拆</span></span>
            <div class="clear"></div>
        </div>
        <div class="rows">
            <label>游戏计量单位</label>
            <span class="input"><input type="text" class="form_input" name="Property[unit]" value="<?php echo !empty($JSON["unit"]) ? $JSON["unit"] : '个';?>" size="5" notnull /> <font class="fc_red">*</font> <span class="tips">如：个</span></span>
            <div class="clear"></div>
        </div>
        <div class="rows">
            <label>游戏内容名称</label>
            <span class="input"><input type="text" class="form_input" name="Property[name]" value="<?php echo !empty($JSON["name"]) ? $JSON["name"] : '小苹果';?>" size="5" notnull /> <font class="fc_red">*</font> <span class="tips">如：红包，与游戏动作名称和游戏计量单位相组合，那么游戏则变成了XX秒内拆XX个红包</span></span>
            <div class="clear"></div>
        </div>
        <div class="rows">
            <label>重上传游戏内容图片</label>
            <span class="input">
                <span class="upload_file">
                    <div>
                        <div class="up_input"><input name="_GameConfigImgUpload" id="_GameConfigImgUpload" type="file" /></div>
                        <div class="tips">图片尺寸建议：100*100px，如：上传一个红包的图片</div>
                        <div class="clear"></div>
                    </div>
                    <div class="img" id="_GameConfigImgDetail"></div>
                </span>
            </span>
            <div class="clear"></div>
        </div>
        <div class="rows">
            <label>游戏背景图</label>
            <span class="input">
                <span class="upload_file">
                    <div>
                        <div class="up_input"><input name="_GameConfigBgImgUpload" id="_GameConfigBgImgUpload" type="button" style="width:80px" value="上传图片" /></div>
                        <div class="tips">图片尺寸建议：640*1010px</div>
                        <div class="clear"></div>
                    </div>
                    <div class="img" id="_GameConfigBgImgDetail"></div>
                </span>
            </span>
            <div class="clear"></div>
        </div>
        <input type="hidden" name="Property[img]" value="<?php echo !empty($JSON["img"]) ? $JSON["img"] : '/static/api/games/images/apple.png';?>" />
        <input type="hidden" name="Property[bgimg]" value="<?php echo !empty($JSON["bgimg"]) ? $JSON["bgimg"] : '';?>" />
        <div class="rows">
			<label>排序优先级</label>
			<span class="input"><input type="text" class="form_input" name="Sorts" value="<?php echo $games["Games_Sorts"];?>" size="5" notnull /></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label>游戏规则</label>
			<span class="input"><textarea class="ckeditor" name="Rules" style="width:600px; height:300px;"><?php echo $games["Games_Rules"] ? $games["Games_Rules"] : '点击最下面的小苹果开始，20秒内看你能吃掉多少个小苹果，点错游戏即结束！';?></textarea></span>
			<div class="clear"></div>
		</div>
		<div class="rows">
			<label></label>
			<span class="input"><input type="submit" class="btn_ok" value="提交保存" name="submit_btn"><a href="lists.php" class="btn_cancel">返回</a></span>
			<div class="clear"></div>
		</div>
        <input type="hidden" name="ReplyImgPath" value="<?php echo !empty($Material_Json["ImgPath"]) ? $Material_Json["ImgPath"] : '/static/api/games/images/cover_'.$ModelID.'.jpg';?>" />
        <input type="hidden" name="AttentionImgPath" value="<?php echo $games["Games_AttentionImg"] ? $games["Games_AttentionImg"] : '/static/api/games/images/subscribe.jpg';?>" />
        <input type="hidden" name="ModelID" value="<?php echo $ModelID;?>">
        <input type="hidden" name="GamesID" value="<?php echo $games["Games_ID"];?>">
      </form>
    </div>
  </div>
</div>
</body>
</html>