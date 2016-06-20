<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');

$base_url = base_url();
$shop_url = shop_url();

//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = $_SERVER["DOCUMENT_ROOT"].'/api/shop/html';
$smarty->template_dir = $template_dir;

if(isset($_GET["UsersID"])){
	$UsersID = $_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$_SESSION[$UsersID."HTTP_REFERER"] = "/api/".$UsersID."/shop/cart/checkout_direct/";
$rsConfig =  $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
//用户授权登录
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$User_ID = $_SESSION[$UsersID."User_ID"];

$rsUser = $DB->GetRs("user","User_Profile","where User_ID=".$User_ID);
if(empty($rsUser["User_Profile"])){
	header("location:/api/".$UsersID."/user/complete/");
	exit;
}

//获得用户地址
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
	$_SESSION[$UsersID."From_Checkout"] = 1;
	header("location:/api/".$UsersID."/user/my/address/edit/");
}

//获取产品列表
$CartList = json_decode($_SESSION[$UsersID."DirectBuy"], true);

$total_price =0;
$toal_shipping_fee = 0;
if(!empty($CartList)){
	if($rsAddress&&$rsConfig['NeedShipping'] == 1){
		$City_Code = $rsAddress['Address_City'];
	}else{
		$City_Code = 0;
	}
	$info = get_order_total_info($UsersID, $CartList, $rsConfig, array(), $City_Code);
	$total_price = $info['total'];
	$total_shipping_fee = $info['total_shipping_fee'];
}else{
	header("location:/api/".$UsersID."/shop/cart/");
	exit;
}

/*商品信息汇总*/
$total_price = $total_price + $info['total_shipping_fee'];

$Default_Business = 'express';	
$Business_List = array('express'=>'快递','common'=>'平邮');	

//获取前台可用的快递公司
foreach($info  as $Biz_ID=>$BizCartList){
	if(is_integer($Biz_ID)){
		$biz_company_dropdown[$Biz_ID] = get_front_shiping_company_dropdown($UsersID,$BizCartList['Biz_Config']);
	}
}
$Business = 'express';
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
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css?t=<?=time()?>' rel='stylesheet' type='text/css' />
<link href="/static/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/static/css/font-awesome.css" />
<link rel="stylesheet" href="/static/api/shop/skin/default/css/tao_checkout.css" />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/bootstrap.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?=time()?>'></script>
<script type='text/javascript' src='/static/api/shop/js/flow.js?t=<?=time()?>'></script>
<script type='text/javascript'>
	var base_url = '<?=$base_url?>';
	var Users_ID = '<?=$UsersID?>';
	$(document).ready(function(){
		flow_obj.checkout_init();
	});
</script>
</head>

<body >
<header class="bar bar-nav"> <a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a> <a href="/api/<?=$UsersID?>/shop/cart/" class="pull-right"><img src="/static/api/shop/skin/default/images/cart_two_points.png" /></a>
	<h1 class="title" id="page_title">提交订单 </h1>
