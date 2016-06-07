<?php 
//$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if($_POST)
{
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$Data=array(
		"Address"=>$_POST["Address"],
		"PrimaryLng"=>$_POST["PrimaryLng"],
		"PrimaryLat"=>$_POST["PrimaryLat"]
	);
	$Set=$DB->Set("user_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
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
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="config.php">基本设置</a>
          <dl>
            <dd class="first"><a href="lbs.php">一键导航设置</a></dd>
          </dl>
        </li>
        <li class=""> <a href="user_list.php">会员管理</a>
          <dl>
            <dd class="first"><a href="user_level.php">会员等级设置</a></dd>
            <dd class=""><a href="user_profile.php">会员注册资料</a></dd>
            <dd class=""><a href="card_benefits.php">会员权利说明</a></dd>
            <dd class=""><a href="user_list.php">会员管理</a></dd>
          </dl>
        </li>
        <li class=""> <a href="card_config.php">会员卡设置</a></li>
        
        <li class=""><a href="business_password.php">商家密码设置</a></li>
      </ul>
    </div>
    <div id="lbs" class="r_con_wrap"> 
      <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $ak_baidu;?>"></script>
      <script language="javascript">$(document).ready(user_obj.lbs_init);</script>
      <form id="lbs_form" class="r_con_form" method="post" action="lbs.php">
        <div class="rows">
          <label>详细地址</label>
          <span class="input">
          <input name="Address" id="Address" value="<?php echo $rsConfig["Address"] ?>" type="text" class="form_input" size="45" maxlength="100" notnull>
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
          </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="PrimaryLng" value="<?php echo empty($rsConfig["PrimaryLng"])?113.676832:$rsConfig["PrimaryLng"] ?>">
        <input type="hidden" name="PrimaryLat" value="<?php echo empty($rsConfig["PrimaryLat"])?34.780696:$rsConfig["PrimaryLat"] ?>">
      </form>
    </div>
  </div>
</div>
</body>
</html>