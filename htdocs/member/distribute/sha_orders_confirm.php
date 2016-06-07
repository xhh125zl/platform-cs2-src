<?php
require_once('sha_orders_global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$rsOrder=$DB->GetRs("sha_order","*","where Users_ID='".$_SESSION['Users_ID']."' and Order_ID=".$OrderID);
$Order_Status=array("待审核","待付款","已付款",'已取消','已拒绝');
if($rsOrder["Order_Status"] <> 0){
	echo '<script language="javascript">alert("只有状态为“待审核”的申请才可以审核");history.back();</script>';
	exit;
}
if($_POST){
	if($_POST["refuse"] == 1){
		$Data=array(		
		"Order_Status"=>1
	);
	}else{
	$Data=array(
		"Order_Status"=>4,
		"Refuse_Be"=>$_POST["refusebe"]
	);	
	}
	$Flag=$DB->Set("sha_order",$Data,"where Order_ID=".$OrderID);
	if($Flag){
		if($_POST["refuse"] == 1){
		echo '<script language="javascript">alert("申请已通过");window.location="sha_orders.php";</script>';
	}else{
		echo '<script language="javascript">alert("申请已回绝");window.location="sha_orders.php";</script>';
	}	
	}else{
		echo '<script language="javascript">alert("审核出错");history.back();</script>';
	}
	exit;
}
$curid = 1;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css?t=14713' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/distribute/order.js'></script>
    <?php require_once('shamenu.php'); ?>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
    <script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script> 
    <script language="javascript">$(document).ready(order_obj.orders_send);</script>
    <div id="orders" class="r_con_wrap">
      <div class="detail_card">
	  <form id="order_send_form" class="s_con_form" method="post" action="?">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="3%" nowrap>订单编号：</td>
              <td width="92%"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
            </tr>			
            <tr>
              <td nowrap>订单总价：</td>
              <td>￥<?php echo $rsOrder["Order_TotalPrice"] ?></td>
            </tr>			
            <tr>
              <td nowrap>订单时间：</td>
              <td><?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></td>
            </tr>
            <tr>
              <td nowrap>订单状态：</td>
              <td><?=$Order_Status[$rsOrder["Order_Status"]]?></td>
            </tr>
            <tr>
              <td nowrap>支付方式：</td>
              <td><?php echo empty($rsOrder["Order_PaymentMethod"]) || $rsOrder["Order_PaymentMethod"]=="0" ? "暂无" : $rsOrder["Order_PaymentMethod"]; ?></td>
            </tr>            
            <tr>
              <td nowrap>联系人：</td>
              <td><?=$rsOrder["Applyfor_Name"]?></td>
            </tr>
            <tr>
              <td nowrap>手机号码：</td>
              <td><?=$rsOrder["Applyfor_Mobile"]?></td>
            </tr>           
			<tr>
			<tr>
              <td nowrap>是否拒绝：</td>
              <td>
			  <input class="input" type="radio" name="refuse" value="1" checked /><label for="c_0">通过</label>&nbsp;&nbsp;<input class="input" type="radio" name="refuse"  value="0" /><label for="c_1">不通过</label>
			  </td>
            </tr>
			<tr id='refuseshow' style="display:none">
              <td nowrap>原因：</td>
              <td><textarea name="refusebe" style="width:30%;height:120px;"></textarea></td>
            </tr>
              <td><input type="submit" class="btn_green" name="submit_button" value="通过审核" /></td>
			  <td colspan="2"><a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a></td>
            </tr>
          </table>
		  <input type="hidden" name="OrderID" value="<?php echo $rsOrder["Order_ID"] ?>" />
        </form>        
      </div>
    </div>
  </div>
</div>
</body>
</html>