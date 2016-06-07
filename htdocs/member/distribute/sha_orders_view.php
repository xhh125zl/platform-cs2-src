<?php
ini_set ( "display_errors", "On" );
require_once('agent_orders_global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$rsOrder=$DB->GetRs("sha_order","*","where Users_ID='".$_SESSION['Users_ID']."' and Order_ID=".$OrderID);
$Order_Status=array("待审核","待付款","已付款",'已取消','已拒绝');
$curid = 1;
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />    
    <?php require_once('shamenu.php'); ?>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>    
    <div id="orders" class="r_con_wrap">
      <div class="detail_card">
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
			<?php if($rsOrder["Order_Status"] == 4):?>
			<tr>
              <td nowrap>拒绝原因：</td>
              <td><?=$rsOrder["Refuse_Be"]?></td>
            </tr>
			<?php endif;?>
			<tr>				
              <td colspan="2"><a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a></td>
            </tr> 
          </table>
      </div>	  
    </div>	
  </div> 
</div>
</body>
</html>