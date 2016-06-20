<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

if($_POST)
{

	
	$Data=array(
		"Type_Index"=>$_POST['Index'],
		"Type_Name"=>$_POST["Name"],
		"Users_ID"=>$_SESSION["Users_ID"],
		"Attr_Group" => sub_str($_POST['Attr_Group'], 255),
	);
	
	$Flag=$DB->Add("shop_product_type",$Data);
	
	if($Flag)
	{
		echo '<script language="javascript">alert("添加成功");window.location="product_type.php";</script>';
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/weicbd.js'></script>
    <script language="javascript">$(document).ready(weicbd_obj.mulu_edit_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="product_type.php">产品类型</a></li>
        <li class="cur"><a href="mulu_add.php">添加类型</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <div class="category">
        <div class="m_righter" style="margin-left:0px;">
          <form action="?" method="post" id="mulu_edit">
            <h1>添加产品类型</h1>
             <div class="opt_item">
              <label>类型排序：</label>
              <span class="input">
              <input type="text" name="Index" value="1" class="form_input" size="5" maxlength="30" notnull />
              <font class="fc_red">*</font>请输入数字</span>
              <div class="clear"></div>
            </div>
            
            <div class="opt_item">
              <label>类型名称：</label>
              <span class="input">
              <input type="text" name="Name" value="" class="form_input" size="15" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            
          	<div class="opt_item">
          <label>属性组</label>
          <span class="input">
          	<textarea name="Attr_Group" class="Attr_Group"></textarea>
          </span>
          <br/>
         
          <div class="clear"></div>
           每行一个商品属性组。排序也将按照自然顺序排序。
        </div>
          
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="添加" />
              <a href="javascript:void(0);" class="btn_gray" onClick="location.href='product_type.php'">返回</a></span>
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