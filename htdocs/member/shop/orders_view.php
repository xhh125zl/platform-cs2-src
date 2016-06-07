<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID='".$OrderID."'");

$rsConfig=$DB->GetRs("shop_config","ShopName,NeedShipping","where Users_ID='".$_SESSION["Users_ID"]."'");
$rsPay=$DB->GetRs("users_payconfig","Shipping","where Users_ID='".$_SESSION["Users_ID"]."'");

$Status=$rsOrder["Order_Status"];
$Order_Status=array("待确认","待付款","已付款","已发货","已完成","申请退款中");
$Shipping=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]),true);
$PayShipping=empty($rsPay["Shipping"])?array():json_decode($rsPay["Shipping"],true);
$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
$amount = $fee = 0;

$lists_back = array();
if($rsOrder["Is_Backup"]==1){
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID=".$OrderID." and Back_Type='shop'";
	$DB->Get("user_back_order","*",$condition);
	while($b=$DB->fetch_assoc()){
		$lists_back[] = $b;
	}
}
$_STATUS = array('<font style="color:#F00; font-size:12px;">申请中</font>','<font style="color:#F60; font-size:12px;">卖家同意</font>','<font style="color:#0F3; font-size:12px;">买家发货</font>','<font style="color:#600; font-size:12px;">卖家收货并确定退款价格</font>','<font style="color:blue; font-size:12px;">完成</font>','<font style="color:#999; font-size:12px; text-decoration:line-through;">卖家拒绝退款</font>');
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
        <li class="cur"><a href="orders.php">订单管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">
var NeedShipping=1;
var orders_status=["待确认","待付款","已付款","已发货","已完成","申请退款中"];
$(document).ready(shop_obj.orders_init);
</script>
    <div id="orders" class="r_con_wrap">
      <div class="control_btn">
      <a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a>
      <a href="order_print.php?OrderID=<?=$rsOrder["Order_ID"]?>" target="blank" class="btn_gray" id="order_print">打印订单</a>
      </div>
      <script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script> 
      <div class="detail_card">
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
              <td>￥<?php echo $rsOrder["Order_TotalPrice"] ?><?php echo $rsOrder["Back_Amount"]>0 ? '&nbsp;&nbsp;<font style="text-decoration:line-through; color:#999">&nbsp;退款金额：￥'.$rsOrder["Back_Amount"].'&nbsp;</font>&nbsp;&nbsp;' : "";?></td>
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
              <td><?php if($rsOrder["Order_TotalPrice"]<=$rsOrder["Back_Amount"]){?><font style="color:#999; text-decoration:line-through">已退款</font><?php }else{?><?php echo $Order_Status[$rsOrder["Order_Status"]] ?><?php }?></td>
            </tr>
            <tr>
              <td nowrap>支付方式：</td>
              <td><?php echo empty($rsOrder["Order_PaymentMethod"]) || $rsOrder["Order_PaymentMethod"]=="0" ? "暂无" : $rsOrder["Order_PaymentMethod"]; ?></td>
            </tr>
            <tr>
              <td nowrap>付款信息：</td>
              <td><?php echo $rsOrder["Order_PaymentInfo"] ?></td>
            </tr>
            <tr>
              <td nowrap>联系人：</td>
              <td><?php echo $rsOrder["Address_Name"] ?></td>
            </tr>
            <tr>
              <td nowrap>手机号码：</td>
              <td><?php echo $rsOrder["Address_Mobile"] ?></td>
            </tr>
            <tr>
              <td nowrap>配送方式：</td>
              <td><?php echo isset($Shipping) && isset($Shipping["Express"])?$Shipping["Express"]:"" ?>&nbsp;&nbsp;快递单号：<?php echo $rsOrder["Order_ShippingID"] ?></td>
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
              <td><?php echo $rsOrder["Order_Remark"] ?></td>
            </tr>
            
          </table>
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
          <?php
		foreach($CartList as $key=>$value){
			foreach($value as $k=>$v){
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
		}
		?>
        <?php
		if(!empty($lists_back)){
			foreach($lists_back as $item){
				$CartList_back=json_decode(htmlspecialchars_decode($item["Back_Json"]),true);
				echo '<tr class="item_list" align="center">
					<td valign="top"><img src="'.$CartList_back["ImgPath"].'" width="100" height="100" /></td>
					<td align="left" class="flh_180">'.$CartList_back["ProductsName"].'<br>';
				foreach($CartList_back["Property"] as $Attr_ID=>$Attr){
					
					echo '<dd>'.$Attr['Name'].': '.$Attr['Value'].'</dd>';
				}
				echo '</td>
					<td>￥'.$CartList_back["ProductsPriceX"].'</td>
					<td>'.$CartList_back["Qty"].'</td>
					<td>￥'.$CartList_back["ProductsPriceX"]*$CartList_back["Qty"].'<br /><font style="text-decoration:line-through; font-size:12px; color:#999">&nbsp;退款金额：￥'.$item["Back_Amount"].'&nbsp;</font><br />'.$_STATUS[$item["Back_Status"]].'</td>
				  </tr>';
			}
		}?>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>