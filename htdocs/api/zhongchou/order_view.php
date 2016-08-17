<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$is_login = 1;
require_once(CMS_ROOT.'/include/library/wechatuser.php');


$OrderID=$_GET['OrderID'];
$rsConfig=$DB->GetRs("shop_config","ShopName,NeedShipping","where Users_ID='".$UsersID."'");
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
$Status=$rsOrder["Order_Status"];
$Order_Status=array("待付款","待确认","已付款","已完成");
$Shipping=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]),true);
$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
$amount = $fee = 0;
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["ShopName"] ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">$(document).ready(shop_obj.page_init);</script>
</head>

<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/member.css' rel='stylesheet' type='text/css' />
  <ul id="member_nav">
    <li class="<?php echo $Status==0?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/0/">待付款</a></li>
    <li class="<?php echo $Status==1?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/1/">待确认</a></li>
    <li class="<?php echo $Status==2?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/2/">已付款</a></li>
    <li class="<?php echo $Status==3?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/3/">已完成</a></li>
    <?php if(!empty($rsConfig["NeedShipping"])){?>
    <li class=""><a href="/api/<?php echo $UsersID ?>/shop/member/address/">地址簿</a></li>
    <?php }?>
  </ul>
  <div id="order_detail">
    <div class="item">
      <ul>
        <li>订单编号：<?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></li>
        <li>订单时间: <?php echo date("Y-m-d H:i:s",$rsOrder["Order_CreateTime"]) ?></li>
        <li>订单状态: <?php echo $Order_Status[$rsOrder["Order_Status"]] ?></li>
        <li>订单总价: <strong class="fc_red">￥<?php echo $rsOrder["Order_TotalPrice"] ?></strong></li>
		<?php if($rsOrder["Coupon_ID"]>0){?>
		<li>优惠详情: <font style="color:blue;">已使用优惠券</font>(
		<?php if($rsOrder["Coupon_Discount"]>0){?>
		享受<?php echo $rsOrder["Coupon_Discount"]*10;?>折
		<?php }?>
		<?php if($rsOrder["Coupon_Cash"]>0){?>
		抵现金<?php echo $rsOrder["Coupon_Cash"];?>元
		<?php }?>
		)
		</li>
		<?php }?>
        <li>订单备注: <?php echo $rsOrder["Order_Remark"] ?></li>
      </ul>
    </div>
    <div class="item">
      <ul>
        <li>收货地址: <?php echo $rsOrder["Address_Province"].$rsOrder["Address_City"].$rsOrder["Address_Area"].$rsOrder["Address_Detailed"].'【'.$rsOrder["Address_Name"].'，'.$rsOrder["Address_Mobile"].'】' ?></li>
        <li>配送方式: <?php echo empty($Shipping)?"":$Shipping["Express"] ?><strong class="fc_red">
		<?php if(empty($Shipping["Price"])){?>
			免运费 
		<?php }else{
			$fee = $Shipping["Price"];
		?>
			 ￥<?php echo $Shipping["Price"];?>
		<?php }?>
		
		</strong></li>
        <?php echo empty($rsOrder["Order_ShippingID"])?"":"<li>快递单号:".$rsOrder["Order_ShippingID"]."</li> " ?>
      </ul>
    </div>
<?php if(!empty($rsOrder["Order_PaymentMethod"])){ ?>
    <div class="item">
      <ul>
        <li>支付方式: <?php echo $rsOrder["Order_PaymentMethod"] ?></li>
        <?php if($rsOrder["Order_PaymentMethod"]=="线下支付"){ ?><li>支付信息: <?php echo $rsOrder["Order_PaymentInfo"] ?><a href="/api/<?php echo $UsersID ?>/shop/cart/payment/<?php echo $rsOrder["Order_ID"] ?>/" class="red"><strong>修改支付信息</strong></a></li><?php }?>
      </ul>
    </div>
<?php }?>
    <div class="item">
