<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
$balance = new balance($DB,$_SESSION["Users_ID"]);

$paymentid=empty($_REQUEST['paymentid'])?0:$_REQUEST['paymentid'];
$rsPayment=$DB->GetRs("shop_sales_payment","*","where Users_ID='".$_SESSION["Users_ID"]."' and Payment_ID='".$paymentid."'");
if(!$rsPayment){
	echo '<script language="javascript">alert("暂无信息！");history.back();</script>';
	exit;
}
if($rsPayment["Biz_ID"]==0){
	$rsPayment["Biz"] = "本站供货";
}else{
	$item = $DB->GetRs("biz","Biz_Name","where Biz_ID=".$rsPayment["Biz_ID"]);
	if($item){
		$rsPayment["Biz"] = $item["Biz_Name"];
	}else{
		$rsPayment["Biz"] = "已被删除";
	}
}
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Payment_ID=".$paymentid.' order by Record_ID desc';

$DB->Get('biz','*',"where Users_ID='".$_SESSION["Users_ID"]."'");
while($BizRs = $DB->fetch_assoc()){
     $BizPayRate[$BizRs["Biz_ID"]] = empty($BizRs['PaymenteRate'])?'100':$BizRs['PaymenteRate'];    
}
 
if(!empty($BizPayRate[$rsPayment['Biz_ID']])){
    $rsPayment['Total'] = $rsPayment['Total']*$BizPayRate[$rsPayment['Biz_ID']]/100;
} 

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
        <li><a href="sales_record.php">销售记录</a></li>
        <li class="cur"><a href="payment.php">付款单</a></li>
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