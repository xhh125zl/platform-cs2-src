<?php
require_once('../global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

$TypeID=empty($_REQUEST['TypeID'])?0:$_REQUEST['TypeID'];
$rsType=$DB->GetRs("shop_product_type","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Type_ID=".$TypeID);
if($_POST){
	$Data=array(
		"Type_Index"=>$_POST['Index'],
		"Type_Name"=>$_POST["Name"]
	);
	$Flag=$DB->Set("shop_product_type",$Data,"where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Type_ID=".$TypeID);
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="product_type.php";</script>';
	}else{
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
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/biz/js/shop.js'></script>
    <script language="javascript">$(document).ready(shop_obj.category_init);</script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="product_type.php">属性类型</a></li>
        <li><a href="product_type_add.php">编辑属性类型</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <div class="category">
        <div class="m_righter" style="margin-left:0px;">
          <form action="?" method="post" id="category_form">
              <input name="TypeID" type="hidden" value="<?php echo $TypeID; ?>">
            <h1>编辑属性类型</h1>
             <div class="opt_item">
              <label>类型排序：</label>
              <span class="input">
              <input type="text" name="Index" value="<?php echo $rsType["Type_Index"] ?>" class="form_input" size="5" maxlength="30" notnull />
              <font class="fc_red">*</font>请输入数字</span>
              <div class="clear"></div>
            </div>
            
            <div class="opt_item">
              <label>类型名称：</label>
              <span class="input">
              <input type="text" name="Name" value="<?php echo $rsType["Type_Name"] ?>" class="form_input" size="15" maxlength="30" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="修改" /></span>
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