<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

if(empty($_SESSION[$UsersID."User_ID"]))
{
	header("location:/api/".$UsersID."/user/login/");
}

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

/*分享页面初始化配置*/
$share_flag = 0;
$signature = '';

//自动授权
$is_login = 1;
$owner = get_owner($rsConfig,$UsersID);
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$owner = get_owner($rsConfig,$UsersID);


$OrderID=$_GET['OrderID'];

$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
$Status=$rsOrder["Order_Status"];
$Order_Status=array("待付款","待确认","已付款","已发货","已完成");

$Shipping=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]),true);
$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
$amount = $fee = 0;


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
<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/cloud/js/shop.js'></script>
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
	<h2 style="background-color: white; border-bottom: 1px solid #ddd;height: 44px;line-height:44px;color: #000;font-size: 17px;font-weight: 500;text-align: center;">订单详情</h2>
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
					) </li>
				<?php }?>
				<li>订单备注: <?php echo $rsOrder["Order_Remark"] ?></li>
			</ul>
		</div>
		<?php if($rsOrder["Order_IsRecieve"]==0){?>
		<div class="item">
			<ul>
				<?php if($rsOrder["Order_IsVirtual"]==0){?>
				<li>收货地址: <?php echo $Province.$City.$Area.'【'.$rsOrder["Address_Name"].'，'.$rsOrder["Address_Mobile"].'】' ?></li>
				<li>配送方式:
					<?php
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
					?>
					<strong class="fc_red">
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
				<li>消费券码： <font style="color:#088704; font-size:16px; font-weight:bold; font-family:'Times New Roman'"><?php echo $rsOrder["Order_Code"];?></font>。
					<?php if(strpos($rsOrder["Order_Code"],"登录名") !== false){?>
					登陆地址: http://<?php echo $_SERVER["HTTP_HOST"];?>/member/login.php
					<?php }?>
				</li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
		<?php if(!empty($rsOrder["Order_PaymentMethod"])){ ?>
		<div class="item">
			<ul>
				<li>支付方式: <?php echo $rsOrder["Order_PaymentMethod"] ?></li>
				<?php if($rsOrder["Order_PaymentMethod"]=="线下支付"){ ?>
				<li>支付信息: <?php echo $rsOrder["Order_PaymentInfo"] ?><a href="/api/<?php echo $UsersID ?>/cloud/cart/payment/<?php echo $rsOrder["Order_ID"] ?>/" class="red"><strong>修改支付信息</strong></a></li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
		<div class="item">
			<?php
			foreach($CartList as $key=>$value){
				foreach($value as $k=>$v){
					$amount = $amount + $v["ProductsPriceX"]*$v["Qty"];
					echo '<div class="pro">
						<div class="img"><a href="/api/'.$UsersID.'/cloud/products/'.$key.'/"><img src="'.$v["ImgPath"].'" width="100" height="100"></a></div>
						<dl class="info">
							<dd class="name"><a href="/api/'.$UsersID.'/cloud/products/'.$key.'/">'.$v["ProductsName"].'</a></dd>
							<dd>价格:￥'.$v["ProductsPriceX"].'×'.$v["Qty"].'=￥'.$v["ProductsPriceX"]*$v["Qty"].'</dd>';
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
		<?php if($rsOrder["Order_Status"]==0){ ?>
		<div class="payment"><a href="/api/<?php echo $UsersID ?>/cloud/cart/payment/<?php echo $rsOrder["Order_ID"] ?>/">付款</a></div>
		<div class="payment"><a href="/api/cloud/member/orders.php?UsersID=<?=$UsersID?>&action=delete&OrderID=<?=$rsOrder["Order_ID"]?>">取消</a></div>
		<?php }?>
		<?php if($rsOrder["Order_Status"]==3 && $rsOrder["Is_Commit"]==0){ ?>
		<div class="backup"><a href="/api/<?php echo $UsersID ?>/cloud/member/backup/<?php echo $rsOrder["Order_ID"] ?>/">去退货</a></div>
		<div class="payment"><a id="confirm_receive" href="javascript:void(0)"  Order_ID="<?=$rsOrder['Order_ID']?>">确认收货</a></div>
		<?php }?>
		<?php if($rsOrder["Order_Status"]==4 && $rsOrder["Is_Commit"]==0){ ?>
		<div class="payment" style="display:none;"><a href="/api/<?php echo $UsersID ?>/cloud/member/commit/<?php echo $rsOrder["Order_ID"] ?>/">评论</a></div>
		<?php }?>
	</div>
</div>
<?php require_once('../footer.php');?>
</body>
</html>