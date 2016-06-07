<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$DB->showErr=false;
if($_POST){
	$_POST['content'] = str_replace('"','&quot;',$_POST['content']);
	$_POST['content'] = str_replace("'","&quot;",$_POST['content']);
	$_POST['content'] = str_replace('>','&gt;',$_POST['content']);
	$_POST['content'] = str_replace('<','&lt;',$_POST['content']);
	$Data=array(
		"Category_ID"=>$_POST["categoryid"],
		"Announce_Title"=>$_POST["title"],
		"Announce_Content"=>$_POST["content"],
		"Announce_Status"=>$_POST["status"],
		"Announce_CreateTime"=>time()
	);
	$flag=$DB->Add("announce",$Data);
	if($flag){
		echo '<script language="javascript">alert("添加成功！");window.open("index.php","_self");</script>';
		exit();
	}else{
		echo '<script language="javascript">alert("添加失败！");window.location="javascript:history.back()";</script>';
		exit();
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/third_party/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
    KindEditor.ready(function(K) {
        K.create('textarea[name="content"]', {
            themeType : 'simple',
			filterMode : false,
            uploadJson : '/third_party/kindeditor/php/upload_json.php',
            fileManagerJson : '/third_party/kindeditor/php/file_manager_json.php',
            allowFileManager : true
        });
    });
</script>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
	  <ul>
        <li class="cur"><a href="index.php">公告管理</a></li>
        <li><a href="category.php">分类管理</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
        <form class="r_con_form" method="post" action="?">
        	<div class="rows">
                <label>所属分类</label>
                <span class="input">
                 <select name="categoryid">
                 <option value="0">所属分类</option>
                 <?php
                 $DB->get("announce_category","*"," order by Category_ID asc");
				 while($r=$DB->fetch_assoc()){
			     ?>
				 <option value="<?php echo $r["Category_ID"];?>"><?php echo $r["Category_Name"]?></option>
			     <?php }?>
                 </select>
                </span>
                <div class="clear"></div>
            </div>
            
        	<div class="rows">
                <label>标题</label>
                <span class="input"><input type="text" name="title" value="" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>详细内容</label>
                <span class="input">
                    <textarea name="content" style="width:100%;height:400px;visibility:hidden;"></textarea>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>状态</label>
                <span class="input">
                    <label><input name="status" type="radio" value="1" checked>通过审核</label>
                    <label><input name="status" type="radio" value="0">待审核</label>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
        </form>
    </div>
  </div>
</div>
</body>
</html>