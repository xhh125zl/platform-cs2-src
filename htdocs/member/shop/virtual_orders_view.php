<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID='".$OrderID."'");
if($_POST){
	$Data=array(
		"Order_Status"=>1
	);
	$Flag=$DB->Set("user_order",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID=".$OrderID);
	if($Flag){
		echo '<script language="javascript">alert("确认订单成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else{
		echo '<script language="javascript">alert("确认订单成功");history.back();</script>';
	}
}

$rsConfig=$DB->GetRs("shop_config","ShopName,NeedShipping","where Users_ID='".$_SESSION["Users_ID"]."'");

$Status=$rsOrder["Order_Status"];
$Order_Status=array("待确认","待付款","已付款","已发货","已完成", "申请退款中");
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
    <div id="orders" class="r_con_wrap">
      <div class="control_btn">
      <a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a>
      </div>     
      <div class="detail_card">
        <form method="post" action="?">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>订单编号：</td>
              <td width="92%"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
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
            <?php
            print_r($Order_Status);
            ?>
              <td nowrap>订单状态：</td>
              <td><?php if($rsOrder["Order_TotalPrice"]<=$rsOrder["Back_Amount"]){?><font style="color:#999; text-decoration:line-through">已退款</font><?php }else{?>
              	<?php
echo $rsOrder["Order_Status"];

              	 echo $Order_Status[$rsOrder["Order_Status"]] ?><?php }?></td>
            </tr>
            <tr>
              <td nowrap>支付方式：</td>
              <td><?php echo empty($rsOrder["Order_PaymentMethod"]) || $rsOrder["Order_PaymentMethod"]=="0" ? "暂无" : $rsOrder["Order_PaymentMethod"]; ?></td>
            </tr>
            <tr>
              <td nowrap>手机号码：</td>
              <td><?php echo $rsOrder["Address_Mobile"] ?></td>
            </tr>
            
            <tr>
              <td nowrap>订单备注：</td>
              <td><?php echo $rsOrder["Order_Remark"] ?></td>
            </tr>
            <?php if($rsOrder["Order_Status"]==0){?>
            <tr class="cp_item_mod">
              <td></td>
              <td><input type="submit" class="btn_green" name="submit_button" value="确认订单" /></td>
            </tr>
            <?php }?>
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