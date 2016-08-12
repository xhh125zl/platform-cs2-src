<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
ini_set("display_errors","On");
if(isset($_GET["UsersID"])){	
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';
$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$User_ID = $_SESSION[$UsersID."User_ID"];

if(isset($_GET["DetailID"])){	
	$DetailID = $_GET["DetailID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$rsDetail = $DB->GetRs("cloud_products_detail","*","where Users_ID='".$UsersID."' and Cloud_Detail_ID=".$DetailID);
$rsProducts = $DB->GetRs("cloud_products","*","where Users_ID='".$UsersID."' and Products_ID=".$rsDetail['Products_ID']);
$ImgPath = get_prodocut_cover_img($rsProducts); 
if(!$rsDetail){
	echo "该奖品不存在";
	exit;
}
$BizID = $rsProducts['Biz_ID'];
$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","Shipping,Delivery_AddressEnabled,Delivery_Address","where Users_ID='".$UsersID."'");
$Address_ID = !empty($_GET['AddressID'])?$_GET['AddressID']:0;
if($Address_ID == 0){
	$condition = "Where Users_ID = '".$UsersID."' And User_ID = ".$User_ID." And Address_Is_Default = 1";
}else{
	$condition = "Where Users_ID = '".$UsersID."' And User_ID = ".$User_ID." And Address_ID =".$Address_ID;
}
$rsAddress = $DB->GetRs('user_address','*',$condition);
$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
$area_array = json_decode($area_json,TRUE);
$province_list = $area_array[0];
	
if($rsAddress){
	$Province = $province_list[$rsAddress['Address_Province']];
	$City = $area_array['0,'.$rsAddress['Address_Province']][$rsAddress['Address_City']];
	$Area = $area_array['0,'.$rsAddress['Address_Province'].','.$rsAddress['Address_City']][$rsAddress['Address_Area']];
}else{
	$_SESSION[$UsersID."HTTP_REFERER"] = '/api/'.$UsersID.'/cloud/member/products/order/'.$DetailID.'/';
	$_SESSION[$UsersID."From_Checkout"] = 1;
	header("location:/api/".$UsersID."/user/my/address/edit/");
}
$rsBiz = $DB->GetRs("biz", "Shipping,Biz_ID" ,"WHERE Users_ID = '{$UsersID}' AND Biz_ID={$BizID}");
if(empty($rsBiz)){
	echo '<p style="text-align:center;color:red;font-size:30px;"><br/><br/>基本运费信息没有设置，请联系管理员</p>';
	exit();
}

//获取前台可用的快递公司
$shipping_company_dropdown = get_front_shiping_company_dropdown($UsersID,$rsBiz);
$Business = 'express';
//获取产品列表
	
$Shipping_ID = !empty($rsConfig['Default_Shipping']) ? $rsConfig['Default_Shipping'] : 0;
	
$Shipping_Name  = '';
if($Shipping_ID != 0){
	$Shipping_Name = $shipping_company_dropdown[$Shipping_ID];
}
	
if($rsAddress){
	$City_Code = $rsAddress['Address_City'];
}else{
	$City_Code = 0;
}
	
//get_order_total_info
if($rsProducts["Products_IsShippingFree"] == 0){
	$rsProducts['weight'] = $rsProducts['Products_Weight'];
	$rsProducts['qty'] = 1;
	$rsProducts['money'] = 0;
	$total_shipping_fee = get_shipping_fee($UsersID,$Shipping_ID,$Business,$City_Code,$rsConfig,$rsProducts);
}else{
	$total_shipping_fee = 0;
}
	
$Default_Business = $rsConfig['Default_Business'];	
$Default_Shipping = $rsConfig['Default_Shipping'];
$Business_List = array('express'=>'快递','common'=>'平邮');
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
<link href="/static/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/static/css/font-awesome.css" />
<link rel="stylesheet" href="/static/api/shop/skin/default/css/tao_checkout.css" />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/bootstrap.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?=time()?>'></script>
<script type='text/javascript' src='/static/api/cloud/js/shipping.js?t=<?=time()?>'></script>
<script type='text/javascript'>
	var base_url = '<?=$base_url?>';
	var Users_ID = '<?=$UsersID?>';
	var FreeShipping = <?php echo $rsProducts["Products_IsShippingFree"];?>;
	$(document).ready(function(){
		shipping_obj.shipping_checkout_init();
	});
</script>
<style>
    .gift_order_info img{ width:96%;}
    .integral {
        font-size: 20px;
	font-weight: 500;
	color: #EA6101;
    }
	.integral del {
    	color: #C5C3C3;
    	clear: both;
    	font-size: 12px;
    }
    .gift_order_info ul li { padding-left:5px; }
    .gift_order_info ul li:nth-child(1){ font-size: 20px; }
</style>
</head>

<body style="background:#FFF">
<header class="bar bar-nav"> <a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
	<h1 class="title" id="page_title">商品领取 </h1>
</header>
<div id="wrap"> 
	<!-- 地址信息简述begin -->
	<div class="container">
		<div id="receiver-info" class="row">
			<dl>
				<dd class="col-xs-1"><a href="javascript:history.go(-1)"><img src="/static/api/shop/skin/default/images/map_maker.png" /></a></dd>
				<dd class="col-xs-9">
					<p>
						<?php if($rsAddress){?>
						收货人:
						<?=$rsAddress['Address_Name']?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?=$rsAddress['Address_Mobile']?>
						<br/>
						所在地区:
						<?=$Province.'&nbsp;&nbsp;'.$City.'&nbsp;&nbsp;'.$Area?>
						<br/>
						详细地址:
						<?=$rsAddress['Address_Detailed']?>
						<input type="hidden" id="City_Code" value="<?=$rsAddress['Address_City']?>"/>
						<?php }else{ ?>
						请去添加收货地址<br/>
						<?php } ?>
					</p>
				</dd>
				<dd class="col-xs-1"><a href="/api/<?=$UsersID?>/user/my/address/<?=$rsAddress['Address_ID'] ?>/"><img src="/static/api/shop/skin/default/images/arrow_right.png"/></a></dd>
			</dl>
		</div>
	</div>
	<div class="b15"></div>
	<!-- 地址信息简述end-->
	<form id="checkout_form" action="<?=$base_url?>api/<?=$UsersID?>/cloud/member/products/ajax/">
		<input type="hidden" name="DetailID" value="<?php echo $DetailID;?>" />
		<input type="hidden" name="isshipping" value="1" />
		<input type="hidden" name="action" value="shipping_change" />
		<!-- 产品列表begin-->
		<div class="gift_order_info">
			<h1>商品信息</h1>
			<img src="<?php echo $ImgPath;?>" />
			<ul>
				<li><?php echo $rsProducts['Products_Name'];?></li>
				<li class="integral">
					<?=$rsProducts["Products_PriceX"]?>
            		元&nbsp;&nbsp;&nbsp;&nbsp; <del>&yen;
            			<?=$rsProducts["Products_PriceY"]?>
            			元</del>
				</li>
			</ul>
			<div class="clear"></div>
		</div>

		<input type="hidden" name="AddressID" value="<?=$rsAddress['Address_ID'] ?>" />
		<!-- 配送方式begin-->
		<div class="container">
			<div class="row" id="shipping_method"> &nbsp;&nbsp;配送方式 <a href="javascript:void(0)" class="pull-right"><img width="25px" height="25px" src="/static/api/shop/skin/default/images/arrow_right.png"/></a> <a  class="pull-right">&nbsp;&nbsp; <span id="shipping_name">
				<?=$Shipping_Name?>
				</span>&nbsp;&nbsp; <span id="total_shipping_fee_txt">
				<?php if($total_shipping_fee  == 0 ){?>
				免运费
				<?php }else{?>
				<?=$total_shipping_fee?>
				元
				<?php } ?>
				</span>&nbsp;&nbsp;</a> </div>
		</div>
		<!-- 配送方式end --> 
		
		<!--- 订单汇总信息begin -->
		<div class="container">
			<?php if($rsProducts["Products_IsShippingFree"] == 0){?>
			<div class="row" >
				<ul class="list-group">
					<li class="list-group-item">
						<p class="pull-right" id="order_total_info">合计：<span id="total_price_txt" class="red">&yen;
							<?=$total_shipping_fee?>
							</span></p>
						<div class="clearfix"></div>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<?php }?>
			<br/>
			<input type="hidden" name="Order_Shipping[Express]"  id="Order_Shipping_Express" value="<?=$Shipping_Name?>"/>
			<input type="hidden" id="total_price" name="total_price" value="<?=$total_shipping_fee?>"/>
			<div class="row" id="btn-panel">
				<button type="button" class="shop-btn btn-orange pull-right"   id="submit-btn">提交</button>
				<div class="clearfix"></div>
			</div>
		</div>
		<!-- 订单汇总信息end --> 
		
		<!-- 快递公司选择begin -->
		<div class="container">
			<div class="row">
				<div class="modal"  role="dialog" id="shipping-modal">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								<h5 class="modal-title" id="mySmallModalLabel">配送方式</h5>
							</div>
							<div class="modal-body">
								<dl id="shipping-company-list">
									<?php foreach($shipping_company_dropdown as  $key=>$item){?>
									<dd>
										<div class="pull-left shipping-company-name">
											<?=$item?>
										</div>
										<div class="pull-right">
											<input  type="radio" shipping_name="<?=$item?>" <?=($Default_Shipping == $key)?'checked':'';?>  name="Shiping_ID" value="<?=$key?>" />
										</div>
										<div class="clearfix"></div>
									</dd>
									<?php }?>
								</dl>
								<div class="clearfix"></div>
							</div>
							<div class="modal-footer"> <a class="pull-left modal-btn" id="confirm_shipping_btn" style="border:none;">确定</a> <a class="pull-right modal-btn" id="cancel_shipping_btn">取消</a> </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 快递公司选择end -->
		<input type="hidden"  id="trigger_cart_id" value=""/>
	</form>
</div>
<?php require_once('../member_footer.php'); ?>
</body>
</html>