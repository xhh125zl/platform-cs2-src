<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if($_POST)
{
	$Data=array(
		"AD_Name"=>$_POST['Name'],
		"AD_Status"=>$_POST['Status'],
		"AD_Width"=>$_POST['width'],
		"AD_Height"=>$_POST['height'],
		"AD_Text"=>$_POST['text'],
		"AD_CreateTime"=>time(),
		"Users_ID"=>$_SESSION["Users_ID"]
	);
	$Flag=$DB->Add("ad_advertising",$Data);
	if($Flag)
	{
		echo '<script language="javascript">alert("添加成功");window.location="config.php";</script>';
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
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/guanggao.js'></script>
<script>$(document).ready(shop_obj.products_init);</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/guanggao.css' rel='stylesheet' type='text/css' />
    
    
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="config.php">广告位管理</a></li>
        <li class=""><a href="ad_list.php">广告列表</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form id="products_form" class="r_con_form" method="post" action="config_add.php">
        <div class="rows">
          <label>广告位名称</label>
          <span class="input">
          <input type="text" name="Name" value="" class="form_input" size="35" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>状态</label>
          <span class="input">
          <select name='Status'>
           <option value='1'>开启</option>
           <option value='0'>关闭</option>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>广告规格</label>
          <span class="input price"> 宽:
          <input type="text" name="width" value="" class="form_input" size="5" maxlength="10" />
          高:
          <input type="text" name="height" value="" class="form_input" size="5" maxlength="10" />
          <span class="tips">&nbsp;注:格式为50px或50%.</span>
          </span>
          <div class="clear"></div>
        </div>
          <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="text" class="briefdesc"></textarea>
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