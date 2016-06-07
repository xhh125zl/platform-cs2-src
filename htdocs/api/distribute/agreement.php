<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

$base_url = base_url();
$shop_url = shop_url();

if(isset($_GET["UsersID"])){
  $UsersID = $_GET["UsersID"];
}else{
  echo '缺少必要的参数';
  exit;
}

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

$is_login = 1;
$owner = get_owner($rsConfig,$UsersID);
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$owner = get_owner($rsConfig,$UsersID);

//分销级别处理文件
include($_SERVER["DOCUMENT_ROOT"].'/api/distribute/distribute.php');

if($distribute_flag){
	header("location:/api/" . $UsersID . "/distribute/");
	exit;
}
$arr_level_name = array('一','二','三','四','五','六','七','八','九','十');
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo htmlspecialchars_decode($rsConfig["Distribute_AgreementTitle"],ENT_QUOTES);?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
</head>
<body style="background:#FFF;">
<style type="text/css">
.article_header{width:98%; margin:0px auto; padding:8px; line-height:22px; font-size:16px; font-weight:bold}
.article_second{width:95%; margin:0px auto; padding:0px 0px 6px;}
.article_second span{font-size:14px;}
.article_second span.span_editor{padding-left:10px; color:#1580C2}
.article_content{padding:0px 10px}
.article_content img{max-width:100%}
.article_footer{width:95%; margin:0px auto; padding:10px 0px 10px;}
.article_footer span{font-size:14px;color:#1580C2}
.back{
	display: block;
    width: 95%;
    margin: 0px auto;
    background: #4F93CE;
    font-size: 16px;
    color: #FFF;
    height: 44px;
    text-align: center;
    line-height: 42px;
    border: none;
    border-radius: 8px;
}
</style>
<div class="article_header">
<?php echo htmlspecialchars_decode($rsConfig["Distribute_AgreementTitle"],ENT_QUOTES);?>
</div>
<div class="article_content">
<?php echo htmlspecialchars_decode($rsConfig["Distribute_Agreement"],ENT_QUOTES);?>
</div>
<?php
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1 and KF_Code<>''");
if($kfConfig){
	echo htmlspecialchars_decode($kfConfig["KF_Code"],ENT_QUOTES);
}
?><button onclick="javascript:history.back()" class="back" style="">返回</button>

<?php if($rsConfig["CallEnable"] && $rsConfig["CallPhoneNumber"]){?>
<script language='javascript'>var shop_tel='<?php echo $rsConfig["CallPhoneNumber"];?>';</script>
<script type='text/javascript' src='/static/api/shop/js/tel.js?t=<?php echo time();?>'></script>
<?php }?>
</body>
</html>

