<?php
header("Content-type: text/html; charset=UTF-8");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/pay/teegon/autoload.php');
$OrderID = "";
if(isset($_GET["UsersID"])){
	if(!strpos($_GET["UsersID"],'_')){
		echo '缺少必要的参数';
		exit;
	}else{
		$arr = explode("_",$_GET["UsersID"]);
		$UsersID = $arr[0];
		$OrderID = $arr[1];
	}
}else{
	echo '缺少必要的参数';
	exit;
}
$rsPay=$DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
require_once(__DIR__.'/config.php');
$rsCharge = new Charge();

$pay_order = new pay_order($DB,$OrderID);
$payinfo = $pay_order->get_pay_info();

if(isset($_POST['action']) && $_POST['action'] == 'pay'){
	$data = [];
	$data['order_no'] = strval($payinfo["out_trade_no"]);
	$data['return_url'] = RETURN_URL;
	$data['amount'] = $payinfo["total_fee"];
	$data['subject'] = $payinfo["subject"];
	$data['metadata'] = json_encode(['account_id' => isset($rsPay['account_id'])?$rsPay['account_id']:'']);
	$data['notify_url'] = NOTIFY_URL;
	$data['pay_channel'] = $_POST['channel'];
	$data['profit-sharing-mode'] = 'manual';
	$data['account_id'] = 'main';
	$t= $rsCharge->chargePay($data,false);
	echo $t;
	exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>商派天工安全支付</title>
<link href='/static/api/shop/skin/default/css/cart.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
</head>
<body style="padding-top:20px;">
<div id="shop_page_contents">
	<div id="cover_layer"></div>
	
	<div id="payment">
		<form>
			<!-- 支付方式选择 begin  -->
			<div class="i-ture">
				<h1 class="t">商派天工支付</h1>
				<ul id="pay-btn-panel">
					<li> <a href="javascript:void(0)" class="pb btn btn-danger btn-pay direct_pay" channel="alipay"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝</a> </li>
					<li> <a href="javascript:void(0)" class="pb btn btn-default btn-pay direct_pay" channel="wxpay_jsapi"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信</a> </li>
					<li> <a href="javascript:void(0)" class="pb btn btn-default btn-pay direct_pay" channel="chinapay"><img  src="/static/api/shop/skin/default/Unionpay.png" width="16px" height="16px"/>&nbsp;银联</a> </li>
				</ul>
			</div>
		</form>
	</div>
</div>
	<script>
		var TEE_API_URL="<?php echo constant('TEE_API_URL')?>";
		var client_id  =  "<?php echo constant('TEE_CLIENT_ID')?>";
		$('.pb').click(function(e){
            $.ajax({
                data: "client_id="+client_id+"&channel="+$(e.target).attr('channel')+"&action=pay",
                method:'post'
            }).done(tee.charge);
        });
    </script>
    <script src="<?php echo constant('TEE_SITE_URL')?>pay/teegon/jslib/t-charging.js"></script>
    <script src="<?php echo constant('TEE_SITE_URL')?>pay/teegon/jslib/jquery-1.11.1.min.js"></script>
</body>
</html>