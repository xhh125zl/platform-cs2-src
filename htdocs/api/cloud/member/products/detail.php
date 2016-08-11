<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
if (isset($_GET["UsersID"])) {
    $UsersID = $_GET["UsersID"];
} else {
    echo '缺少必要的参数';
    exit();
}
$base_url = base_url();
$cloud_url = base_url() . 'api/' . $UsersID . '/cloud/';
if (isset($_GET["DetailID"])) {
    $DetailID = $_GET["DetailID"];
} else {
    echo '缺少必要的参数';
    exit();
}

$is_login = 1;
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php');
$rsConfig = $DB->GetRs("user_config", "*", "where Users_ID='" . $UsersID . "'");
$rsUser = $DB->GetRs("user", "*", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID . "User_ID"]);

$rsDetail = $DB->GetRs("cloud_products_detail", "*", "where Users_ID='" . $UsersID . "' and Cloud_Detail_ID=" . $DetailID);
$rsProducts = $DB->GetRs("cloud_products", "*", "where Users_ID='" . $UsersID . "' and Products_ID=" . $rsDetail['Products_ID']);
$ImgPath = get_prodocut_cover_img($rsProducts);
$item = $DB->GetRs("shipping_orders", "*", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID . "User_ID"] . " and Detail_ID=" . $DetailID . " and Orders_Status<>4");
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport"
	content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>领取商品</title>
<link href="/static/api/cloud/css/comm.css" rel="stylesheet"
	type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>'
	rel='stylesheet' type='text/css' />
<link href="/static/api/cloud/css/products.css?t=<?php echo time();?>"
	rel="stylesheet" type="text/css" />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<style>
.column {
	width: 100%;
	margin: 0 auto;
	height: 37px;
	background: #f7f7f7;
	border-bottom: 1px solid #dedede;
	position: relative;
	z-index: 21;
	line-height: 37px;
}

.column h1 {
	text-align: center;
	height: 37px;
	line-height: 37px;
	color: #999;
}

.pull-left {
	float: left;
}

.column img {
	margin: 4px;
}

.detail_info img {
	width: 100%;
}

.detail_info .img {
	clear: both;
	margin: 10px auto;
	width: 98%;
}

.btns {
	margin-bottom: 20px;
}

.btns .right {
	width: 30%;
	float: right;
}

.btns .left {
	width: 60%;
	float: left;
}

.btns .left p {
	padding: 5px 10px;
}

.btns .left p:nth-child(1) {
	font-size: 20px;
}

.btns .right span {
	width: 65px;
	height: 30px;
	line-height: 30px;
	background: #eb2c00;
	padding-left: 20px;
	color: #fff;
	position: absolute;
	z-index: 999;
	right: 5px;
}

.btns .right span a {
	color: #fff;
}

.btns .price {
	font-size: 20px;
	font-weight: 500;
	color: #EA6101;
}

.btns .price del {
	color: #C5C3C3;
	clear: both;
	font-size: 12px;
}

.detail_decription {
	width: 100%;
	line-height: 40px;
	height: 40px;
	font-size: 18px;
	overflow: hidden;
	border-top: solid 1px #dcdcdc;
	border-bottom: solid 1px #eeeeee;
	text-align: center;
}

.contents {
	line-height: 180%;
	overflow: hidden;
	min-height: 250px;
	width: 100%;
	text-align: center;
	padding-top: 20px;
}
</style>
</head>

<body>
	<header class="column">
		<a href="javascript:history.go(-1)" class="pull-left"><img
			src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
		<h1 class="title" id="page_title">领取商品</h1>
	</header>
	<div class="clear"></div>
	<div id="gift_detail">
		<div class="detail_info">
			<div class="img">
				<img src="<?php echo $ImgPath;?>" />
			</div>
			<div class="btns">
				<div class="left">
					<p><?php echo $rsProducts["Products_Name"];?></p>
					<p class="price">&yen;
			<?=$rsProducts["Products_PriceX"]?>
		元&nbsp;&nbsp;&nbsp;&nbsp; <del>&yen;
			<?=$rsProducts["Products_PriceY"]?>
			元</del>
					</p>
				</div>
				<div class="right">
	<?php if($item){?>
	<span>您已领取</span>
	<?php }else{?>
	<span> <a
						href="/api/<?php echo $UsersID ?>/cloud/member/products/order/<?php echo $rsDetail["Cloud_Detail_ID"];?>/">去领取</a>
					</span>
	<?php }?>
	</div>
			</div>
		</div>
		<div class="center-block">
			<div class="detail_decription">产品详情</div>
			<div class="contents"><?php echo htmlspecialchars_decode($rsProducts["Products_Description"],ENT_QUOTES)?></div>
		</div>
	</div>
<?php require_once('../member_footer.php'); ?>
</body>
</html>