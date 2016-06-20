<?php
require_once('../global.php');

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');

$BizRs = $DB->GetRS('biz','Users_ID,UserID',"where Users_ID='".$rsBiz["Users_ID"]."' and BIZ_ID=".$_SESSION["BIZ_ID"]);
if(empty($BizRs['UserID'])){
    echo '<script language="javascript">alert("您没有绑定前台会员,暂不能结款!");history.back();</script>';
    exit;
}

$balance = new balance($DB, $rsBiz["Users_ID"]);

if($_POST){
	$Time = empty($_POST["Time"]) ? array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime = strtotime($Time[0]);
	$EndTime = strtotime($Time[1]);
	$condition = "where Biz_ID=".$_SESSION["BIZ_ID"]." and Users_ID='".$rsBiz["Users_ID"]."' and Record_CreateTime>=".$StartTime." and Record_CreateTime<=".$EndTime." and Record_Status=0";
	$paymentinfo = $balance->create_payment($condition);
	if($paymentinfo["products_num"] == 0){
		echo '<script language="javascript">alert("暂无结算数据");history.back();</script>';
		exit;
	}
	$createtime = time();
	$Data = array(
		"FromTime"=>$StartTime,
		"EndTime"=>$EndTime,
		"Amount"=>$paymentinfo["alltotal"],
		"Diff"=>$paymentinfo["cash"],
		"Web"=>$paymentinfo["web"]-$paymentinfo["bonus"],
		"Bonus"=>$paymentinfo["bonus"],
		"Total"=>$paymentinfo["supplytotal"],
		"Bank"=>$_POST["Bank"],
		"BankNo"=>$_POST["BankNo"],
		"BankName"=>$_POST["BankName"],
		"BankMobile"=>$_POST["BankMobile"],
		"CreateTime"=>$createtime,
		"Biz_ID"=>$_SESSION["BIZ_ID"],
		"Users_ID"=>$rsBiz["Users_ID"]
	);
	$Flag = $DB->Add("shop_sales_payment", $Data);
	$paymentid = $DB->insert_id();
	if($Flag){
		$DB->Set("shop_sales_record",array("Record_Status"=>1,"Payment_ID"=>$paymentid),$condition);
		$Payment_Sn = $createtime.$paymentid;
		$DB->Set("shop_sales_payment",array("Payment_Sn"=>$Payment_Sn),"where Payment_ID=".$paymentid);
		echo '<script language="javascript">window.location.href="payment_detail.php?paymentid='.$paymentid.'";</script>';
	}else{
		echo '<script language="javascript">alert("生成失败！");history.back();</script>';
	}
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

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
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
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
      <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
      <script language="javascript">$(document).ready(payment.payment_edit_init);</script>
      <form id="payment_form" class="r_con_form" method="post" action="?">
        <div class="rows">
          <label>结算时间</label>
          <span class="input time">
          <input name="Time" type="text" value="<?php echo date("Y-m-d H:i:s") ?> - <?php echo date("Y-m-d H:i:s",strtotime("+7 day")) ?>" class="form_input" size="40" readonly notnull />
          <font class="fc_red">*</font> <span class="tips">需要结算的销售记录的时间段</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>银行类型</label>
          <span class="input">
          <input name="Bank" value="" type="text" class="form_input" size="40" maxlength="100" notnull>
          <font class="fc_red">*</font> <span class="tips">如交通银行，***分行</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>银行卡号</label>
          <span class="input">
          <input name="BankNo" value="" type="text" class="form_input" size="40" maxlength="100" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>收款人</label>
          <span class="input">
          <input name="BankName" value="" type="text" class="form_input" size="40" maxlength="100" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>收款人手机</label>
          <span class="input">
          <input name="BankMobile" value="" type="text" id="BankMobile" class="form_input" size="40" maxlength="100" notnull>
          <font class="fc_red">*</font><span class="tips">手机号必须跟会员中心绑定的手机号一致</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="一键生成" name="submit_btn"></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>