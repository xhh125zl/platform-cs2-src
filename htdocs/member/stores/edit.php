<?php
$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$StoresID=empty($_REQUEST['StoresID'])?0:$_REQUEST['StoresID'];
$rsStores=$DB->GetRs("stores","*","where Users_ID='".$_SESSION["Users_ID"]."' and Stores_ID=".$StoresID);
if($_POST)
{
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$Data=array(
		"Stores_Name"=>$_POST["StoresName"],
		"Stores_ImgPath"=>$_POST["ImgPath"],
		"Stores_Telephone"=>$_POST["Telephone"],
		"Stores_Address"=>$_POST["Address"],
		"Stores_PrimaryLng"=>$_POST["PrimaryLng"],
		"Stores_PrimaryLat"=>$_POST["PrimaryLat"]
	);
	$Set=$DB->Set("stores",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Stores_ID=".$StoresID);
	$Flag=$Flag&&$Set;
		
	if($Flag){
		mysql_query("commit");
		echo '<script language="javascript">alert("修改成功");window.location="index.php";</script>';
	}else{
		mysql_query("roolback");
		echo '<script language="javascript">alert("修改失败");history.go(-1);</script>';
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
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
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/stores.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/stores.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="index.php">门店管理</a></li>
      </ul>
    </div>
    <div id="stores" class="r_con_wrap"> 
      <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $ak_baidu;?>"></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">$(document).ready(stores_obj.stores_init);</script>
      <form id="stores_form" class="r_con_form" method="post" action="edit.php">
        <div class="rows">
          <label>门店名称</label>
          <span class="input">
          <input name="StoresName" value="<?php echo $rsStores["Stores_Name"] ?>" type="text" class="form_input" size="30" maxlength="100" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>门店图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input id="ImgUpload" name="ImgUpload" type="file">
            </div>
            <div class="tips">图片建议尺寸：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>联系电话</label>
          <span class="input">
          <div class="tips">如果有多个电话，每行填写一个</div>
          <textarea name="Telephone"><?php echo $rsStores["Stores_Telephone"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>详细地址</label>
          <span class="input">
          <input name="Address" id="Address" value="<?php echo $rsStores["Stores_Address"] ?>" type="text" class="form_input" size="45" maxlength="100" notnull>
          <span class="primary" id="Primary">定位</span> <font class="fc_red">*</font><br />
          <div class="tips">如果输入地址后点击定位按钮无法定位，请在地图上直接点击选择地点</div>
          <div id="map"></div>
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
        <input type="hidden" name="PrimaryLng" value="<?php echo $rsStores["Stores_PrimaryLng"] ?>">
        <input type="hidden" name="PrimaryLat" value="<?php echo $rsStores["Stores_PrimaryLat"] ?>">
        <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $rsStores["Stores_ImgPath"] ?>" />
        <input type="hidden" name="StoresID" value="<?php echo $rsStores["Stores_ID"] ?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>