<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
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
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");

$pay_order = new pay_order($DB,$OrderID);
$payinfo = $pay_order->get_pay_info();

$pay_fee = $payinfo["total_fee"];
$pay_total = strval(floatval($pay_fee)*100);
$pay_orderno = strval($payinfo["out_trade_no"]);
$pay_subject = $payinfo["subject"];

include_once("WxPay.pub.config.php");
include_once("WxPayPubHelper.php");
$jsApi = new JsApi_pub();
if (!isset($_GET['code'])){
	$url = $jsApi->createOauthUrlForCode(JS_API_CALL_URL);
	Header("Location: $url");
}else{
	$code = $_GET['code'];
	$jsApi->setCode($code);
}
$openid = $jsApi->getOpenid();
$unifiedOrder = new UnifiedOrder_pub();
$unifiedOrder->setParameter("openid","$openid");
$unifiedOrder->setParameter("body","$pay_subject");
$unifiedOrder->setParameter("out_trade_no","$pay_orderno");
$unifiedOrder->setParameter("total_fee","$pay_total");
$unifiedOrder->setParameter("notify_url",NOTIFY_URL);
$unifiedOrder->setParameter("trade_type","JSAPI");
$prepay_id = $unifiedOrder->getPrepayId();

$jsApi->setPrepayId($prepay_id);
$jsApiParameters = $jsApi->getParameters();
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
    <title>微信安全支付</title>

	<script type="text/javascript">
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					if(res.err_msg=='get_brand_wcpay_request:ok'){
						window.location.href = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/pay/wxpay2/notify_url.php?OrderID=<?php echo $OrderID;?>';
					}
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
	</script>
</head>
<body style="padding-top:20px;">
<style>
body, article, section, h1, h2, hgroup, p, a, ul, li, em, div, small, span, footer, canvas, figure, figcaption, input {
    margin: 0;
    padding: 0;
}
a {
	color:#333;
    cursor: pointer;
    text-decoration: none;
}
ul,li{
    list-style-type: none;
}
.clr{
	clear:both;
}
body {
    background-color: #ECECEC;
    font-family: Microsoft YaHei,Helvitica,Verdana,Tohoma,Arial,san-serif;
    margin: 0;
    overflow-x: hidden;
    padding: 0;
	color: #666666;
}
.cardexplain{
	margin:11px 10px 20px 9px;
	min-width:301px;
}
ul.round {
	border:1px solid #C6C6C6;
	background-color:rgba(255, 255, 255, 0.9);
	text-align:left;
	font-size:14px;
	line-height:24px;
	border-radius:5px;
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	-moz-box-shadow:0 1px 1px #f6f6f6;
	-webkit-box-shadow:0 1px 1px #f6f6f6;
	box-shadow:0 1px 1px #f6f6f6;
	margin-bottom:11px;
	display:block
}

ul.round li {
	border:solid #C6C6C6;
	border-width:0 0 1px 0;
	padding:0px 10px 0 10px;
}

.round li, .round li span, .round li a {
	line-height:22px;
}
.round li span {
	display:block;
	background:url(img/arrow3.png) no-repeat right 50%;
	-webkit-background-size:8.5px 13px;
	background-size:8.5px 13px;
	padding:10px 20px 9px 0;
	position:relative;
	font-size:16px;
	min-height: 22px;
}
.round li span.none {
    background: none repeat scroll 0 0 transparent;
}
.round li span.noneorder {
    background: none repeat scroll 0 0 transparent;
	padding:10px 5px 9px 0;
}
.round li span.none em {
    right: 0;
}
.mb{ margin-bottom:4px}
.round li.nob {
    border-width:0;
}
.kuang th {
    color: #333333;padding:0; font-size:16px; font-weight:normal;text-align: left;width: 79px;
}
.kuang td {
    color: #999999;padding:0;
}
.submit[type="button"] {
     width: 100%; 
     box-sizing: border-box;
     -webkit-box-sizing:border-box;
     -moz-box-sizing: border-box;
}
.footReturn {
    display: block;
    margin: 11px auto;
    padding: 0;
	position: relative;
}
.submit {
	background:#179F00;
	padding:10px 20px;
	font-size:16px;
	text-decoration:none;
	color: #ffffff;
	display:block;
	text-align:center;;
	border:none;
}
.submit:active {
	padding-bottom:9px;
	padding-left:20px;
	padding-right:20px;
	padding-top:11px;
	top:0px;
}
.submit img{ width:15px; margin:-4px 10px 0 0;}
</style>
<div id="payDom" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>金额</th><td><?php echo $pay_fee;?>元</td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="点击进行微信支付" /></div></div>

</body>
</html>