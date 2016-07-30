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

$rsConfig = $DB->GetRs("shop_config", "*", "where Users_ID='" . $UsersID . "'");

$share_flag=0;
$signature="";

$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$OrderID=$_GET['OrderID'];

$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
$rsOrder=$DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID='".$OrderID."'");

$total = $rsOrder['Orders_TotalPrice'];
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
  <script language="javascript">$(document).ready(shipping_obj.shipping_payment_init());</script>
  <div id="payment">
    <div class="i-ture">
      <h1 class="t" style="padding:0px 0px 12px 0px; margin:0px">订单提交成功！</h1>
      <div class="info"> 订 单 号：<?php echo date("Ymd",$rsOrder["Orders_CreateTime"]).$rsOrder["Orders_ID"] ?><br />
        订单总价：<span class="fc_red" id="Order_TotalPrice">￥<?php echo $rsOrder["Orders_TotalPrice"]?><br/></span>
        </div>
    
    </div>
    
    
    <form id="payment_form" action="/api/<?php echo $UsersID ?>/cloud/member/products/ajax/">
      	<div class="i-ture">
        <h1 class="t" style="padding:0px 0px 12px 0px; margin:0px">选择支付方式</h1>

        	<ul id="pay-btn-panel">
            <?php if(!empty($rsPay["PaymentWxpayEnabled"])){ ?>
			<li>
            	 <a href="javascript:void(0)" class="btn btn-default btn-pay direct_pay" id="wzf" data-value="微支付"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信支付</a>
                 
            </li>
            <?php }?>
<?php if(!empty($rsPay["Payment_AlipayEnabled"])){ ?>
            <li>
            	 <a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="zfb" data-value="支付宝"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝支付</a>
            </li>
			<?php if(!empty($rsPay["PaymentYeepayEnabled"])){?>			
            <li style="display:none;">
            	 <a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="ybzf" data-value="易宝支付"><img  src="/static/api/shop/skin/default/yeepay.png" width="16px" height="16px"/>&nbsp;易宝支付</a>
            </li>
            <?php }?>
            <?php }
if(!empty($rsPay["Payment_OfflineEnabled"])){?>
             <li>
             <a href="/api/<?=$UsersID?>/cloud/member/products/complete_pay/huodao/<?=$OrderID?>/" class="btn btn-warning  btn-pay" id="out" data-value="线下支付"><img  src="/static/api/shop/skin/default/huodao.png" width="16px" height="16px"/>&nbsp;货到付款</a>
            </li>
    <?php }?>        

             <li>
             <a href="/api/<?=$UsersID?>/cloud/member/products/complete_pay/money/<?=$OrderID?>/" class="btn btn-warning  btn-pay" id="money" data-value="余额支付"><img  src="/static/api/shop/skin/default/money.jpg"  width="16px" height="16px"/>&nbsp;余额支付</a>
            </li>	
            </ul>
  
        </div>
      <!-- 支付方式选择 end -->      
      <input type="hidden" name="PaymentMethod" id="PaymentMethod_val" value="微支付"/>
      <input type="hidden" name="OrderID" value="<?php echo $rsOrder["Orders_ID"] ?>" />
      <input type="hidden" name="action" value="payment" />
      <input type="hidden" name="DefautlPaymentMethod" value="" />
    </form>
  </div>
</div>
</body>
</html>