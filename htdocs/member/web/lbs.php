<?php 
//$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig=$DB->GetRs("web_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if($_POST)
{
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$Data=array(
		"Stores_Name"=>$_POST["StoresName"],
		"Stores_LBS"=>$_POST["StoresLBS"] ? intval($_POST["StoresLBS"]) : '0',
		"Stores_Address"=>$_POST["Address"],
		"Stores_ImgPath"=>$_POST["ImgPath"],
		"Stores_Description"=>$_POST["StoresDescription"],
		"Stores_PrimaryLng"=>$_POST["PrimaryLng"],
		"Stores_PrimaryLat"=>$_POST["PrimaryLat"]
	);
	$Set=$DB->Set("web_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	$Flag=$Flag&&$Set;
		
	if($Flag){
		mysql_query("commit");
		echo '<script language="javascript">alert("修改成功");window.location="lbs.php";</script>';
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/web.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/web.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
        <li class=""><a href="column.php">栏目管理</a></li>
        <li class=""><a href="article.php">内容管理</a></li>
        <li class="cur"><a href="lbs.php">一键导航</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $ak_baidu;?>"></script> 
    <script language="javascript">$(document).ready(web_obj.lbs_init);</script>
    <div id="lbs" class="r_con_wrap">
      <form id="lbs_form" class="r_con_form" method="post" action="lbs.php">
        <div class="rows">
          <label>商家名称</label>
          <span class="input">
          <input name="StoresName" value="<?php echo $rsConfig["Stores_Name"] ?>" type="text" class="form_input" size="30" maxlength="100" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>多家门店</label>
          <span class="input">
          <input type="checkbox" name="StoresLBS1" value="1"<?php echo empty($rsConfig["Stores_LBS"])?'':' checked' ?> />
          <input type="hidden" name="StoresLBS" value="<?php echo empty($rsConfig["Stores_LBS"])?'0':'1' ?>" />
          <span class="tips">如果您有多家门店，请使用<a href="">LBS门店定位</a>功能，让客户直接选择最近的门店</span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows not_lbs">
          <label>详细地址</label>
          <span class="input">
          <input name="Address" id="Address" value="<?php echo $rsConfig["Stores_Address"] ?>" type="text" class="form_input" size="45" maxlength="100" notnull>
          <span class="primary" id="Primary">定位</span> <font class="fc_red">*</font><br />
          <div class="tips">如果输入地址后点击定位按钮无法定位，请在地图上直接点击选择地点</div>
          <div id="map"></div>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows not_lbs">
          <label>门店图片</label>
          <span class="input">
          <div class="upload_file">
            <div>
              <div class="up_input">
                <input id="ImgUpload" name="ImgUpload" type="file">
              </div>
              <div class="tips">大图尺寸建议：640*360px</div>
              <div class="clear"></div>
            </div>
            <div class="img" id="ImgDetail"><img src="<?php echo $rsConfig["Stores_ImgPath"] ?>" /></div>
          </div>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows not_lbs">
          <label>门店介绍</label>
          <span class="input">
          <textarea name="StoresDescription"><?php echo $rsConfig["Stores_Description"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $rsConfig["Stores_ImgPath"] ?>" />
        <input type="hidden" name="PrimaryLng" value="<?php echo empty($rsConfig["Stores_PrimaryLng"])?113.676832:$rsConfig["Stores_PrimaryLng"] ?>">
        <input type="hidden" name="PrimaryLat" value="<?php echo empty($rsConfig["Stores_PrimaryLat"])?34.780696:$rsConfig["Stores_PrimaryLat"] ?>">
        <input type="hidden" name="do_action" value="web.lbs">
      </form>
    </div>
  </div>
</div>
</body>
</html>