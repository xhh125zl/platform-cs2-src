<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';
if(isset($_GET["DetailID"])){
	$DetailID = $_GET["DetailID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$rsConfig = $DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$rsUser = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);

$rsDetail = $DB->GetRs("cloud_products_detail","*","where Users_ID='".$UsersID."' and Cloud_Detail_ID=".$DetailID);
$rsProducts = $DB->GetRs("cloud_products","*","where Users_ID='".$UsersID."' and Products_ID=".$rsDetail['Products_ID']);
$ImgPath = get_prodocut_cover_img($rsProducts);  
$item = $DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Detail_ID=".$DetailID." and Orders_Status<>4");
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>领取商品</title>
<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
</head>

<body>
<div id="gift_detail">
 <h1>商品详情</h1>
 <div class="detail_info">
  <div class="img"><img src="<?php echo $ImgPath;?>" /><span><?php echo $rsProducts["Products_Name"];?></span></div>
  <div class="btns">
	<?php if($item){?>
	<span>您已领取</span>
	<?php }else{?>
		<a href="/api/<?php echo $UsersID ?>/cloud/member/products/order/<?php echo $rsDetail["Cloud_Detail_ID"];?>/">去领取</a>
	<?php }?>
  </div>
 </div>
 <div class="detail_decription">
  <?php echo htmlspecialchars_decode($rsProducts["Products_Description"],ENT_QUOTES)?>
 </div>
</div>
<?php require_once('../member_footer.php'); ?>
</body>
</html>