</header>
<div id="wrap"> 
	<!-- 地址信息简述begin -->
	<div class="container">
		<?php if($rsConfig['NeedShipping'] == 1){?>
		<div id="receiver-info" class="row">
			<dl>
				<dd class="col-xs-1"><a href="javascript:void(0);"><img src="/static/api/shop/skin/default/images/map_maker.png" /></a></dd>
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
		<?php }else{?>
		<p class="row" style="text-align:center;"><br/>
			此商城无需物流</p>
		<?php }?>
	</div>
	<div class="b15"></div>
	
	<!-- 地址信息简述end-->
	
	<form id="checkout_form" action="<?=$base_url?>api/<?=$UsersID?>/shop/cart/">
		<input type="hidden" name="AddressID" value="<?=$rsAddress['Address_ID'] ?>" />
		<input type="hidden" name="action" value="checkout" />
		<input type="hidden" name="Need_Shipping" value="<?=$rsConfig['NeedShipping']?>" />
		
		<!-- 产品列表begin-->
		<div class="container" id="Biz_List">
			<?php foreach($CartList as $Biz_ID=>$BizCartList):?>
			<div class="row panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?=$info[$Biz_ID]['Biz_Config']['Biz_Name']?>
					</h3>
				</div>
				<?php $Shipping_ID = $info[$Biz_ID]['Shipping_ID'];?>
				<div class="panel-body">
					<div class="product-list" >
						<?php
							//获取优惠券
							$coupon_info = get_useful_coupons($User_ID,$UsersID,$Biz_ID,$total_price);
						?>
						<?php foreach($BizCartList as $Products_ID=>$Product_List):?>
						<?php
							$condition = "where Users_ID = '".$UsersID."' and Products_ID =".$Products_ID;
							$rsProduct = $DB->getRs('shop_products','Products_Count,Products_IsShippingFree',$condition);
						?>
						<!-- 购物车中产品attr begin -->
						<input type="hidden" id="Products_Count_<?=$Products_ID?>" value="<?=$rsProduct['Products_Count']?>" />
						<input type="hidden" id="IsShippingFree_<?=$Products_ID?>" value="<?=$rsProduct['Products_IsShippingFree']?>" />
						
						<!-- 购物车中产品attr end -->
						<?php foreach($Product_List as $key=>$product): ?>
						<div class="product">
							<div class="simple-info row">
								<dl>
									<dd class="col-xs-2"><img src="<?=$product['ImgPath']?>" class="thumb"/></dd>
									<dd class="col-xs-6">
										<h5>
											<?=sub_str($product['ProductsName'],18,true)?>
										</h5>
										<dl class="option">
											<?php 
											 if(!empty($product["Property"])){
												foreach($product["Property"] as $Attr_ID=>$Attr){
													echo '<dd>'.$Attr['Name'].': '.$Attr['Value'].'</dd>';
												}
											 }
											?>
										</dl>
									</dd>
									<dd class="col-xs-2 price"> <span class="orange">
									&yen;<?=$product["ProductsPriceX"]?>
										</span></dd>
									<div class="clearfix"></div>
								</dl>
							</div>
							<div class="row">
								<div class="qty_container"> <span class="pull-left">&nbsp;&nbsp;购买数量</span>
									<div class="qty_selector pull-right"> <a class="btn  btn-default" name="minus">-</a>
										<input id="<?=$Biz_ID?>_<?=$Products_ID?>_<?=$key?>" class="qty_value" type="text" value="<?=$product['Qty']?>" size="2"/>
										<a class="btn btn-default" name="add">+</a> </div>
									<div class="clearfix"></div>
								</div>
								<?php 
									$Sub_Weight = $product['ProductsWeight'] * $product['Qty']; 
									$Sub_Total = $product["ProductsPriceX"] * $product['Qty']; 
									$Sub_Qty = $product['Qty']; 
								?>
							</div>
							<!-- 产品小计开始 -->
							<div class="sub_total row">
								<p> 共&nbsp;<span class="red" id="subtotal_qty_<?=$Biz_ID?>_<?=$Products_ID?>_<?=$key?>">
									<?=$product['Qty']?>
									</span>&nbsp;件商品&nbsp;&nbsp;<span class="orange" id="subtotal_price_<?=$Biz_ID?>_<?=$Products_ID?>_<?=$key?>">&yen;
									<?=$Sub_Total?>
									元</span> </p>
							</div>
							<!-- 产品小计结束 --> 
						</div>
						<?php endforeach; ?>
						<?php endforeach;?>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row fapiao"> &nbsp;&nbsp;<span>是否要发票</span> <a class="pull-right">
						<input type="checkbox" class="Invoice_btn" rel="<?=$Biz_ID?>" name="Order_NeedInvoice[<?=$Biz_ID?>]" value="1" />
						&nbsp;&nbsp;&nbsp;&nbsp; </a>
						<div class="clearfix"></div>
					</div>
					<div class="container" id="Invoice_info_<?=$Biz_ID?>" style="display:none">
						<div class="row order_extra_info">
							<input type="text" name="Order_InvoiceInfo[<?=$Biz_ID?>]" placeholder="发票抬头，个人姓名或公司名称..." style="width:100%; height:30px; border:1px #dfdfdf solid; line-height:30px; margin-top:8px;" />
						</div>
					</div>
				</div>
				<!-- 优惠券begin -->
				<div class="panel-footer">
					<?php if($coupon_info['num'] >0):?>
					<div class="container">
						<div class="row" id="coupon-list-<?=$Biz_ID?>">
							<?=build_coupon_html($smarty, $coupon_info)?>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<!-- 优惠券end --> 
				<!-- 配送方式begin -->
				<div class="panel-footer">
					<?php if(!empty($info[$Biz_ID]['error'])):?>
					<div class="row"> <a href="javascript:void(0)">
						<?=$info[$Biz_ID]['msg']?>
						</a> </div>
					<?php exit;?>
					<?php else: ?>
					<div class="row shipping_method" Biz_ID=<?=$Biz_ID?>> &nbsp;&nbsp;配送方式 <a href="javascript:void(0)" class="pull-right"><img src="/static/api/shop/skin/default/images/arrow_right.png" height="25px" width="25px"></a> <a class="pull-right">&nbsp;&nbsp; <span id="biz_shipping_<?=$Biz_ID?>">
						<?=$info[$Biz_ID]['Shipping_Name']?>
						</span>&nbsp;&nbsp; <span id="biz_shipping_fee_txt_<?=$Biz_ID?>">
						<?=$info[$Biz_ID]['total_shipping_fee']?>
						元 </span>&nbsp;&nbsp;</a>
						<input type="hidden" name="Biz_Shipping_Fee[<?=$Biz_ID?>]" id="Shipping_ID_<?=$Biz_ID?>" value="<?=$info[$Biz_ID]['total_shipping_fee']?>"/>
					</div>
					<?php endif;?>
				</div>
				<!-- 配送方式end --> 
				<!-- 订单备注begin -->
				<div class="panel-footer">
					<div class="container">
						<div class="row order_extra_info">
							<input type="text" name="Remark[<?=$Biz_ID?>]" placeholder="订单备注信息" style="width:100%; height:30px; border:1px #dfdfdf solid; line-height:30px; margin-bottom:8px;" />
							
						</div>
					</div>
				</div>
				<!-- 订单备注end --> 
			</div>
			<?php endforeach;?>
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
				<input type="hidden" name="cart_key" id="cart_key" value="DirectBuy"/>
				<input type="hidden" id="coupon_value" value="0"/> 
				<input type="hidden" id="total_shipping_fee" name="Order_Shipping" value="<?=$total_shipping_fee?>"/>
				<div class="clearfix"></div>
			</div>
			<br/>
			<div class="row" id="btn-panel">
				<button type="button" class="shop-btn btn-orange pull-right"   id="submit-btn">提交</button>
				<div class="clearfix"></div>
			</div>
		</div>
		<!-- 订单汇总信息end --> 
		
		<!-- 快递公司选择begin -->
		<?php foreach($biz_company_dropdown as $Biz_ID=>$shipping_company_dropdown){?>
		<div class="container">
			<div class="row">
				<div class="modal"  role="dialog" id="shipping-modal-<?php echo $Biz_ID?>">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								<h5 class="modal-title" id="mySmallModalLabel">配送方式</h5>
							</div>
							<div class="modal-body">
								<dl id="shipping-company-list">
									<?php if($shipping_company_dropdown){?>
									<?php foreach($shipping_company_dropdown as  $key=>$item){?>
									<dd>
										<div class="pull-left shipping-company-name">
											<?=$item?>
										</div>
										<div class="pull-right">
											<input  type="radio" <?=($info[$Biz_ID]['Biz_Config']['Default_Shipping'] == $key)?'checked':'';?> class="Shiping_ID_Val" name="Shiping_ID_<?=$Biz_ID?>"  Biz_ID="<?=$Biz_ID?>" value="<?=$key?>" />
										</div>
										<div class="clearfix"></div>
									</dd>
									<?php }?>
									<?php } ?>
								</dl>
								<div class="clearfix"></div>
							</div>
							<div class="modal-footer"> <a class="pull-left modal-btn" id="confirm_shipping_btn" biz_id= "<?=$Biz_ID?>">确定</a> <a class="pull-right modal-btn" id="cancel_shipping_btn" biz_id= "<?=$Biz_ID?>">取消</a> </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<!-- 快递公司选择end -->
		<input type="hidden"  id="trigger_cart_id" value=""/>
	</form>
</div>
<?php require_once('../footer.php'); ?>
</body>
</html>