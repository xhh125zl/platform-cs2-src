<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

$OrderID=$_GET['OrderID'];

$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
$Status=$rsOrder["Order_Status"];
$Order_Status=array("待确认","待付款","已付款","已发货","已完成");

$Shipping=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]),true);
$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
$amount = $fee = 0;
// echo '<pre>';
// print_r($Shipping);
// exit;
$fee = empty($Shipping["Price"]) ? 0 : $Shipping["Price"];//var_dump($rsOrder["Order_TotalAmount"]);exit;
//$amount = $rsOrder["Order_TotalAmount"] - $fee;
$amount = $rsOrder["Order_TotalAmount"];

$lists_back = array();
if($rsOrder["Is_Backup"]==1){
	$condition = "where Users_ID='".$UsersID."' and Order_ID=".$rsOrder["Order_ID"]." and Back_Type='shop'";
	$DB->Get("user_back_order","*",$condition);
	while($b=$DB->fetch_assoc()){
		$lists_back[] = $b;
	}
}
$_STATUS = array('<font style="color:#F00; font-size:12px;">申请中</font>','<font style="color:#F60; font-size:12px;">卖家同意</font>','<font style="color:#0F3; font-size:12px;">买家发货</font>','<font style="color:#600; font-size:12px;">卖家收货并确定退款价格</font>','<font style="color:blue; font-size:12px;">完成</font>','<font style="color:#999; font-size:12px; text-decoration:line-through;">卖家拒绝退款</font>');


//收货地址
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

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单详情 - 个人中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">
var base_url = '<?php echo $base_url;?>';
var UsersID = '<?php echo $UsersID;?>';

$(document).ready(shop_obj.page_init);
</script>
</head>

