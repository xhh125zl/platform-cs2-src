<?php


if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$DB->showErr=false;


if($_POST){
	$_POST["content"] = str_replace('"','&quot;',$_POST["content"]);
	$_POST["content"] = str_replace("'","&quot;",$_POST["content"]);
	$_POST["content"] = str_replace('>','&gt;',$_POST["content"]);
	$_POST["content"] = str_replace('<','&lt;',$_POST["content"]);
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Article_Title"=>$_POST["title"],
		"Category_ID"=>empty($_POST["CategoryID"]) ? 0 : $_POST["CategoryID"],
		"Article_Content"=>$_POST["content"],
		"Article_Status"=>$_POST["status"],
		"Article_Editor"=>$_POST["Editor"],
		"Article_CreateTime"=>time()
	);
	
	
	$flag=$DB->Add("shop_articles",$Data);
	if($flag){
		echo '<script language="javascript">alert("添加成功！");window.open("articles.php","_self");</script>';
		exit();
	}else{
		echo '<script language="javascript">alert("添加失败！");window.location="javascript:history.back()";</script>';
		exit();
	}
}
$DB->get("shop_articles_category","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Category_Index asc");
$arr = $DB->toArray();
$list = [];
foreach($arr as $k=>$v){
    if($v['Category_ParentID']==0){
        $list[$k]['base'] = $v;
    }
}

foreach ($list as $k => $v){
    foreach($arr as $key => $val){
        if($v['base']['Category_ID']==$val['Category_ParentID']){
            $list[$k]['child'][] = $val;
        }
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

<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
	  <ul>
        <li class="cur"><a href="articles.php">文章管理</a></li>
		<li><a href="articles_category.php">分类管理</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
        <form class="r_con_form" method="post" action="?">
        	<div class="rows">
                <label>标题</label>
                <span class="input"><input type="text" name="title" value="" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
			
			<div class="rows">
                <label>发布者</label>
                <span class="input"><input type="text" name="Editor" value="管理员" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>所属分类</label>
                <span class="input">
                 <select name="CategoryID" notnull>
                <?php
                	foreach ($list as $k => $v){
		?>
                     <option value="<?=$v['base']["Category_ID"];?>" disabled><?=$v['base']["Category_Name"];?></option>
                <?php 
                  if(!empty($v['child'])){
                    foreach ($v['child'] as $key => $val){
                ?>
                <option value="<?=$val["Category_ID"];?>">——<?=$val["Category_Name"];?></option>
                <?php
                    }
                  }
                 ?>
                <?php }?>
                 </select>
                </span>
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
                    <label><input name="status" type="radio" value="1" checked>显示</label>
                    <label><input name="status" type="radio" value="0">不显示</label>
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