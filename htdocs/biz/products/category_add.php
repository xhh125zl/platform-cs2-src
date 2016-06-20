<?php
require_once('../global.php');

if($_POST){
	$Data=array(
		"Category_Index"=>$_POST['Index'],
		"Category_ParentID"=>$_POST["ParentID"],
		"Users_ID"=>$rsBiz["Users_ID"],
		"Biz_ID"=>$_SESSION["BIZ_ID"]		
	);
	$names = explode("\n",$_POST["Name"]);
	foreach($names as $name){
		$name = trim($name);
		if(!$name) continue;
		$Data["Category_Name"] = $name;
		$DB->Add("biz_category",$Data);
	}	
	echo '<script language="javascript">window.location="category.php";</script>';
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
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/biz/js/shop.js'></script>
	<script type='text/javascript'>$(document).ready(shop_obj.category_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="category.php">自定义分类</a></li>
        <li class="cur"><a href="category_add.php">添加分类</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <div class="category">
        <div class="m_righter" style="margin-left:0px;">
          <form action="category_add.php" name="category_form" id="category_form" method="post">
            <h1>添加产品分类</h1>
             <div class="opt_item">
              <label>菜单排序：</label>
              <span class="input">
              <input type="text" name="Index" value="1" class="form_input" size="5" maxlength="30" notnull />
              <font class="fc_red">*</font>请输入数字</span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>上级分类：</label>
              <span class="input">
              <select name='ParentID'>
                <option value='0'>一级分类</option>
				<?php $DB->get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=0 order by Category_Index asc");
                while($rsPCategory=$DB->fetch_assoc()){
                    echo '<option value="'.$rsPCategory["Category_ID"].'">&nbsp;├'.$rsPCategory["Category_Name"].'</option>';
                }?>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>类别名称：</label>
              <span class="input">
              <textarea name="Name" style="width:160px; height:120px; padding:0px 5px; line-height:22px;" notnull></textarea>
              <font class="fc_red">*</font><span style="padding-left:5px; font-size:12px; color:#999">一行一个</span></span>
              <div class="clear"></div>
            </div>
            
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="添加分类" /></span>
              <div class="clear"></div>
            </div>
          </form>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>