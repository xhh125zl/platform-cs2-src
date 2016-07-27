<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='{$UsersID}' and Order_ID=".$OrderID);
if($rsOrder["Order_Status"]<>0){
	echo '<script language="javascript">alert("只有状态为“待确认”的订单才可确认订单");history.back();</script>';
	exit;
}
if($_POST){
	$Data=array(
		"Address_Name"=>$_POST['Name'],
		"Address_Mobile"=>$_POST["Mobile"],
		"Order_Remark"=>$_POST["Remark"],
		"Order_Status"=>$_POST["Status"]
	);
	$Flag=$DB->Set("user_order",$Data,"where Order_ID=".$OrderID);
	if($Flag){
		echo '<script language="javascript">alert("订单已确认");window.location="orders.php";</script>';
	}else{
		echo '<script language="javascript">alert("订单已确认");history.back();</script>';
	}
	exit;
}else{
	$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='{$UsersID}'");
	$rsPay=$DB->GetRs("users_payconfig","Shipping","where Users_ID='{$UsersID}'");
	
	$Status=$rsOrder["Order_Status"];
	$Order_Status=array("待确认","待付款","已付款","已发货","已完成");
	
	$PayShipping = get_front_shiping_company_dropdown($UsersID,$rsConfig);
	
	$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
	$amount = $fee = 0;
	
	$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
	$area_array = json_decode($area_json,TRUE);
	$province_list = $area_array[0];
	$Province = '';
	if(!empty($rsOrder['Address_Province'])){
		$Province = $province_list[$rsOrder['Address_Province']].',';
	}
	$City = '';
	if(!empty($rsOrder['Address_City'])){
		$City = $area_array['0,'.$rsOrder['Address_Province']][$rsOrder['Address_City']].',';
	}

	$Area = '';
	if(!empty($rsOrder['Address_Area'])){
		$Area = $area_array['0,'.$rsOrder['Address_Province'].','.$rsOrder['Address_City']][$rsOrder['Address_Area']];
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="orders.php">订单列表</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
    <script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script> 
    <script language="javascript">$(document).ready(shop_obj.orders_send);</script>
    <div id="orders" class="r_con_wrap">
      <div class="detail_card">
        <form id="order_send_form" method="post" action="?">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>订单编号：</td>
              <td width="92%"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
            </tr>
			<tr>
              <td nowrap>物流费用：</td>
              <td>
			  <?php if(!isset($Shipping) || empty($Shipping["Price"])){?>
				免运费 
			  <?php }else{
				$fee = $Shipping["Price"];
			  ?>
				 ￥<?php echo $Shipping["Price"];?>
			  <?php }?>
              </td>
            </tr>
            <tr>
              <td nowrap>订单总价：</td>
              <td>￥<?php echo $rsOrder["Order_TotalPrice"] ?></td>
            </tr>
			<?php if($rsOrder["Coupon_ID"]>0){?>
			<tr>
			  <td nowrap>优惠详情</td>
			  <td><font style="color:blue;">已使用优惠券</font>(
				  <?php if($rsOrder["Coupon_Discount"]>0){?>
				  享受<?php echo $rsOrder["Coupon_Discount"]*10;?>折
				  <?php }?>
				  <?php if($rsOrder["Coupon_Cash"]>0){?>
				  抵现金<?php echo $rsOrder["Coupon_Cash"];?>元
				  <?php }?>)
			  </td>
			</tr>
			<?php }?>
            <tr>
              <td nowrap>订单时间：</td>
              <td><?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></td>
            </tr>
            <tr>
              <td nowrap>订单状态：</td>
              <td><select name="Status">
					<option value="0"<?php echo $rsOrder["Order_Status"]==0 ? ' selected' : ''?>>待确认</option>
					<option value="1"<?php echo $rsOrder["Order_Status"]==1 ? ' selected' : ''?>>待付款</option>
					<option value="2"<?php echo $rsOrder["Order_Status"]==2 ? ' selected' : ''?>>已付款</option>
					<option value="3"<?php echo $rsOrder["Order_Status"]==3 ? ' selected' : ''?>>已发货</option>
					<option value="4"<?php echo $rsOrder["Order_Status"]==4 ? ' selected' : ''?>>已完成</option>
				</select></td>
            </tr>
            <tr>
              <td nowrap>支付方式：</td>
              <td><?php echo empty($rsOrder["Order_PaymentMethod"]) || $rsOrder["Order_PaymentMethod"]=="0" ? "暂无" : $rsOrder["Order_PaymentMethod"]; ?></td>
            </tr>
            <?php if($rsOrder["Order_PaymentMethod"]=="线下付款"){?>
            <tr>
              <td nowrap>付款信息：</td>
              <td><?php echo $rsOrder["Order_PaymentInfo"] ?></td>
            </tr>
            <?php }?>
            <tr>
              <td nowrap>联系人：</td>
              <td><input name="Name" value="<?php echo $rsOrder["Address_Name"] ?>" size="10" notnull /></td>
            </tr>
            <tr>
              <td nowrap>手机号码：</td>
              <td><input name="Mobile" value="<?php echo $rsOrder["Address_Mobile"] ?>" size="15" notnull /></td>
            </tr>
            <tr>
              <td nowrap>配送方式：</td>
              <td><?php echo isset($Shipping) && isset($Shipping["Express"])?$Shipping["Express"]:"" ?></td>
            </tr>
            <tr>
              <td nowrap>地址信息：</td>
              <td><?php echo $Province.$City.$Area.'【'.$rsOrder["Address_Name"].'，'.$rsOrder["Address_Mobile"].'】'.'&nbsp;&nbsp;&nbsp;&nbsp;详细地址: '.$rsOrder["Address_Detailed"] ?></td>
            </tr>
			<tr>
              <td nowrap>是否需要发票：</td>
              <td><?php echo $rsOrder["Order_NeedInvoice"]==1 ? '<font style="color:#F60">是</font>': "否" ?></td>
            </tr>
			<?php if($rsOrder["Order_NeedInvoice"]==1){ ?>
			<tr>
              <td nowrap>发票抬头：</td>
              <td><?php echo $rsOrder["Order_InvoiceInfo"];?></td>
            </tr>
			<?php }?>
            <tr>
              <td nowrap>订单备注：</td>
              <td><textarea name="Remark" rows="5" cols="50"><?php echo $rsOrder["Order_Remark"] ?></textarea></td>
            </tr>
            <tr>
              <td></td>
              <td><input type="submit" class="btn_green" name="submit_button" value="确认" /></td>
            </tr>
          </table>
          <input type="hidden" name="OrderID" value="<?php echo $rsOrder["Order_ID"] ?>" />
        </form>
        <div class="blank12"></div>
        <div class="item_info">物品清单</div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="order_item_list">
          <tr class="tb_title">
            <td width="20%">图片</td>
            <td width="35%">产品信息</td>
            <td width="15%">价格</td>
            <td width="15%">数量</td>
            <td width="15%" class="last">小计</td>
          </tr>
          <?php $total=0;
$qty=0;
foreach($CartList as $key=>$value){
	foreach($value as $k=>$v){
		$total+=$v["Qty"]*$v["ProductsPriceX"];
		$qty+=$v["Qty"];
		echo '<tr class="item_list" align="center">
            <td valign="top"><img src="'.$v["ImgPath"].'" width="100" height="100" /></td>
            <td align="left" class="flh_180">'.$v["ProductsName"].'<br>';
		foreach($v["Property"] as $Attr_ID=>$Attr){
			
			echo '<dd>'.$Attr['Name'].': '.$Attr['Value'].'</dd>';
		}
        echo '</td>
            <td>￥'.$v["ProductsPriceX"].'</td>
            <td>'.$v["Qty"].'</td>
            <td>￥'.$v["ProductsPriceX"]*$v["Qty"].'</td>
          </tr>';
	}
}?>
          <tr class="total">
            <td colspan="3">&nbsp;</td>
            <td><?php echo $qty ?></td>
            <td>￥<?php echo $total ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>