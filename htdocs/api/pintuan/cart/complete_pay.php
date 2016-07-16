<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
$base_url = base_url();
$shop_url = shop_url();

if(isset($_GET["UsersID"])){
	$UsersID = $_GET["UsersID"];
}else {
	echo '缺少必要的参数';
	exit;
}
$share_flag = 0;
$signature = '';

$OrderID=$_GET['OrderID'];
$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");

$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
if(!$rsOrder)  {
    header("Location:/api/{$UsersID}/pintuan/");exit;
}
if($rsOrder['Order_Status'] !=1){
    header("Location:/api/{$UsersID}/pintuan/");exit;
}
$total = $rsOrder['Order_TotalPrice'];
$ordersn = $rsOrder["Order_Code"];

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
<title><?=$rsConfig["ShopName"] ?>付款</title>
<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/pintuan/js/pintuan1.js'></script>
<script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
<script language="javascript">$(document).ready(shop_obj.page_init);</script>
</head>

<body>
<div id="shop_page_contents">
	<div id="cover_layer"></div>
	<link href='/static/api/shop/skin/default/css/cart.css' rel='stylesheet' type='text/css' />
	<script language="javascript">$(document).ready(shop_obj.payment_init);</script>
	<div class="dingdan">
		<span class="fanhui l"><a href="javascript:history.go(-1);"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
		<span class="querendd l">完善支付信息</span>
		<div class="clear"></div>
	</div>
	<div id="payment">
		<div class="i-ture">
			<form id="payment_form" action="/api/<?=$UsersID ?>/pintuan/">
				<input type="hidden" name="PaymentMethod" id="PaymentMethod_val" value="<?=$method_list[$Paymethod]?>"/>
				<input type="hidden" name="OrderID" value="<?=$OrderID ?>" />
				<input type="hidden" name="action" value="payment" />
				<input type="hidden" name="UsersID" value="<?=$UsersID ?>" />
				<input type="hidden" name="DefautlPaymentMethod" value="余额支付" />

				<div class="info"> 订 单 号：<?=$ordersn ?><br />
					订单总价：<span class="fc_red">￥<?=$total ?></span><br/>
					账户余额:<span class="fc_red">￥<?=$rsUser["User_Money"] ?></span><br/>
					<?php if($Paymethod == 'money'){?>
					<?php if($rsUser["User_Money"] < $total){?>
					抱歉，余额不足<br/>
					<a href="/api/<?=$UsersID?>/shop/cart/payment/<?=$OrderID ?>/" class="fc_red">返回</a> &nbsp;&nbsp;&nbsp; <a href="/api/<?=$UsersID?>/user/charge/" class="fc_red">去充值</a>
					<?php }else{ ?>
					<div class="payment_password">
						<input type="password" name="PayPassword" placeholder="请输入支付密码" value="" />
					</div>
					<?php }?>
					<?php }else{ ?>
					线下支付信息:<?=$rsPay["Payment_OfflineInfo"] ?>
					<textarea name="PaymentInfo" placeholder="请输入支付信息，如转账帐号、时间等"></textarea>
					<?php }?>
				</div>
			</form>
		</div>
		<div class="button-panel">
			<?php if($rsUser["User_Money"] < $total){?>
			<button  class="btn  btn-info " id="btn-confirm" style="background:#C1B6AF;" disabled="disabled">确定</button>
			<?php }else{?>
			<button  class="btn  btn-info " id="btn-confirm">确定</button>
			<?php }?>
		</div>
	</div>
</div>
<div id="footer_points"></div>
</body>
</html>