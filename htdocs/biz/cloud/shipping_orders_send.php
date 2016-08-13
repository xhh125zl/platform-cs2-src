<?php
if (isset($_GET["OrderId"])) {
    $OrderID = $_GET["OrderId"];
} else {
    echo '缺少必要的参数';
    exit();
}
$shipping_orders = $DB->GetRs("shipping_orders", "*", "where Orders_ID=" . $_GET['OrderId'] . "");
if ($shipping_orders["Orders_IsShipping"] == 0) {
    echo '此订单不需要物流';
    exit();
}
$Shipping = json_decode($shipping_orders["Orders_Shipping"], true);

if ($_POST) {
    $Data = array(
        "Orders_ShippingID" => $_POST["ShippingID"],
        "Address_Province" => $_POST["Province"],
        "Address_City" => $_POST["City"],
        "Address_Area" => $_POST["Area"],
        "Address_Detailed" => $_POST["Detailed"],
        "Orders_Status" => 2,
        "Orders_SendTime" => time()
    );
    if (! empty($_POST["Shipping"]["Express"])) {
        $ShippingN = array(
            "Express" => $_POST["Shipping"]["Express"],
            "Price" => empty($Shipping["Price"]) ? 0 : $Shipping["Price"]
        );
        $Data["Orders_Shipping"] = json_encode($ShippingN, JSON_UNESCAPED_UNICODE);
    }
    $Flag = $DB->Set("shipping_orders", $Data, "WHERE Users_ID='{$UsersID}' AND Orders_ID=" . $OrderID);
    if ($Flag) {
        $url = 'http://' . $_SERVER["HTTP_HOST"] . "/api/" . $UsersID . "/cloud/member/products/";
        require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/weixin_message.class.php');
        $weixin_message = new weixin_message($DB, $UsersID, $shipping_orders["User_ID"]);
        $contentStr = '您领取的商品已发货，<a href="' . $url . '">查看详情</a>';
        $weixin_message->sendscorenotice($contentStr);
        echo '<script language="javascript">alert("操作成功");window.location="shipping_orders.php";</script>';
    } else {
        echo '<script language="javascript">alert("操作失败");history.back();</script>';
    }
} else {
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
    
    $rsConfig = $DB->GetRs("shop_config", "*", "WHERE Users_ID='{$UsersID}'");
    $rsPay = $DB->GetRs("users_payconfig", "Shipping", "WHERE Users_ID='{$UsersID}'");
    $rsBiz = $DB->GetRs("biz", "Shipping,Biz_ID", "WHERE Users_ID = '{$UsersID}' AND Biz_ID={$BizID}");
    $PayShipping = get_front_shiping_company_dropdown($UsersID, $rsBiz);
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
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
	<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
	<style type="text/css">
body, html {
	background: url(/static/member/images/main/main-bg.jpg) left top fixed
		no-repeat;
}
</style>
	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/user.css' rel='stylesheet'
				type='text/css' />
			<script type='text/javascript' src='/static/member/js/user.js'></script>
			<div class="r_nav">
				<ul>
					<li class="cur"><a href="shipping_orders.php">商品领取订单</a></li>
				</ul>
			</div>
			<div id="gift_orders" class="r_con_wrap">
				<form id="orders_mod_form" method="post"
					action="?OrderId=<?php echo $OrderID;?>" class="r_con_form">
					<div class="rows">
						<label>商品名称</label> <span class="input"><span class="tips"><?php echo $rsProducts['Products_Name'];?></span></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>商品图片</label> <span class="input"><img
							src="<?php echo $ImgPath;?>" width="300" /></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>配送方式</label> <span class="input"> <select
							name="Shipping[Express]">
                  <?php foreach($PayShipping as $key=>$value){?>
                  	<?php if(!empty($Shipping["Express"])):?>
                  		<option value="<?=$value?>"
									<?=$value == $Shipping["Express"]?'selected':''?>><?php echo $value ?></option>
                    <?php else:?>
                    	<option value="<?=$value?>"><?php echo $value ?></option>
				  	<?php endif;?> 
				  <?php }?>
            </select> &nbsp;&nbsp;快递单号： <input name="ShippingID"
							value=""
							style="height: 28px; border: 1px #dfdfdf solid; padding: 0px 5px" />
						</span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>地址信息</label> <span class="input"> <select name="Province"
							id="loc_province" style="width: 120px">
						</select> <select name="City" id="loc_city" style="width: 120px">
						</select> <select name="Area" id="loc_town" style="width: 120px">
						</select> <input type="text" name="Detailed"
							value="<?php echo $shipping_orders["Address_Detailed"] ?>"
							size="35"
							style="height: 28px; border: 1px #dfdfdf solid; padding: 0px 5px" />
							<div class="blank9"></div>
				<?php if(!is_numeric($shipping_orders["Address_Province"])){?>
				<script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script>
							<script language="javascript">new PCAS('Province', 'City', 'Area', '<?php echo $shipping_orders["Address_Province"] ?>', '<?php echo $shipping_orders["Address_City"] ?>', '<?php echo $shipping_orders["Address_Area"] ?>');</script>
				<?php }else{?>
				<script type='text/javascript' src="/static/js/select2.js"></script>
							<script type="text/javascript" src="/static/js/location.js"></script>
							<script type="text/javascript" src="/static/js/area.js"></script>
							<link href="/static/css/select2.css" rel="stylesheet" /> <script
								type="text/javascript">
				$(document).ready(function(){
					showLocation(<?php echo $shipping_orders["Address_Province"];?>,<?php echo $shipping_orders["Address_City"];?>,<?php echo $shipping_orders["Address_Area"];?>);
				});
				</script>
				<?php }?>
          </span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>联系方式</label> <span class="input"><span class="tips"><?php echo $shipping_orders['Address_Name'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $shipping_orders['Address_Mobile'];?></span></span>
						<div class="clear"></div>
					</div>

					<div class="rows">
						<label>领取时间</label> <span class="input"><span class="tips"><?php echo date("Y-m-d H:i:s",$shipping_orders["Orders_CreateTime"]); ?></span></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>状态</label> <span class="input"><span class="tips">
		  <?php
    if ($shipping_orders["Orders_IsShipping"] == 0) {
        echo $_STATUS[$shipping_orders["Orders_Status"]];
    } else {
        echo $_STATUS_SHIPPING[$shipping_orders["Orders_Status"]];
    }
    ?>
          </span></span>
						<div class="clear"></div>
					</div>

					<div class="rows">
						<label></label> <span class="input"> <input type="submit"
							class="btn_green" name="submit_button" value="确认发货" />
						</span>
						<div class="clear"></div>
					</div>

				</form>
			</div>
		</div>
	</div>
</body>
</html>