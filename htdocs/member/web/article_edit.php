<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$ArticleID=empty($_REQUEST['ArticleID'])?0:$_REQUEST['ArticleID'];
$rsArticle=$DB->GetRs("web_article","*","where Users_ID='".$_SESSION["Users_ID"]."' and Article_ID=".$ArticleID);
$rsMaterial=$DB->GetRs("wechat_material","Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='web' and Material_TableID=".$ArticleID);
if($rsMaterial){
	$Material_Json=json_decode($rsMaterial['Material_Json'],true);
	$mstu = 1;
}else{
	$mstu = 0;
}

$rsKeyword = $DB->GetRs("wechat_keyword_reply","*","where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='web' and Reply_TableID=".$ArticleID);
if($rsKeyword){
	$keyword = $rsKeyword["Reply_Keywords"];
	$kstu = 1;
}else{
	$kstu = 0;
}

if($_POST){	
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$_POST['Description'] = str_replace('"','&quot;',$_POST['Description']);
	$_POST['Description'] = str_replace("'","&quot;",$_POST['Description']);
	$_POST['Description'] = str_replace('>','&gt;',$_POST['Description']);
	$_POST['Description'] = str_replace('<','&lt;',$_POST['Description']);
	$Data=array(
		"Article_Index"=>$_POST['Index'] ? intval($_POST['Index']) : 0,
		"Article_Title"=>$_POST['Title'],
		"Column_ID"=>$_POST['Column_ID'],
		"Article_ImgPath"=>$_POST['ImgPath'],
		"Article_Link"=>empty($_POST['Link'])?0:$_POST['Link'],
		"Article_LinkUrl"=>$_POST["LinkUrl"],
		"Article_BriefDescription"=>$_POST['BriefDescription'],
		"Article_Description"=>$_POST['Description']
	);
	$Set=$DB->Set("web_article",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Article_ID=".$ArticleID);
	$Flag=$Flag&&$Set;
	
	$Material=array(
		"Title"=>$_POST["Title"],
		"ImgPath"=>$_POST["FPath"],
		"TextContents"=>$_POST["BriefDescription"],
		"Url"=>"/api/".$_SESSION["Users_ID"]."/web/article/".$ArticleID."/"
	);
	$Data=array(
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE)
	);
	$Set=$DB->Set("wechat_material",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='web' and Material_TableID=".$ArticleID);
	$Flag=$Flag&&$Set;
	
	$Data=array(
		"Reply_Keywords"=>$_POST["Keywords"]
	);
	$Set=$DB->Set("wechat_keyword_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='web' and Reply_TableID=".$ArticleID);
	$Flag=$Flag&&$Set;
	
	if($Flag){
		mysql_query("commit");
		echo '<script language="javascript">alert("修改成功");window.location="article.php";</script>';
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
	KindEditor.ready(function(K){
		var editor = K.editor({
			uploadJson : '/member/upload_json.php?TableField=web_article',
            fileManagerJson : '/member/file_manager_json.php',
			showRemote : true,
            allowFileManager : true,
		});
		K('#ImgUpload').click(function() {
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#ImgPath').val(),
					clickFn : function(url, title, width, height, border, align) {
						K('#ImgPath').val(url);
						K('#ImgDetail').html('<img src="'+url+'" />');
						editor.hideDialog();
					}
				});
			});
		});
		K('#FUpload').click(function() {
			editor.loadPlugin('image', function() {
				editor.plugin.imageDialog({
					imageUrl : K('#FPath').val(),
					clickFn : function(url, title, width, height, border, align) {
						K('#FPath').val(url);
						K('#FDetail').html('<img src="'+url+'" />');
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
    <script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
    <link href='/static/member/css/web.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/web.js'></script>
    <script language="javascript">
	$(document).ready(function(){
		web_obj.article_edit_init();
	});
	</script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
        <li class=""><a href="column.php">栏目管理</a></li>
        <li class="cur"><a href="article.php">内容管理</a></li>
        <li class=""><a href="lbs.php">一键导航</a></li>
      </ul>
    </div>
    <div id="column" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">//$(document).ready(web_obj.column_article_init);</script>
      <form class="r_con_form" method="post" action="article_edit.php" id="article_form">
        <input name="ArticleID" type="hidden" value="<?php echo $rsArticle["Article_ID"] ?>">
		<div class="rows">
          <label>排序</label>
          <span class="input">
          <input name="Index" value="<?php echo $rsArticle["Article_Index"] ?>" type="text" class="form_input" size="10" >
          越大越靠后</span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>触发关键词</label>
          <span class="input">
           <input name="Keywords" value="<?php echo $kstu==1 ? $keyword : "";?>" type="text" class="form_input" size="40" maxlength="50"><span class="tips">&nbsp;匹配方式为“<font class="fc_red">精确匹配</font>”</span>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>图文消息封面</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="FUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="FDetail"><?php echo $mstu==1 && $Material_Json["ImgPath"] ? '<img src="'.$Material_Json["ImgPath"].'" />' : "";?></div>
          </span> </span>
          <div class="clear"></div>
		  <input type="hidden" id="FPath" name="FPath" value="<?php echo $mstu==1 ? $Material_Json["ImgPath"] : "";?>" />
        </div>
        <div class="rows">
          <label>内容标题</label>
          <span class="input">
          <input name="Title" value="<?php echo $rsArticle["Article_Title"] ?>" type="text" class="form_input" size="40" maxlength="50" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>隶属栏目</label>
          <span class="input">
          <select name="Column_ID" notnull>
            <?php
			$DB->Get("web_column","*","where Users_ID='".$_SESSION["Users_ID"]."' and Column_ParentID=0 order by Column_Index asc");
			$Columns = array();
			while($r=$DB->fetch_assoc()){
				$Columns[] = $r;
			}
			foreach($Columns as $Column){
				echo '<option value="'.$Column['Column_ID'].'"'.($rsArticle["Column_ID"]==$Column['Column_ID']?" selected":"").'>'.$Column['Column_Name'].'</option>';
				$DB->Get("web_column","*","where Users_ID='".$_SESSION["Users_ID"]."' and Column_ParentID=".$Column['Column_ID']." order by Column_Index asc");
				while($item=$DB->fetch_assoc()){
					echo '<option value="'.$item['Column_ID'].'"'.($rsArticle["Column_ID"]==$item['Column_ID']?" selected":"").'> └ '.$item['Column_Name'].'</option>';
				}
			}?>
          </select>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>上传图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input id="ImgUpload" name="ImgUpload" type="button" style="width:80px" value="上传图片">
              <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $rsArticle["Article_ImgPath"] ?>" />
            </div>
            <div class="tips">大图尺寸建议：420*300px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"><?php echo $rsArticle["Article_ImgPath"] ? '<img src="'.$rsArticle["Article_ImgPath"].'" />' : ""; ?></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>页面链接</label>
          <span class="input opt">
          <input type="checkbox" value="1" name="Link"<?php echo empty($rsArticle["Article_Link"])?"":" checked" ?> />
          <span id="LinkUrl_span">
          <input name="LinkUrl" value="<?php echo $rsArticle["Article_LinkUrl"];?>" type="text" class="form_input" size="40" id="web_common_url" ><img src="/static/member/images/ico/search.png" style="width:22px; height:22px; margin:0px 0px 0px 5px; vertical-align:middle; cursor:pointer" class="btn_select_url" />
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="BriefDescription_rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea class="txetarea" name="BriefDescription"><?php echo $rsArticle["Article_BriefDescription"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="Description_rows">
          <label>详细内容</label>
          <span class="input">
          <textarea name="Description" style="width:100%;height:400px;visibility:hidden;"><?php echo $rsArticle["Article_Description"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="article.php" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>