<?php
foreach($CartList as $key=>$value){
	foreach($value as $k=>$v){
		$amount = $amount + $v["ProductsPriceX"]*$v["Qty"];
		echo '<div class="pro">
			<div class="img"><a href="/api/'.$UsersID.'/shop/products/'.$key.'/"><img src="'.$v["ImgPath"].'" width="100" height="100"></a></div>
			<dl class="info">
				<dd class="name"><a href="/api/'.$UsersID.'/shop/products/'.$key.'/">'.$v["ProductsName"].'</a></dd>
				<dd>价格:￥'.$v["ProductsPriceX"].'×'.$v["Qty"].'=￥'.$v["ProductsPriceX"]*$v["Qty"].'</dd>';
		foreach($v["Property"] as $m=>$n){
			echo '<dd>'.$m.': '.$n.'</dd>';
		}
        echo '</dl>
			<div class="clear"></div>
		</div>';
		}
	}?>
	<?php if($rsOrder["Coupon_ID"]>0){?>
		<?php if($rsOrder["Coupon_Discount"]>0){?>
		<div class="total_price">产品总价:<span>￥<?php echo $amount;?> × <?php echo $rsOrder["Coupon_Discount"];?> + ￥<?php echo $fee;?> = ￥<?php echo $rsOrder["Order_TotalPrice"] ?></span></div>		
		<?php }?>
		<?php if($rsOrder["Coupon_Cash"]>0){?>
		<div class="total_price">产品总价:<span>￥<?php echo $amount;?> - ￥<?php echo $rsOrder["Coupon_Cash"];?> + ￥<?php echo $fee;?> = ￥<?php echo $rsOrder["Order_TotalPrice"] ?></span></div>
		<?php }?>
	<?php }else{?>
		<div class="total_price">产品总价:<span>￥<?php echo $amount;?> + ￥<?php echo $fee;?> = ￥<?php echo $rsOrder["Order_TotalPrice"] ?></span></div>
	<?php }?>
    </div>
    <?php if($rsOrder["Order_Status"]==0){ ?><div class="payment"><a href="/api/<?php echo $UsersID ?>/shop/cart/payment/<?php echo $rsOrder["Order_ID"] ?>/">付款</a></div><?php }?>
	<?php if($rsOrder["Order_Status"]==3 && $rsOrder["Is_Commit"]==0){ ?><div class="payment"><a href="/api/<?php echo $UsersID ?>/shop/member/commit/<?php echo $rsOrder["Order_ID"] ?>/">评论</a></div><?php }?>
  </div>
</div>
<div id="footer_points"></div>
<footer id="footer">
  <ul>
    <li class="category"><a href="#">产品分类</a></li>
    <li class="cart"><a href="/api/<?php echo $UsersID ?>/shop/cart/">购物车</a></li>
    <li class="member"><a href="/api/<?php echo $UsersID ?>/shop/member/">会员中心</a></li>
    <li class="home"><a href="/api/<?php echo $UsersID ?>/shop/">商城首页</a></li>
  </ul>
</footer>
<div id="category">
  <div class="close"></div>
  <dl>
    <?php
		$DB->get("shop_category","Category_Name,Category_ID","where Users_ID='".$UsersID."' and Category_ParentID=0 order by Category_Index asc");
		$ParentCategory=array();
		$i=1;
		while($rsPCategory=$DB->fetch_assoc()){
			$ParentCategory[$i]=$rsPCategory;
			$i++;
		}
		foreach($ParentCategory as $key=>$value){
			echo '<dt><a href="/api/'.$UsersID.'/shop/category/'.$value["Category_ID"].'/?OpenID='.$_SESSION[$UsersID."OpenID"].'">'.$value["Category_Name"].'</a></dt>';
			$DB->get("shop_category","Category_Name,Category_ID","where Users_ID='".$UsersID."' and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc");
			while($rsCategory=$DB->fetch_assoc()){
				echo '<dd><a href="/api/'.$UsersID.'/shop/category/'.$rsCategory["Category_ID"].'/?OpenID='.$_SESSION[$UsersID."OpenID"].'">&gt; '.$rsCategory["Category_Name"].'</a></dd>';
			}
		}
	?>
  </dl>
</div>
<?php
$KfIco = '';
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1");
$KfIco = empty($kfConfig["KF_Icon"]) ? '' : $kfConfig["KF_Icon"];
?>
<?php if(!empty($kfConfig)){?>
<script language='javascript'>var KfIco='<?php echo $KfIco;?>'; var OpenId='<?php echo $_SESSION[$UsersID."OpenID"];?>'; var UsersID='<?php echo $UsersID;?>'; </script>
<script type='text/javascript' src='/kf/js/webchat.js?t=<?php echo time();?>'></script>
<?php }?>
</body>
</html>