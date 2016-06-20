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
		$OrderID = intval($arr[1]);
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
$pay_orderno = $payinfo["out_trade_no"];
$pay_subject = $payinfo["subject"];

include_once("WxPayHelper.php");
$commonUtil = new CommonUtil();
$wxPayHelper = new WxPayHelper();

$wxPayHelper->setParameter("bank_type", "WX");
$wxPayHelper->setParameter("body", $pay_subject);
$wxPayHelper->setParameter("partner", PARTNERID);
$wxPayHelper->setParameter("out_trade_no", strval($pay_orderno));
$wxPayHelper->setParameter("total_fee", strval(floatval($pay_fee)*100));
$wxPayHelper->setParameter("fee_type", "1");
$wxPayHelper->setParameter("notify_url", "http://".$_SERVER['HTTP_HOST']."/pay/wxpay/notify_url.php");
$wxPayHelper->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);
$wxPayHelper->setParameter("input_charset", "GBK");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>微信在线支付</title>
</head>
<script type="text/javascript" language="javascript">

function callpay()
{
	WeixinJSBridge.invoke('getBrandWCPayRequest',<?php echo $wxPayHelper->create_biz_package(); ?>,function(res){
	WeixinJSBridge.log(res.err_msg);
	if(res.err_msg=='get_brand_wcpay_request:ok'){
	    document.getElementById('payDom').style.display='none';
		document.getElementById('successDom').style.display='';
		setTimeout("window.location.href = 'http://<?php echo $_SERVER['HTTP_HOST'];?>/pay/wxpay/notify_url.php?OrderID=<?php echo $OrderID;?>'",2000);
	}else{
	
		document.getElementById('payDom').style.display='none';
		document.getElementById('failDom').style.display='';
		document.getElementById('failRt').innerHTML=res.err_code+'|'+res.err_desc+'|'+res.err_msg;
		
	}
	});
}

</script>
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
	background-color:#179F00;
	padding:10px 20px;
	font-size:16px;
	text-decoration:none;
	border:1px solid #0B8E00;
	background-image: linear-gradient(bottom,  #179F00 0%, #5DD300 100%);
	background-image: -o-linear-gradient(bottom, #179F00 0%, #5DD300 100%);
	background-image: -moz-linear-gradient(bottom, #179F00 0%, #5DD300 100%);
	background-image: -webkit-linear-gradient(bottom, #179F00 0%, #5DD300 100%);
	background-image: -ms-linear-gradient(bottom, #179F00 0%, #5DD300 100%);
	background-image: -webkit-gradient(
	linear,
	left bottom,
	left top,
	color-stop(0, #179F00),
	color-stop(1, #5DD300)
	);
	-webkit-box-shadow: 0 1px 0 #94E700 inset, 0 1px 2px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 0 1px 0 #94E700 inset, 0 1px 2px rgba(0, 0, 0, 0.5);
	box-shadow: 0 1px 0 #94E700 inset, 0 1px 2px rgba(0, 0, 0, 0.5);
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-o-border-radius: 5px;
	border-radius: 5px;
	color: #ffffff;
	display:block;
	text-align:center;
	text-shadow:0 1px rgba(0, 0, 0, 0.2);
}
.submit:active {
	padding-bottom:9px;
	padding-left:20px;
	padding-right:20px;
	padding-top:11px;
	top:0px;
	background-image: linear-gradient(bottom, #5DD300 0%, #179F00 100%);
	background-image: -o-linear-gradient(bottom,  #5DD300 0%, #179F00 100%);
	background-image: -moz-linear-gradient(bottom,  #5DD300 0%, #179F00 100%);
	background-image: -webkit-linear-gradient(bottom,  #5DD300 0%, #179F00 100%);
	background-image: -ms-linear-gradient(bottom,  #5DD300 0%, #179F00 100%);
	background-image: -webkit-gradient(
	linear,
	left bottom,
	left top,
	color-stop(0, #5DD300),
	color-stop(1, #179F00)
	);
	-webkit-box-shadow: 0 1px 0 #5DD300 inset, 0 1px 2px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 0 1px 0 #5DD300 inset, 0 1px 2px rgba(0, 0, 0, 0.5);
	box-shadow: 0 1px 0 #5DD300 inset, 0 1px 2px rgba(0, 0, 0, 0.5);
}
.submit img{ width:15px; margin:-4px 10px 0 0;}
</style>
<div id="payDom" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>金额</th><td><?php echo $rsOrder["Order_TotalPrice"];?>元</td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="点击进行微信支付" /></div></div>



<div id="failDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="重新进行支付" /></div></div>


<div id="successDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>您已支付成功，页面正在跳转...</td></tr></table><div id="failRt"></div></td></tr></table></li></ul></div>

</body>
</html>