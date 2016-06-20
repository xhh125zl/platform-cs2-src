<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$OrderID=empty($_GET["OrderID"])?"0":$_GET["OrderID"]; 
$rsOrder=$DB->GetRs("user_order","*","where Order_ID='".$OrderID."'");
$Status=$rsOrder["Order_Status"];
$Order_Status=array("待付款","待确认","已付款","已发货","已完成");
$Shipping=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]), true);
$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]), true);
$amount = $fee = 0;
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$rsOrder["Users_ID"]."'");
$company = $rsUsers["Users_Company"];
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>

<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
<style type="text/css">
.detail_card{border:1px solid #ddd; padding:15px;}
.detail_card .order_info{border-collapse:collapse;}
.detail_card .order_info *{font-size:12px;}
.detail_card .order_info td{padding:10px 7px; border-bottom:1px solid #ddd; empty-cells:show;}
.detail_card .order_info td input{height:28px; line-height:28px; border:1px solid #ddd; border-radius:5px;}
.detail_card .order_info td textarea{vertical-align:top; border-radius:5px; width:350px; height:80px; padding:5px; line-height:150%;}
.detail_card .order_info td select{height:32px; border:1px solid #ddd; padding:5px; vertical-align:middle; border-radius:5px;}
.detail_card .cp_item_mod{display:none;}
.detail_card .cp_item_mod td input{border:0; height:30px; line-height:30px;}
.item_info{height:20px; line-height:20px; font-weight:bold;}
.order_item_list{border:1px solid #ddd; margin:5px 0; border-collapse:collapse;}
.order_item_list td{empty-cells:show; font-size:12px;}
.order_item_list .tb_title td{border-right:1px solid #ddd; height:32px; font-weight:bold; text-align:center; background:#f1f1f1;}
.order_item_list .tb_title td.last{border-right:none;}
.order_item_list .item_list td{padding:7px 5px; border-top:1px solid #ddd; background:#fff;}
.order_item_list .item_list td img{width:100px;}
.order_item_list .item_list:hover td{background:#E4F1FC;}
.order_item_list .total td{height:26px; background:#efefef; text-align:center; color:#B50C08; font-weight:bold;}
</style>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
        <li class="cur"><a href="index.php">订单管理</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
		<div class="detail_card">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>订单编号：</td>
              <td width="92%"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
            </tr>
            <tr>
              <td width="8%" nowrap>所属商家：</td>
              <td width="92%"><?php echo $company;?></td>
            </tr>
			<tr>
              <td nowrap>物流费用：</td>
              <td>
              <span class="cp_item_view">
			  <?php if(!isset($Shipping) || empty($Shipping["Price"])){?>
				免运费 
			  <?php }else{
				$fee = $Shipping["Price"];
			  ?>
				 ￥<?php echo $Shipping["Price"];?>
			  <?php }?>
			  </span>
              </td>
            </tr>
            <tr>
              <td nowrap>订单总价：</td>
              <td><span class="cp_item_view">￥<?php echo $rsOrder["Order_TotalPrice"] ?></span></td>
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
              <td><span class="cp_item_view"><?php echo $Order_Status[$Status];?></span></td>
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
              <td><span class="cp_item_view"><?php echo $rsOrder["Address_Name"] ?></span></td>
            </tr>
            <tr>
              <td nowrap>手机号码：</td>
              <td><span class="cp_item_view"><?php echo $rsOrder["Address_Mobile"] ?></span></td>
            </tr>
            <tr>
              <td nowrap>配送方式：</td>
              <td><span class="cp_item_view"><?php echo isset($Shipping) && isset($Shipping["Express"])?$Shipping["Express"]:"" ?></span></td>
            </tr>
            <tr>
              <td nowrap>地址信息：</td>
              <td><span class="cp_item_view"><?php echo $rsOrder["Address_Province"].$rsOrder["Address_City"].$rsOrder["Address_Area"].$rsOrder["Address_Detailed"].'【'.$rsOrder["Address_Name"].'，'.$rsOrder["Address_Mobile"].'】' ?></span></td>
            </tr>
            <tr>
              <td nowrap>订单备注：</td>
              <td><span class="cp_item_view"><?php echo $rsOrder["Order_Remark"] ?></span></td>
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
          <?php $total=0;
$qty=0;
foreach($CartList as $key=>$value){
	foreach($value as $k=>$v){
		$total+=$v["Qty"]*$v["ProductsPriceX"];
		$qty+=$v["Qty"];
		echo '<tr class="item_list" align="center">
            <td valign="top"><img src="'.$v["ImgPath"].'" width="100" height="100" /></td>
            <td align="left" class="flh_180">'.$v["ProductsName"].'<br>';
			if(!empty($v["Property"])){
		foreach($v["Property"] as $m=>$n){
			echo $n["Name"].': '.$n["Value"].'<br>';
		}
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