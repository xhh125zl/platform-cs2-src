<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$CategoryID=empty($_REQUEST['CategoryID'])?0:$_REQUEST['CategoryID'];
$rsCategory=$DB->GetRs("shop_articles_category","*","where Users_ID='".$_SESSION["Users_ID"]."' and Category_ID=".$CategoryID);
if($_POST)
{
	
	$Data=array(
		"Category_Index"=>isset($_POST['Index']) ? $_POST['Index'] : 0,
		"Category_Name"=>trim($_POST["Name"]),
		"Users_ID"=>$_SESSION["Users_ID"],
		"Category_Type"=>$_POST["Type"],
		"Category_Content"=>!empty($_POST['Content']) ? $_POST['Content'] : '',
		'mob_show'=>$_POST['mob_show']
	);
	$Flag=$DB->Set("shop_articles_category",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Category_ID=".$CategoryID);
	if($Flag)
	{
		echo '<script language="javascript">alert("修改成功");window.location="articles_category.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
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
<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/third_party/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
    KindEditor.ready(function(K) {
        K.create('textarea[name="Content"]', {
            themeType : 'simple',
			filterMode : false,
            uploadJson : '/third_party/kindeditor/php/upload_json.php',
            fileManagerJson : '/third_party/kindeditor/php/file_manager_json.php',
            allowFileManager : true
        });
    });
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    
    <div class="r_nav">
      <ul>
        <li><a href="articles.php">文章管理</a></li>
		<li class="cur"><a href="articles_category.php">分类管理</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <div class="category">
        <div class="m_righter" style="margin-left:0px;">
          <form action="articles_category_edit.php" name="category_form" id="category_form" method="post">
              <input name="CategoryID" type="hidden" value="<?php echo $rsCategory["Category_ID"] ?>">
            <h1>修改文章分类</h1>
             <div class="opt_item">
              <label>分类排序：</label>
              <span class="input">
              <input type="text" name="Index" value="<?php echo $rsCategory["Category_Index"] ?>" class="form_input" size="5" maxlength="30" notnull />
              <font class="fc_red">*</font>请输入数字</span>
              <div class="clear"></div>
            </div>
			<div class="opt_item">
                <label>显示在:</label>
                <span class="input">
				    <label><input name="mob_show" type="radio" value="0"<?php if($rsCategory['mob_show'] == 0){ echo 'checked';}?>>手机端</label>
                    <label><input name="mob_show" type="radio" value="1"<?php if($rsCategory['mob_show'] == 1){ echo 'checked';}?>>电脑端</label>
					<label><input name="mob_show" type="radio" value="2"<?php if($rsCategory['mob_show'] == 2){ echo 'checked';}?>>全部</label>
                </span>
                <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>类别名称：</label>
              <span class="input">
              <input type="text" name="Name" value="<?php echo $rsCategory["Category_Name"] ?>" class="form_input" size="15" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
			<div class="opt_item">
              <label>类型：</label>
              <span class="input">
              <select name="Type">
                   <option value="单页" <?php if($rsCategory["Category_Type"] == '单页'){?> selected<?php }?>>单页</option>
				   <option value="列表" <?php if($rsCategory["Category_Type"] == '列表'){?> selected<?php }?>>列表</option>
              </select>
              <font class="fc_red">*</font>
			  </span>
              <div class="clear"></div>
            </div>
			<div class="opt_item" id="content">
                <label>详细内容</label>
                <span class="input">
                    <textarea name="Content" style="width:100%;height:400px;visibility:hidden;"><?php echo $rsCategory["Category_Content"];?></textarea>
                </span>
                <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="修改分类" />
              <a href="javascript:void(0);" class="btn_gray" onClick="location.href='articles_category.php'">返回</a></span>
              <div class="clear"></div>
            </div>
          </form>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
<script>
    if($('select[name=Type]').val() == '单页'){
		$('#content').show();
	}else{
		$('#content').hide();
	}
    $('select[name=Type]').change(function(){
	    if($(this).val() == '单页'){
		    $('#content').show();
		}else{
		    $('#content').hide();
		}
	});
</script>
</body>
</html>