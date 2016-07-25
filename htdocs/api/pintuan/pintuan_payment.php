<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');


$OrderID = $_GET['OrderID'];
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");

if(strpos($OrderID,"PRE") !== false){
	$rsOrder=$DB->GetRs("user_pre_order","*","where usersid='".$UsersID."' and userid='".$UserID."' and pre_sn='".$OrderID."'");
	$total = $rsOrder['total'];
	$ordersn = $rsOrder['pre_sn'];
}else{
	$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$UserID."' and Order_ID='".$OrderID."'");

	$total = $rsOrder['Order_TotalPrice'];
	$ordersn = date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"];

}

$rsUser = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$UserID);

/*///计算参加抵用后的余额
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
}*/

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
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
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
			<div class="info"> 订 单 号：<?php echo $ordersn;?><br />
				订单总价：<span class="fc_red" id="Order_TotalPrice">￥<?php echo $total;?><br/>
				</span> 
				<?php if($rsOrder['Integral_Money'] > 0):?>
        已经用<?=$rsOrder['Integral_Consumption']?>个积分抵用了<span class="fc_red"><?=$rsOrder['Integral_Money']?></span>元
        <?php endif; ?>
			</div>
		</div>
		 <!-- 积分查询 -->
			<!-- 支付方式选择 begin  -->
			<div class="i-ture">
				<h1 class="t">选择支付方式</h1>
				<ul id="pay-btn-panel">
					<?php if(!empty($rsPay["PaymentWxpayEnabled"])){ ?>
					<li> <a href="javascript:void(0)" class="btn btn-default btn-pay direct_pay" id="wzf" data-value="微支付"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信支付</a> </li>
					<?php }?>
					<?php if(!empty($rsPay["Payment_AlipayEnabled"])){?>
					<li> <a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="zfb" data-value="支付宝"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝支付</a> </li>
					<?php if(!empty($rsPay["PaymentYeepayEnabled"])){?>
					<li style="display:none;"> <a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="ybzf" data-value="易宝支付"><img  src="/static/api/shop/skin/default/yeepay.png" width="16px" height="16px"/>&nbsp;易宝支付</a> </li>
					<?php }?>
					<?php }
					if(!empty($rsPay["Payment_UnionpayEnabled"])){ ?>
            <li>
            	 <a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="uni" data-value="银联支付"><img  src="/static/api/shop/skin/default/Unionpay.png" width="16px" height="16px"/>&nbsp;银联支付</a>
            </li>
            <?php }
                   if(!empty($rsPay["Payment_OfflineEnabled"])){?>
					<li> <a href="/api/<?=$UsersID?>/shop/cart/complete_pay/huodao/<?=$OrderID?>/" class="btn btn-warning  btn-pay" id="out" data-value="线下支付"><img  src="/static/api/shop/skin/default/huodao.png" width="16px" height="16px"/>&nbsp;货到付款</a> </li>
					<?php }?>
					<li> <a href="/api/<?=$UsersID?>/pintuan/pintuan_complepay/money/<?=$OrderID?>/" class="btn btn-warning  btn-pay" id="money" data-value="余额支付"><img  src="/static/api/shop/skin/default/money.jpg"  width="16px" height="16px"/>&nbsp;余额支付</a> </li>
				</ul>
			</div>
			<!-- 支付方式选择 end -->
			
			<input type="hidden" name="PaymentMethod" id="PaymentMethod_val" value="微支付"/>
			<input type="hidden" name="OrderID" value="<?php echo $OrderID;?>" />
			<input type="hidden" name="total_price" value="<?php echo $rsOrder["Order_TotalPrice"] ?>" />
			<input type="hidden" name="action" value="payment" />
			<input type="hidden" name="DefautlPaymentMethod" value="" />
		</form>
	</div>
</div>
<div id="footer_points"></div>
</body>
</html>
