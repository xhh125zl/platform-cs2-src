<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

$share_flag=0;
$signature="";

$is_login=1;
$owner = get_owner($rsConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);


if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
	//$order_filter_base .= '&OwnerID='.$owner['id'];
	//$page_url .= '&OwnerID='.$owner['id'];
}

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}

$OrderID = $_GET['OrderID'];
$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
$rsUser = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);

$total = $rsOrder['Order_TotalPrice'];
//积分抵用
$diyong_flag = false;
$diyong_list = json_decode($rsConfig['Integral_Use_Laws'],true);

//用户设置了积分抵用规则，且抵用率大于零 
if(count($diyong_list) >0 &&$rsConfig['Integral_Buy']>0){
	$diyong_intergral = diyong_act($total,$diyong_list,$rsUser['User_Integral']);
	//如果符合抵用规则中的某一个规则,且此订单之前未执行过抵用操作
	if($diyong_intergral >0 &&$rsOrder['Integral_Consumption'] ==0&&$rsUser["User_Integral"]>0){
		$diyong_flag = true;
	}
	
}

if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
};

///计算参加抵用后的余额
function diyong_act($sum,$regulartion,$User_Integral){
	$diyong_integral = 0;
	foreach($regulartion as $key=>$item){	
		if($sum >= $item['man']){
			$diyong_integral = $item['use'];
			continue;
		}
		 		
	}
	//如果用户积分小于最少抵用所需积分
	if($diyong_integral > $User_Integral){
		$diyong_integral = $User_Integral;
	}
	return $diyong_integral;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["ShopName"] ?>付款</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/cloud/js/shop.js'></script>
<script language="javascript">
$(document).ready(shop_obj.page_init);
$(document).ready(shop_obj.select_payment_init);
</script>
</head>

<body>
<div id="shop_page_contents">
	<div id="cover_layer"></div>
	<link href='/static/api/shop/skin/default/css/cart.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
	<script language="javascript">$(document).ready(shop_obj.payment_init);</script>
	<div id="payment">
		<div class="i-ture">
			<h1 class="t">订单提交成功！</h1>
			<div class="info"> 订 单 号：<?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?><br />
				订单总价：<span class="fc_red" id="Order_TotalPrice">￥<?php echo $rsOrder["Order_TotalPrice"]?><br/>
				</span>
				<?php if($rsOrder['Integral_Money'] > 0):?>
				已经用
				<?=$rsOrder['Integral_Consumption']?>
				个积分抵用了<span class="fc_red">
				<?=$rsOrder['Integral_Money']?>
				</span>元
				<?php endif; ?>
			</div>
		</div>
		<form id="payment_form" action="/api/<?php echo $UsersID ?>/cloud/cart/">
			
			<!-- 积分抵用活动begin -->
			<?php if($diyong_flag):?>
			<input type="hidden" id="User_Integral" name="User_Integral" value="<?=$rsUser['User_Integral']?>"/>
			<input type="hidden" id="Integral_Consumption" name="Integral_Consumption" value="0"/>
			<div class="diyong i-ture">
				<h1 class="t">积分抵用</h1>
				<p> 每<span class="fc_red" id="diyong_rate">
					<?=$rsConfig['Integral_Buy']?>
					</span>积分可抵用一元,您现在有积分<span id="user-integral">
					<?=$rsUser['User_Integral']?>
					</span>个 </p>
				<P> 可使用积分<span id="can-diyong" class="fc_red">
					<?=$diyong_intergral?>
					</span>个,减&nbsp;<span class="fc_red">
					<?=$diyong_intergral/$rsConfig['Integral_Buy']?>
					</span>&nbsp;元
					<button style="float:right" class="btn btn-success btn-sm" id="btn-diyong" >抵用</a></button>
					&nbsp;&nbsp;&nbsp;&nbsp; </p>
			</div>
			<?php endif;?>
			
			<!-- 积分抵用活动end --> 
			
			<!-- 支付方式选择 begin  -->
			<div class="i-ture">
				<h1 class="t">选择支付方式</h1>
				<ul id="pay-btn-panel">
					<?php if(!empty($rsPay["PaymentWxpayEnabled"])){ ?>
					<li> <a href="javascript:void(0)" class="btn btn-default btn-pay direct_pay" id="wzf" data-value="微支付"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信支付</a> </li>
					<?php }?>
					<?php if(!empty($rsPay["Payment_AlipayEnabled"])){ ?>
					<li> <a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="zfb" data-value="支付宝"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝支付</a> </li>
					<?php if(!empty($rsPay["PaymentYeepayEnabled"])){?>
					<li style="display:none;"> <a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="ybzf" data-value="易宝支付"><img  src="/static/api/shop/skin/default/yeepay.png" width="16px" height="16px"/>&nbsp;易宝支付</a> </li>
					<?php }?>
					<?php }
                    if(!empty($rsPay["Payment_OfflineEnabled"])){?>
					<li> <a href="/api/<?=$UsersID?>/cloud/cart/complete_pay/huodao/<?=$OrderID?>/" class="btn btn-warning  btn-pay" id="out" data-value="线下支付"><img  src="/static/api/shop/skin/default/huodao.png" width="16px" height="16px"/>&nbsp;货到付款</a> </li>
					<?php }?>
					<li> <a href="/api/<?=$UsersID?>/cloud/cart/complete_pay/money/<?=$OrderID?>/" class="btn btn-warning  btn-pay" id="money" data-value="余额支付"><img  src="/static/api/shop/skin/default/money.jpg"  width="16px" height="16px"/>&nbsp;余额支付</a> </li>
				</ul>
			</div>
			<!-- 支付方式选择 end -->
			
			<input type="hidden" name="PaymentMethod" id="PaymentMethod_val" value="微支付"/>
			<input type="hidden" name="OrderID" value="<?php echo $rsOrder["Order_ID"] ?>" />
			<input type="hidden" name="total_price" value="<?php echo$rsOrder["Order_TotalPrice"] ?>" />
			<input type="hidden" name="action" value="payment" />
			<input type="hidden" name="DefautlPaymentMethod" value="<?php echo $rsOrder["Order_PaymentMethod"] ?>" />
		</form>
	</div>
</div>
</body>
</html>