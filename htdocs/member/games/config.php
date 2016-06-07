<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig=$DB->GetRs("games_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Games_Name"=>"游戏中心",
		"Games_Logo"=>""
	);
	$DB->Add("games_config",$Data);
	$rsConfig=$Data;
}
$json=$DB->GetRs("wechat_material","*","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='games' and Material_TableID=0 and Material_Display=0");
if($json){
	$MJSON = json_decode($json['Material_Json'],true);
	$RTitle = $RImgPath = '';
	if($MJSON){
		$RTitle = $MJSON['Title'];
		$RImgPath = $MJSON['ImgPath'];
	}
}
if(empty($json)){
	$Material=array(
		"Title"=>"游戏中心",
		"ImgPath"=>"/static/api/images/cover_img/games.jpg",
		"TextContents"=>"",
		"Url"=>"/api/".$_SESSION["Users_ID"]."/games/"
	);
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Material_Table"=>"games",
		"Material_TableID"=>0,
		"Material_Display"=>0,
		"Material_Type"=>0,
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE),
		"Material_CreateTime"=>time()
	);
	$DB->Add("wechat_material",$Data);
	$MaterialID=$DB->insert_id();
	$rsMaterial=$Material;
	$RTitle = "游戏中心";
	$RImgPath = "/static/api/images/cover_img/games.jpg";
}else{
	$rsMaterial=json_decode($json['Material_Json'],true);
}
$rsKeyword=$DB->GetRs("wechat_keyword_reply","*","where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='games' and Reply_TableID=0 and Reply_Display=0");
if(empty($rsKeyword)){
	$MaterialID=empty($json['Material_Json'])?$MaterialID:$json['Material_Json'];
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Reply_Table"=>"games",
		"Reply_TableID"=>0,
		"Reply_Display"=>0,
		"Reply_Keywords"=>"游戏中心",
		"Reply_PatternMethod"=>0,
		"Reply_MsgType"=>1,
		"Reply_MaterialID"=>$MaterialID,
		"Reply_CreateTime"=>time()
	);
	$DB->Add("wechat_keyword_reply",$Data);
	$rsKeyword=$Data;
}
if($_POST)
{
	//开始事务定义
	$flag=true;
	$msg="";
	mysql_query("begin");
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Games_Name"=>$_POST["GamesName"],
		"Games_Logo"=>$_POST["GamesLogo"]
	);
	$Set=$DB->Set("games_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	$flag=$flag&&$Set;
	$Data=array(
		"Reply_Keywords"=>$_POST["ReplyKeyword"],
		"Reply_PatternMethod"=>isset($_POST["PatternMethod"])?$_POST["PatternMethod"]:0
	);
	$Set=$DB->Set("wechat_keyword_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='games' and Reply_TableID=0 and Reply_Display=0");
	$flag=$flag&&$Set;
	$Material=array(
		"Title"=>$_POST["ReplyTitle"],
		"ImgPath"=>$_POST["ReplyImgPath"],
		"TextContents"=>"",
		"Url"=>"/api/".$_SESSION["Users_ID"]."/games/"
	);
	$Data=array(
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE)
	);
	$Set=$DB->Set("wechat_material",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='games' and Material_TableID=0 and Material_Display=0");
	$flag=$flag&&$Set;
	if($flag){
		mysql_query("commit");
		echo '<script language="javascript">alert("设置成功");window.location="config.php";</script>';
	}else{
		mysql_query("roolback");
		echo '<script language="javascript">alert("设置失败");history.back();</script>';
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
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	
	K('#ReplyImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ReplyImgPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ReplyImgPath').val(url);
					K('#ReplyImgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#LogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#GamesLogo').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#GamesLogo').val(url);
					K('#LogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
<style type="text/css">
.r_con_config td .tips {
    color: #888;
    font-size: 12px;
}
.r_con_config .u_file {
    background: none repeat scroll 0 0 #f5f5f5;
    border: 1px solid #ddd;
    float: left;
    height: 130px;
    padding: 12px;
    position: relative;
    width: 90%;
}
.r_con_config .u_file .input {
    background: none repeat scroll 0 0 #fff;
}
.r_con_config .u_file .tips {
    color: #999;
    margin-top: 5px;
}
.r_con_config .u_file .img {
    background: none repeat scroll 0 0 #fff;
    border: 1px solid #ddd;
    height: 80px;
    left: 140px;
    overflow: hidden;
    position: absolute;
    top: 50px;
    width: 180px;
}
.r_con_config .u_file .img img {
    width: 100%;
}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
        <ul>
			<li class="cur"><a href="config.php">基本设置</a></li>
			<li class=""><a href="lists.php">游戏管理</a></li>
        </ul>
	</div>
	<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
	<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
	<script language="javascript">
	$(document).ready(function(){
		global_obj.config_form_init();
	});
	</script>
    <div id="config" class="r_con_config r_con_wrap">
      <form action="config.php" method="post" id="config_form" class="r_con_form">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50%" valign="top">
                        <h1><span class="fc_red">*</span> <strong>名称</strong></h1>
                        <input type="text" class="input" name="GamesName" value="<?php echo $rsConfig ? $rsConfig['Games_Name'] : ''?>" maxlength="30" notnull />
                    </td>
                    <td width="50%" valign="top">
                    	<h1><strong>Logo</strong></h1>
                        <div class="u_file">
                            <span class="tips">尺寸建议：120*50px</span><br /><br />
                            <div class="file"><input name="Upload" id="LogoUpload" type="button" style="width:80px" value="上传" /></div>
                            <div class="img" id="LogoDetail"><?php echo $rsConfig["Games_Logo"] ? '<img src="'.$rsConfig["Games_Logo"].'" />' : '';?></div>
                        </div>              
                    </td>
                </tr>
            </table>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <h1><strong>触发信息设置</strong></h1>
                        <div class="reply_msg">
                            <div class="m_left">
                                <span class="fc_red">*</span> 触发关键词<br />
                                <input type="text" class="input" name="ReplyKeyword" value="<?php echo $rsKeyword ? $rsKeyword['Reply_Keywords'] : '';?>" maxlength="100" notnull /><br /><br /><br />
                                <span class="fc_red">*</span> 匹配模式<br />
                                <div class="input"><input type="radio" name="PatternMethod" value="0" <?php echo ($rsKeyword && $rsKeyword['Reply_PatternMethod']==0)|| !$rsKeyword ? 'checked' : ''?> />精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span></div>
                                <div class="input"><input type="radio" name="PatternMethod" value="1" <?php echo $rsKeyword && $rsKeyword['Reply_PatternMethod']==1 ? 'checked' : ''?>/>模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span></div><br /><br />
                                <span class="fc_red">*</span> 图文消息标题<br />
                                <input type="text" class="input" name="ReplyTitle" value="<?php echo $RTitle;?>" maxlength="100" notnull />
                            </div>
                            <div class="m_right">
                                <span class="fc_red">*</span> 图文消息封面<span class="tips">（图片尺寸建议：640*360px）</span><br />
                                <div class="file"><input name="ReplyImgUpload" id="ReplyImgUpload" type="button" style="width:80px" value="上传" /></div><br />
                                <div class="img" id="ReplyImgDetail">
                                 <?php echo $RImgPath ? '<img src="'.$RImgPath.'" />' : '';?>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <input type="hidden" name="ReplyImgPath" id="ReplyImgPath" value="<?php echo $RImgPath ? $RImgPath : '';?>" />
                        <input type="hidden" name="GamesLogo" id="GamesLogo" value="<?php echo $rsConfig["Games_Logo"];?>" />
                    </td>
                </tr>
            </table>
        <div class="submit"><input type="submit" class="btn_green" value="提交保存" name="submit_button"></div>
        </form>
    </div>
  </div>
</div>
</body>
</html>