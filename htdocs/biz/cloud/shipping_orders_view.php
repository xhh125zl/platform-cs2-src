<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/update/common.php');

if (isset($_GET["OrderId"])) {
    $OrderID = $_GET["OrderId"];
} else {
    echo '缺少必要的参数';
    exit();
}
$shipping_orders = $DB->GetRs("shipping_orders", "*", "WHERE Orders_ID=" . $_GET['OrderId'] . "");
$Shipping = json_decode($shipping_orders["Orders_Shipping"], true);

if ($shipping_orders['Detail_ID']) {
    $rsDetail = $DB->GetRs("cloud_products_detail", "*", "WHERE Cloud_Detail_ID=" . $shipping_orders['Detail_ID'] . "");
    $rsProducts = $DB->GetRs("cloud_products", "*", "WHERE Products_ID=" . $rsDetail['Products_ID'] . "");
    $ImgPath = get_prodocut_cover_img($rsProducts);
}
$_STATUS_SHIPPING = array(
    '<font style="color:#FF0000">待付款</font>',
    '<font style="color:#03A84E">待发货</font>',
    '<font style="color:#F60">待收货</font>',
    '<font style="color:blue">已领取</font>',
    '<font style="color:#999; text-decoration:line-through">&nbsp;已取消&nbsp;</font>'
);
$_STATUS = array(
    '',
    '<font style="color:#FF0000">未领取</font>',
    '',
    '<font style="color:blue">已领取</font>'
);
if (is_numeric($shipping_orders['Address_Province'])) {
    $area_json = read_file($_SERVER["DOCUMENT_ROOT"] . '/data/area.js');
    $area_array = json_decode($area_json, TRUE);
    $province_list = $area_array[0];
    $Province = '';
    if (! empty($shipping_orders['Address_Province'])) {
        $Province = $province_list[$shipping_orders['Address_Province']] . ',';
    }
    $City = '';
    if (! empty($shipping_orders['Address_City'])) {
        $City = $area_array['0,' . $shipping_orders['Address_Province']][$shipping_orders['Address_City']] . ',';
    }
    
    $Area = '';
    if (! empty($shipping_orders['Address_Area'])) {
        $Area = $area_array['0,' . $shipping_orders['Address_Province'] . ',' . $shipping_orders['Address_City']][$shipping_orders['Address_Area']];
    }
} else {
    $Province = $shipping_orders['Address_Province'];
    $City = $shipping_orders['Address_City'];
    $Area = $shipping_orders['Address_Area'];
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
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"> <a href="shipping_orders.php">商品领取订单</a></li>
      </ul>
    </div>
    <div id="gift_orders" class="r_con_wrap">
     <form id="orders_mod_form" class="r_con_form">
        <div class="rows">
          <label>商品</label>
          <span class="input"><span class="tips"><?php echo $rsProducts['Products_Name'];?></span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商品图片</label>
          <span class="input"><img src="<?php echo $ImgPath;?>" width="300" /></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>需要物流</label>
          <span class="input"><span class="tips"><?php if($shipping_orders["Orders_IsShipping"]==1){echo '需要';}else{echo '不需要';}?></span></span>
          <div class="clear"></div>
        </div>
        <?php if($shipping_orders['Orders_IsShipping']==1){?>
        <div class="rows">
          <label>配送方式</label>
          <span class="input"><span class="tips" style="color:blue"><?php echo empty($Shipping["Express"]) ? '' : $Shipping["Express"];?><?php if($shipping_orders["Orders_ShippingID"]){echo '&nbsp;&nbsp;&nbsp;&nbsp;物流单号：'.$shipping_orders["Orders_ShippingID"];}?></span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>物流费用</label>
          <span class="input"><span class="tips" style="color:#F00">￥ <?php echo $shipping_orders['Orders_TotalPrice'];?> 元</span></span>
          <div class="clear"></div>
        </div>
        <?php if($shipping_orders["Orders_PaymentMethod"]){?>
        <div class="rows">
          <label>支付方式</label>
          <span class="input"><span class="tips"><?php echo $shipping_orders["Orders_PaymentMethod"];?></span></span>
          <div class="clear"></div>
        </div>
        <?php }?>
        <?php if($shipping_orders["Orders_PaymentInfo"]){?>
        <div class="rows">
          <label>订单备注</label>
          <span class="input"><span class="tips"><?php echo $shipping_orders["Orders_PaymentInfo"];?></span></span>
          <div class="clear"></div>
        </div>
        <?php }?>
        <div class="rows">
          <label>地址信息</label>
          <span class="input"><span class="tips"><?php echo $shipping_orders['Address_Detailed'];?>【<?php echo $Province;?><?php echo $City;?><?php echo $Area;?>】</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>联系方式</label>
          <span class="input"><span class="tips"><?php echo $shipping_orders['Address_Name'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $shipping_orders['Address_Mobile'];?></span></span>
          <div class="clear"></div>
        </div>
        <?php }else{?>
        <div class="rows">
          <label>领取手机</label>
          <span class="input"><span class="tips"><?php echo $shipping_orders['Address_Mobile'];?></span></span>
          <div class="clear"></div>
        </div>
        <?php }?>
        <div class="rows">
          <label>领取时间</label>
          <span class="input"><span class="tips"><?php echo date("Y-m-d H:i:s",$shipping_orders["Orders_CreateTime"]); ?></span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>状态</label>
          <span class="input"><span class="tips">
		  <?php
            if($shipping_orders["Orders_IsShipping"]==0){
				echo $_STATUS[$shipping_orders["Orders_Status"]];
			}else{
				echo $_STATUS_SHIPPING[$shipping_orders["Orders_Status"]];
			}
		 ?>
          </span></span>
          <div class="clear"></div>
        </div>
        <?php if($shipping_orders["Orders_Status"]==3){?>
        <div class="rows">
          <label>领取时间</label>
          <span class="input"><span class="tips">
		  <?php
            echo date("Y-m-d H:i:s",$shipping_orders["Orders_FinishTime"]);
		 ?>
          </span></span>
          <div class="clear"></div>
        </div>
        <?php }?>
      </form>
    </div>
  </div>
</div>
</body>
</html>