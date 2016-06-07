<?php
require_once('../global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
$balance = new balance($DB,$rsBiz["Users_ID"]);

$paymentid=empty($_REQUEST['paymentid'])?0:$_REQUEST['paymentid'];
$rsPayment=$DB->GetRs("shop_sales_payment","*","where Users_ID='".$rsBiz["Users_ID"]."' and Payment_ID='".$paymentid."' and Biz_ID=".$_SESSION["BIZ_ID"]);
if(!$rsPayment){
	echo '<script language="javascript">alert("暂无信息！");history.back();</script>';
	exit;
}
$rsPayment["Biz"] = $rsBiz["Biz_Name"];
$condition = "where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Payment_ID=".$paymentid.' order by Record_ID desc';
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>
<script language=javascript>
function PrintPage1(){   
	var newstr = document.getElementById("printPage").innerHTML; 
	var oldstr = document.body.innerHTML; 
	document.body.innerHTML = newstr; 
	window.print(); 
	document.body.innerHTML = oldstr; 
	return false; 
}
</script>
<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/payment.js'></script>
    <div class="r_nav">
      <ul>
      	<li><a href="distribute_record.php">分销记录</a></li>
        <li><a href="sales_record.php">销售记录</a></li>
        <li class="cur"><a href="payment.php">收款单</a></li>
      </ul>
    </div>
    
    <div id="payment" class="r_con_wrap">
    	<div class="control_btn"><a href="#" class="btn_green btn_w_120" onclick="PrintPage1();">打印</a></div>
      <?php
      	$balance->echo_payment_info($condition,$rsPayment);
	  ?>
    </div>
  </div>
</div>
</body>
</html>