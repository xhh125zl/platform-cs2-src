<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
if($_POST)
{	
	$_POST['Description'] = str_replace('"','&quot;',$_POST['Description']);
	$_POST['Description'] = str_replace("'","&quot;",$_POST['Description']);
	$_POST['Description'] = str_replace('>','&gt;',$_POST['Description']);
	$_POST['Description'] = str_replace('<','&lt;',$_POST['Description']);
	$Data=array(
		"Column_Index"=>$_POST['Index'] ? intval($_POST['Index']) : 0,
		"Column_ParentID"=>$_POST['ParentID'],
		"Column_Name"=>$_POST['Name'],
		"Column_PageType"=>$_POST['PageType'],
		"Column_ImgPath"=>$_POST["ImgPath"],
		"Column_Link"=>empty($_POST['Link'])?0:$_POST['Link'],
		"Column_LinkUrl"=>$_POST["LinkUrl"],
		"Column_PopSubMenu"=>empty($_POST['PopSubMenu'])?0:$_POST['PopSubMenu'],
		"Column_NavDisplay"=>empty($_POST['NavDisplay'])?0:$_POST['NavDisplay'],
		"Column_ListTypeID"=>empty($_POST['ListTypeID'])?0:$_POST['ListTypeID'],
		"Column_ChildTypeID"=>empty($_POST['ChildTypeID'])?0:$_POST['ChildTypeID'],
		"Column_Description"=>$_POST['Description'],
		"Users_ID"=>$_SESSION["Users_ID"]
		
	);
	$Flag=$DB->Add("web_column",$Data);
	if($Flag)
	{
		echo '<script language="javascript">alert("添加成功");window.location="column.php";</script>';
	}else
	{
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
			uploadJson : '/member/upload_json.php?TableField=web_column',
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
	});
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
		web_obj.column_edit_init();
	});
	</script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
        <li class="cur"><a href="column.php">栏目管理</a></li>
        <li class=""><a href="article.php">内容管理</a></li>
        <li class=""><a href="lbs.php">一键导航</a></li>
      </ul>
    </div>
    <div id="column" class="r_con_wrap">
      <form id="column_form" class="r_con_form" method="post" action="column_add.php">
        <div class="rows">
          <label>栏目排序</label>
          <span class="input">
          <input name="Index" value="" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">*</font>越大越靠后</span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>上级栏目</label>
          <span class="input">
		    <select name="ParentID" class="parent">
			  <option value="0">一级栏目</option>
			  <?php
				$DB->get("web_column","*","where Users_ID='".$_SESSION["Users_ID"]."' and Column_ParentID=0 order by Column_Index asc");
				while($r=$DB->fetch_assoc()){
					echo '<option value="'.$r["Column_ID"].'">'.$r["Column_Name"].'</option>';
				}
			  ?>
		    </select>
          </span> 
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>栏目名称</label>
          <span class="input">
          <input name="Name" value="" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>上传图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input id="ImgUpload" name="ImgUpload" type="button" style="width:80px" value="上传图片">
                <input type="hidden" id="ImgPath" name="ImgPath" value="" />
            </div>
            <div class="tips">大图尺寸建议：420*300px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>栏目链接</label>
          <span class="input opt">
          <input type="checkbox" value="1" name="Link"  />
          <span id="LinkUrl_span">
          <input name="LinkUrl" value="" type="text" class="form_input" size="40" id="web_common_url" ><img src="/static/member/images/ico/search.png" style="width:22px; height:22px; margin:0px 0px 0px 5px; vertical-align:middle; cursor:pointer" class="btn_select_url" />
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="Option_rows">
          <label>相关选项</label>
          <span class="input opt"> <span class="pop_sub_menu">弹出二级菜单:
          <input type="checkbox" value="1" name="PopSubMenu"  />
          </span> 导航显示:
          <input type="checkbox" value="1" name="NavDisplay" checked />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="Description_rows">
          <label>详细内容</label>
          <span class="input">
          <textarea name="Description"></textarea>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>页面显示</label>
          <span class="input">
           <input type="radio" name="PageType" value="0" id="PageType0" checked /><label for="PageType0">内容列表</label>&nbsp;&nbsp;<input type="radio" name="PageType" value="1" id="PageType1" /><label for="PageType1">子栏目列表</label>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="ListType_rows" style="display:block">
          <label>列表形式</label>
          <span class="input">
          <ul id="column-article-list-type">
            <li><input name="ListTypeID" type="radio" value="0" checked>
              <div class="item" ListTypeID="0">
                <div class="img"><img src="/static/member/images/web/column-article-list-0.jpg" /></div>
                <div class="filter"></div>
                <div class="bg"></div>
              </div>
            </li>
            <li><input name="ListTypeID" type="radio" value="1">
              <div class="item" ListTypeID="1">
                <div class="img"><img src="/static/member/images/web/column-article-list-1.jpg" /></div>
                <div class="filter"></div>
                <div class="bg"></div>
              </div>
            </li>
            <li><input name="ListTypeID" type="radio" value="2">
              <div class="item" ListTypeID="2">
                <div class="img"><img src="/static/member/images/web/column-article-list-2.jpg" /></div>
                <div class="filter"></div>
                <div class="bg"></div>
              </div>
            </li>
          </ul>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows" id="ListType_child" style="display:none">
          <label>列表形式</label>
          <span class="input">
          <ul id="column-article-list-type">
            <li>
              <input name="ChildTypeID" type="radio" value="0" checked>
              <div class="item" ListTypeID="0">
                <div class="img"><img src="/static/member/images/web/column-childlist-list-0.jpg" /></div>
                <div class="filter"></div>
                <div class="bg"></div>
              </div>
            </li>
            <li>
              <input name="ChildTypeID" type="radio" value="1">
              <div class="item" ListTypeID="1">
                <div class="img"><img src="/static/member/images/web/column-childlist-list-1.jpg" /></div>
                <div class="filter"></div>
                <div class="bg"></div>
              </div>
            </li>
          </ul>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="column.php" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>