<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

$share_flag=0;
$signature="";

$OrderID=$_GET['OrderID'];
$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
$rsOrder=$DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID='".$OrderID."'");
$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);

$Paymethod = $_GET['Paymethod'];

$method_list = array("money"=>"余额支付","huodao"=>"线下支付");

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>积分兑换订单付款</title>
<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/cloud/js/shipping.js'></script>
</head>

<body>
<div id="shop_page_contents">
	<div id="cover_layer"></div>
	<link href='/static/api/shop/skin/default/css/cart.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
	<script language="javascript">$(document).ready(shipping_obj.shipping_payment_init);</script>
	<div id="payment">
		<div class="i-ture">
			<form id="payment_form" action="/api/<?php echo $UsersID ?>/cloud/member/products/ajax/">
				<input type="hidden" name="PaymentMethod" id="PaymentMethod_val" value="<?=$method_list[$Paymethod]?>"/>
				<input type="hidden" name="OrderID" value="<?php echo $rsOrder["Orders_ID"] ?>" />
				<input type="hidden" name="action" value="payment" />
				<input type="hidden" name="DefautlPaymentMethod" value="" />
				<h1 class="t">完善支付信息！</h1>
				<div class="info"> 订 单 号：<?php echo date("Ymd",$rsOrder["Orders_CreateTime"]).$rsOrder["Orders_ID"] ?><br />
					订单总价：<span class="fc_red">￥<?php echo $rsOrder["Orders_TotalPrice"] ?></span><br/>
					账户余额:<span class="fc_red">￥<?php echo $rsUser["User_Money"] ?></span><br/>
					<?php if($Paymethod == 'money'):?>
					<?php if($rsUser["User_Money"] < $rsOrder["Orders_TotalPrice"]):?>
					抱歉，余额不足<br/>
					<a href="/api/<?=$UsersID?>/shop/cart/payment/<?=$OrderID?>/" class="fc_red">返回</a> &nbsp;&nbsp;&nbsp; <a href="/api/<?=$UsersID?>/user/charge/" class="fc_red">去充值</a>
					<?php else: ?>
					<div class="payment_password">
						<input type="password" name="PayPassword" placeholder="请输入支付密码" value="" />
					</div>
					<?php endif;?>
					<?php else: ?>
					线下支付信息:<?php echo $rsPay["Payment_OfflineInfo"] ?>
					<textarea name="PaymentInfo" placeholder="请输入支付信息，如转账帐号、时间等"></textarea>
					<?php endif;?>
				</div>
			</form>
		</div>
		<div class="button-panel">
			<button  class="btn  btn-info " id="btn-confirm">确定</button>
		</div>
	</div>
</div>
<?php require_once('../member_footer.php'); ?>
</body>
</html>