<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/member.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
  <ul id="member_nav">
    <li class="<?php echo $Status==0?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/0/">待确认</a></li>
    <li class="<?php echo $Status==1?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/1/">待付款</a></li>
    <li class="<?php echo $Status==2?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/2/">已付款</a></li>
    <li class="<?php echo $Status==3?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/3/">已发货</a></li>
    <li class="<?php echo $Status==4?"cur":"" ?>"><a href="/api/<?php echo $UsersID ?>/shop/member/status/4/">已完成</a></li>
   
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
	<?php if($rsOrder["Order_IsRecieve"]==0){?>
    <div class="item">
      <ul>
        <?php if($rsOrder["Order_IsVirtual"]==0){?>
		<?php if($rsOrder["Order_Status"]==3 && !empty($rsConfig['Confirm_Time'])){?>
		<li class="red">注:商家设置了自动收货时间<?php echo ($rsConfig['Confirm_Time']/86400);?>天，该订单还有<?php echo number_format(($rsConfig['Confirm_Time']-time()+$rsOrder["Order_SendTime"])/86400,0,'.','')?>天自动收货</li>
		<?php }?>
        <li>收货地址: <?php echo $Province.$City.$Area.'【'.$rsOrder["Address_Name"].'，'.$rsOrder["Address_Mobile"].'】' ?></li>
        <li>配送方式: <?php
		
		if(empty($Shipping)){
				echo "暂无信息";
			}else{
				if(empty($Shipping["Express"])){
					echo "暂无信息";
				}else{
					echo $Shipping["Express"];
				}
			}
		//echo empty($Shipping)?"":$Shipping["Express"]
		?><strong class="fc_red">
		<?php if(empty($Shipping["Price"])){?>
			免运费 
		<?php }else{
			$fee = $Shipping["Price"];
		?>
			 ￥<?php echo $Shipping["Price"];?>
		<?php }?>
		
		</strong></li>
        <?php echo empty($rsOrder["Order_ShippingID"])?"":"<li>快递单号:".$rsOrder["Order_ShippingID"]."</li> " ?>
        <?php }else{?>
        <li>购买手机： <?php echo $rsOrder["Address_Mobile"];?></li>
        <li>消费券码： <font style="color:#088704; font-size:16px; font-weight:bold; font-family:'Times New Roman'"><?php echo $rsOrder["Order_Code"];?></font>。<?php if(strpos($rsOrder["Order_Code"],"登录名") !== false){?>登陆地址: http://<?php echo $_SERVER["HTTP_HOST"];?>/member/login.php<?php }?></li>
        <?php }?>
      </ul>
    </div>
	<?php }?>
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
		echo '<div class="pro" style="border-bottom:1px #dfdfdf dotted; padding-bottom:8px">
			<div class="img"><a href="/api/'.$UsersID.'/shop/products/'.$key.'/"><img src="'.$v["ImgPath"].'" width="70" height="70"></a></div>
			<dl class="info" style="padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:0px">
				<dd class="name" style="padding:0px; margin:0px"><a href="/api/'.$UsersID.'/shop/products/'.$key.'/">'.$v["ProductsName"].'</a></dd>
				<dd style="padding:0px; margin:0px">价格:￥'.$v["ProductsPriceX"].'×'.$v["Qty"].'=￥'.$v["ProductsPriceX"]*$v["Qty"].'</dd>';
		foreach($v["Property"] as $Attr_ID=>$Attr){
			echo '<dd style="padding:0px; margin:0px">'.$Attr['Name'].': '.$Attr['Value'].'</dd>';
		}
        echo '</dl>';
		if($rsOrder['Order_Status'] == 2 || $rsOrder['Order_Status'] == 3){
			echo '<a href="'.$base_url.'api/'.$UsersID.'/shop/member/backup/'.$rsOrder["Order_ID"].'_'.$key.'_'.$k.'/" style="display:block; width:100px; height:30px; line-height:28px; color:#FFF; background:#F60; border-radius:8px; text-align:center; float:right; font-size:12px;">申请退款</a>';
		}
		echo '<div class="clear"></div>
		</div>';
	}
}
if(!empty($lists_back)){
	foreach($lists_back as $item){
		$CartList_back=json_decode(htmlspecialchars_decode($item["Back_Json"]),true);
		echo '<div class="pro" style="border-bottom:1px #dfdfdf dotted; padding-bottom:3px">
			<div class="img"><a href="/api/'.$UsersID.'/shop/products/'.$item["ProductID"].'/"><img src="'.$CartList_back["ImgPath"].'" width="70" height="70"></a></div>
			<dl class="info" style="padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:0px">
				<dd class="name" style="padding:0px; margin:0px"><a href="/api/'.$UsersID.'/shop/products/'.$item["ProductID"].'/">'.$CartList_back["ProductsName"].'</a></dd>
				<dd style="padding:0px; margin:0px">价格:￥'.$CartList_back["ProductsPriceX"].'×'.$CartList_back["Qty"].'=￥'.$CartList_back["ProductsPriceX"]*$CartList_back["Qty"].'</dd>';
		foreach($CartList_back["Property"] as $Attr_ID=>$Attr){
			echo '<dd style="padding:0px; margin:0px;">'.$Attr['Name'].': '.$Attr['Value'].'</dd>';
		}
        echo '</dl>';
		echo '<div class="clear"></div>';
		echo '<p style="margin:0px; padding:0px; width:100%; display:block; text-align:right; color:#ff0000">'.$_STATUS[$item["Back_Status"]].'&nbsp;&nbsp;<a href="'.$base_url.'api/'.$UsersID.'/shop/member/backup/detail/'.$item["Back_ID"].'/" style="color:#FFF; background:#696969; border-radius:8px; text-align:center; padding:5px 10px; font-size:12px;">退款详情</a><div class="clear"></div></p>';
		echo '</div>';
	}
}
?>
	<?php if($rsOrder["Coupon_ID"]>0){?>
		<div class="total_price">订单总价:<span>￥<?php echo $amount;?> - ￥<?php echo $rsOrder["Coupon_Cash"];?> + ￥<?php echo $fee;?> = ￥<?php echo $rsOrder["Order_TotalPrice"] ?></span></div>
	<?php }else{?>
		<div class="total_price">订单总价:<span>￥<?php echo $amount;?> + ￥<?php echo $fee;?> = ￥<?php echo $rsOrder["Order_TotalPrice"] ?></span></div>
	<?php }?>
    </div>
    <?php if($rsOrder["Order_Status"]==0){ ?>
    <div class="payment"><a href="/api/<?php echo $UsersID ?>/shop/cart/payment/<?php echo $rsOrder["Order_ID"] ?>/">付款</a></div>
	<div class="payment"><a href="/api/shop/member/orders.php?UsersID=<?=$UsersID?>&action=delete&OrderID=<?=$rsOrder["Order_ID"]?>">取消</a></div>
	<?php }?>
    
    <?php if($rsOrder["Order_Status"]==3 && !empty($CartList)){ ?>
    <div class="payment"><a class="confirmreceive" href="javascript:void(0)"  Order_ID="<?=$rsOrder['Order_ID']?>">确认收货</a></div>
	<?php }?>
	
	<?php if($rsOrder["Order_Status"]==4 && $rsOrder["Is_Commit"]==0){ ?>
    <div class="payment"><a href="/api/<?php echo $UsersID ?>/shop/member/commit/<?php echo $rsOrder["Order_ID"] ?>/">评论</a></div>
	<?php }?>
   

   

  </div>
</div>
<?php
 	require_once('../skin/distribute_footer.php');
 ?>
</body>
</html>