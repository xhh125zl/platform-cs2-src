<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID = $_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/cloud/member/products/no/";

$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsConfig = $DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$rsUser = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云购商品领取</title>
<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
<style>
.pull-left {
	float: left;
}

.column img {
	margin: 4px;
}
body { background:#FFFFFF; }
#shop_page_contents { clear:both; }
</style>
</head>
<body>
<div class="column">
	<h2 style="text-align: center;height: 37px;line-height: 37px;color: #999;">
	<a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
	云购商品领取
	<h2>
</div>
<div class="clear"></div>
<script language="javascript">$(document).ready(user_obj.gift_init);</script>
<div id="gift">
  <div class="t_list"> <a href="/api/<?php echo $UsersID ?>/cloud/member/products/">已领取</a> <a href="/api/<?php echo $UsersID ?>/cloud/member/products/no/" class="c">未领取</a> </div>
  <?php
	$DB->query('SELECT p.Products_Name,p.Products_JSON,p.Products_IsShippingFree,p.Products_Description,r.Cloud_Detail_ID FROM cloud_products_detail r LEFT JOIN cloud_products p ON p.Products_ID = r.Products_ID WHERE r.Users_ID="'.$UsersID.'" and r.User_ID = '.$_SESSION[$UsersID."User_ID"]);
	$list = array();
    while($rs = $DB->fetch_assoc()){
		$list[] = $rs;
	}
	foreach($list as $val){
		$item = $DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Detail_ID=".$val['Cloud_Detail_ID']." and Orders_Status<>4");
		if($item){
		    continue;
		}
		$ImgPath = get_prodocut_cover_img($val);
		echo '<div class="item">
					<a href="/api/'.$UsersID.'/cloud/member/products/detail/'.$val["Cloud_Detail_ID"].'/">
						<h1>【'.$val['Products_Name'].'】</h1>
						<div class="p"><div class="img" style="width:70%;overflow:hidden;background:#FFFFFF"><img src="'.$ImgPath.'" /></div>
							<div class="get" style="width:30%;">详情</div>
						</div>
					</a>
				</div>';
	}
  ?>
</div>
<?php require_once('../member_footer.php'); ?>
</body>
</html>