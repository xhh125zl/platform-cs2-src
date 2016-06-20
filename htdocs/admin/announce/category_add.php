<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$DB->showErr=false;
if($_POST){
	$Data=array(
		"Category_Name"=>$_POST["name"]
	);
	$flag=$DB->Add("announce_category",$Data);
	if($flag){
		echo '<script language="javascript">alert("添加成功！");window.open("category.php","_self");</script>';
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

<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
	  <ul>
        <li><a href="index.php">公告管理</a></li>
        <li class="cur"><a href="category.php">公告类别</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
        <form class="r_con_form" method="post" action="?">
        	<div class="rows">
                <label>分类名称</label>
                <span class="input"><input type="text" name="name" value="" size="30" class="form_input" /></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
            <input type="hidden" name="Img" id="Img" value="" />
        </form>
    </div>
  </div>
</div>
</body>
</html>