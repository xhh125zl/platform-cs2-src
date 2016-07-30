<?php 
ini_set("display_errors","On");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

if(isset($_GET["UsersID"])){	
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

if(isset($_GET["needcart"])){	
	$needcart=$_GET["needcart"];
}else{
	$needcart = 1;
}

$share_flag=0;
$signature="";

//用户已登录
if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}
//用户没有登录
if(empty($_SESSION[$UsersID."User_ID"])){
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/cloud/cart/checkout/".$needcart."/";
	header("location:/api/".$UsersID."/user/login/");
}

$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/cloud/cart/checkout/".$needcart."/";

$User_ID  = $_SESSION[$UsersID."User_ID"];

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
$rsPay = $DB->GetRs("users_payconfig","Shipping,Delivery_AddressEnabled,Delivery_Address","where Users_ID='".$UsersID."'");


//获取产品列表
$cart_key = get_cart_key_cloud($UsersID,0,$needcart);
$CartList = json_decode($_SESSION[$cart_key], true);

$total_price =0;

if(count($CartList)>0) {
	foreach($CartList as $key=>$value){
		foreach($value as $j=>$v){
			$total_price += $v["Qty"]*$v["ProductsPriceX"];
		}
	}
}else{
	header("location:/api/".$UsersID."/cloud/cart/");
	exit;
}
$owner = get_owner($rsConfig,$UsersID);
if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
};
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单确认页面</title>
<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css?t=<?=time()?>' rel='stylesheet' type='text/css' />
<link href="/static/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/static/css/font-awesome.css" />
<link rel="stylesheet" href="/static/api/shop/skin/default/css/tao_checkout.css" />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/bootstrap.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?=time()?>'></script>
<script type='text/javascript' src='/static/api/cloud/js/flow.js?t=<?=time()?>'></script>
<script type='text/javascript'>
	var base_url = '<?=$base_url?>';
	var Users_ID = '<?=$UsersID?>';
	$(document).ready(function(){
		flow_obj.checkout_init();
	});
</script>
</head>

<body >
<header class="bar bar-nav"> <a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a> <a href="/api/<?=$UsersID?>/cloud/cart/" class="pull-right"><img src="/static/api/shop/skin/default/images/cart_two_points.png" /></a>
	<h1 class="title" id="page_title">提交订单 </h1>
</header>
<div id="wrap"> 
	<form id="checkout_form" action="<?=$base_url?>api/<?=$UsersID?>/cloud/cart/">
		<input type="hidden" name="action" value="checkout" />
		<!-- 产品列表begin-->
		<div id="product-list" class="container">
			<?php foreach($CartList as $Products_ID=>$Product_List){?>
			<?php
			    $condition = "where Users_ID = '".$UsersID."' and Products_ID =".$Products_ID;
				$rsProduct = $DB->getRs('cloud_products','Products_Shipping,Products_Business,Products_IsShippingFree',$condition);
			
			?>
			<input type="hidden" id="IsShippingFree_<?=$Products_ID?>" value="<?=$rsProduct['Products_IsShippingFree']?>" />

			<?php foreach($Product_List as $key=>$product){ ?>
			<div class="product">
				<div class="simple-info row">
					<dl>
						<dd class="col-xs-2"><img src="<?=$product['ImgPath']?>" class="thumb"/></dd>
						<dd class="col-xs-6">
							<h4>
								<?=$product['ProductsName']?>
							</h4>
						</dd>
						<dd class="col-xs-2 price"> <span class="orange">&yen;
							<?=$product["ProductsPriceX"]?>
							</span></dd>
						<div class="clearfix"></div>
					</dl>
				</div>
				<div class="row">
					<div class="qty_container"> <span class="pull-left">&nbsp;&nbsp;购买数量</span>
						<div class="qty_selector pull-right"> 
						    <a class="btn  btn-default" name="minus">-</a>
							<input id="qty_<?=$Products_ID?>_<?=$key?>" class="qty_value" type="text" value="<?=$product['Qty']?>" size="2" />
							<a class="btn btn-default" name="add">+</a> </div>
						<div class="clearfix"></div>
					</div>
					<?php 
						$Sub_Weight = $product['ProductsWeight']*$product['Qty']; 
                    	$Sub_Total = $product["ProductsPriceX"]*$product['Qty']; 
                     	$Sub_Qty = $product['Qty']; 
					?>
				</div>
				<!-- 产品小计开始 -->
				<div class="sub_total row">
					<p> 共&nbsp;<span class="red" id="subtotal_qty_<?=$Products_ID?>_<?=$key?>">
						<?=$product['Qty']?>
						</span>&nbsp;件商品&nbsp;&nbsp;<span class="orange" id="subtotal_price_<?=$Products_ID?>_<?=$key?>">&yen;
						<?=$Sub_Total?>
						元</span> </p>
				</div>
				<!-- 产品小计结束 --> 
			</div>
			<?php } ?>
			<?php }?>
		</div>
		
		<!-- 产品列表end--> 

		<!--- 订单汇总信息begin -->
		<div class="container">
			<div class="row" >
				<ul class="list-group">					
					<li class="list-group-item">
						<p class="pull-right" id="order_total_info">合计<span id="total_price_txt" class="red">&yen;
							<?=$total_price?>
							</span></p>
						<div class="clearfix"></div>
					</li>
				</ul>
				<input type="hidden" id="virtual" name="virtual" value="0"/>
				<input type="hidden" id="needcart" name="needcart" value="<?=$needcart?>"/>
				<div class="clearfix"></div>
			</div>
			<br/>
			<div class="row" id="btn-panel">
				<button type="button" class="shop-btn btn-orange pull-right"   id="submit-btn">提交</button>
				<div class="clearfix"></div>
			</div>
		</div>
		<!-- 订单汇总信息end --> 
	</form>
</div>
</body>
</html>