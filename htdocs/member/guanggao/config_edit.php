<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$ID=empty($_REQUEST['id'])?0:$_REQUEST['id'];
$rsAD=$DB->GetRs("ad_advertising","*","where Users_ID='".$_SESSION["Users_ID"]."' and AD_IDS=".$ID);
if($_POST)
{
	$Data=array(
		"AD_Name"=>$_POST['Name'],
		"AD_Status"=>$_POST['Status'],
		"AD_Text"=>$_POST['text'],
		"AD_CreateTime"=>time(),
		"Users_ID"=>$_SESSION["Users_ID"]
	);
	$Flag=$DB->Set("ad_advertising",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and AD_IDS=".$ID);
	if($Flag)
	{
		echo '<script language="javascript">alert("修改成功");window.location="config.php";</script>';
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

<link href='/static/css/global.css' rel='stylesheet' type='text/css' />

<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script>$(document).ready(shop_obj.products_init);</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
           <li class="cur"><a href="config.php">广告位管理</a></li>
           <li class=""><a href="ad_list.php">广告列表</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" method="post" action="config_edit.php">
        <div class="rows">
          <label>产品名称</label>
          <span class="input">
          <input name="id" type="hidden" value="<?php echo $rsAD["AD_IDS"]; ?>">
          <input type="text" name="Name" value="<?php echo $rsAD["AD_Name"]; ?>" class="form_input" size="35" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>状态</label>
          <span class="input">
          <select name='Status'>
           <option value='1' <?php echo $rsAD["AD_Status"]==1?" selected":""; ?>>开启</option>
           <option value='0' <?php echo $rsAD["AD_Status"]==0?" selected":"";?>>关闭</option>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        
          <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="text" class="briefdesc"><?php echo $rsAD["AD_Text"]; ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>