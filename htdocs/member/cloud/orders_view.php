<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');

ini_set("display_errors","On");

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID='".$OrderID."'");
$Shipping=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]),true);
if($_POST){
	$Data=array(
		"Order_TotalPrice"=>$_POST['TotalPrice'],
		"Address_Name"=>$_POST['Name'],
		"Address_Mobile"=>$_POST["Mobile"],
		"Order_ShippingID"=>$_POST["ShippingID"],
		"Address_Province"=>$_POST["Province"],
		"Address_City"=>$_POST["City"],
		"Address_Area"=>$_POST["Area"],
		"Address_Detailed"=>$_POST["Detailed"],
		"Order_Remark"=>$_POST["Remark"],
		"Order_Status"=>$_POST["Status"]
	);
	if(!empty($_POST["Shipping"]["Express"])){
		$ShippingN = array(
			"Express"=>$_POST["Shipping"]["Express"],
			"Price"=>empty($Shipping["Price"]) ? 0 : $Shipping["Price"]
		);
		$Data["Order_Shipping"] = json_encode($ShippingN,JSON_UNESCAPED_UNICODE);
	}
	$Flag=$DB->Set("user_order",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID=".$OrderID);
	if($Flag){		
		if($rsOrder['Order_Status']<3 && $Data['Order_Status']>=3){
			$rsConfig=$DB->GetRs("shop_config","CheckOrder","where Users_ID='".$Flag['Users_ID']."'");
			$url='http://'.$_SERVER["HTTP_HOST"]."/api/".$rsOrder['Users_ID']."/cloud/member/detail/".$rsOrder['Order_ID']."/";
			require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
			$weixin_message = new weixin_message($DB,$_SESSION["Users_ID"],$rsOrder["User_ID"]);
			$contentStr = '您购买的商品已发货，<a href="'.$url.'">查看详情</a>';
			$weixin_message->sendscorenotice($contentStr);
		}
		echo '<script language="javascript">alert("修改成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
}else{
	$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	$rsPay=$DB->GetRs("users_payconfig","Shipping","where Users_ID='".$_SESSION["Users_ID"]."'");

	$Status=$rsOrder["Order_Status"];
	$Order_Status=array("待确认","待付款","已付款","已发货","已完成");
	
	$PayShipping = get_front_shiping_company_dropdown($_SESSION["Users_ID"],$rsConfig);
	$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
	$amount = $fee = 0;
	if(is_numeric($rsOrder['Address_Province'])){
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
	}else{
		$Province = $rsOrder['Address_Province'];
		$City = $rsOrder['Address_City'];
		$Area = $rsOrder['Address_Area'];
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
        <li class="cur"><a href="orders.php">订单管理</a></li>
        <li><a href="virtual_orders.php">消费认证</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">
var NeedShipping=1;
var orders_status=["待确认","待付款","已付款","已发货","已完成"];
$(document).ready(shop_obj.orders_init);
</script>
    <div id="orders" class="r_con_wrap">
      <div class="control_btn">
      <a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a>
      <a href="order_print.php?OrderID=<?=$rsOrder["Order_ID"]?>" target="blank" class="btn_gray" id="order_print">打印订单</a>
      </div>
      
      <div class="cp_title">
        <div id="cp_view" class="cur">订单详情</div>
        <div id="cp_mod">修改订单</div>
      </div>
      <div class="detail_card">
        <form id="orders_mod_form" method="post" action="orders_view.php">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>订单编号：</td>
              <td width="92%"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></td>
            </tr>
			<tr>
              <td nowrap>物流费用：</td>
              <td><span class="cp_item_view">
			  <?php if(!isset($Shipping) || empty($Shipping["Price"])){?>
				免运费 
			  <?php }else{
				$fee = $Shipping["Price"];
			  ?>
				 ￥<?php echo $Shipping["Price"];?>
			  <?php }?>
			  </span> <span class="cp_item_mod">￥
                <input name="Shipping[Price]" value="<?php echo isset($Shipping) && isset($Shipping["Price"])?$Shipping["Price"]:0 ?>" size="5" />
                </span></td>
            </tr>
            <tr>
              <td nowrap>订单总价：</td>
              <td><span class="cp_item_view">￥<?php echo $rsOrder["Order_TotalPrice"] ?></span> <span class="cp_item_mod">￥<input name="TotalPrice" value="<?php echo $rsOrder["Order_TotalPrice"] ?>" size="5" /></span></td>
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
              <td><span class="cp_item_view"><?php echo $Order_Status[$Status];?></span> <span class="cp_item_mod">
                <select name="Status">
                  <option value='0'<?php echo $rsOrder["Order_Status"]==0?" selected":"" ?>>待确认</option>
                  <option value='1'<?php echo $rsOrder["Order_Status"]==1?" selected":"" ?>>待付款</option>
                  <option value='2'<?php echo $rsOrder["Order_Status"]==2?" selected":"" ?>>已付款</option>
                  <option value='3'<?php echo $rsOrder["Order_Status"]==3?" selected":"" ?>>已发货</option>
                  <option value='3'<?php echo $rsOrder["Order_Status"]==4?" selected":"" ?>>已完成</option>
                </select>
                </span></td>
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
              <td><span class="cp_item_view"><?php echo $rsOrder["Address_Name"] ?></span> <span class="cp_item_mod">
                <input name="Name" value="<?php echo $rsOrder["Address_Name"] ?>" size="10" />
                </span></td>
            </tr>
            <tr>
              <td nowrap>手机号码：</td>
              <td><span class="cp_item_view"><?php echo $rsOrder["Address_Mobile"] ?></span> <span class="cp_item_mod">
                <input name="Mobile" value="<?php echo $rsOrder["Address_Mobile"] ?>" size="15" />
                </span></td>
            </tr>
            <tr>
              <td nowrap>配送方式：</td>
              <td><span class="cp_item_view"><?php echo isset($Shipping) && isset($Shipping["Express"])?$Shipping["Express"]:"" ?></span> <span class="cp_item_mod">
                <select name="Shipping[Express]">
                  <?php foreach($PayShipping as $key=>$value){?>
                  	<?php if(!empty($Shipping["Express"])):?>
                  		<option value="<?=$value?>" <?=$value == $Shipping["Express"]?'selected':''?>><?php echo $value ?></option>
                    <?php else:?>
                    	<option value="<?=$value?>"><?php echo $value ?></option>
				  	<?php endif;?> 
				  <?php }?>
                </select>
                &nbsp;&nbsp;快递单号：
                <input name="ShippingID" value="<?php echo $rsOrder["Order_ShippingID"] ?>" />
                </span></td>
            </tr>
            <tr>
              <td nowrap>地址信息：</td>
              <td><span class="cp_item_view"><?php echo $Province.$City.$Area.','.$rsOrder["Address_Detailed"].'【'.$rsOrder["Address_Name"].'，'.$rsOrder["Address_Mobile"].'】' ?></span> <span class="cp_item_mod"> 地址：
                <select name="Province" id="loc_province" style="width:120px">
                </select>
                  <select name="City" id="loc_city" style="width:120px">
                </select>
                  <select name="Area" id="loc_town" style="width:120px">
                </select>
                <input type="text" name="Detailed" value="<?php echo $rsOrder["Address_Detailed"] ?>" size="35" />
                <div class="blank9"></div>
				<?php if(!is_numeric($rsOrder["Address_Province"])){?>
				<script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script> 
                <script language="javascript">new PCAS('Province', 'City', 'Area', '<?php echo $rsOrder["Address_Province"] ?>', '<?php echo $rsOrder["Address_City"] ?>', '<?php echo $rsOrder["Address_Area"] ?>');</script>
				<?php }else{?>
				<script type='text/javascript' src="/static/js/select2.js"></script>
				<script type="text/javascript" src="/static/js/location.js"></script>
				<script type="text/javascript" src="/static/js/area.js"></script>
				<link href="/static/css/select2.css" rel="stylesheet"/>
				<script type="text/javascript">
				$(document).ready(function(){
					showLocation(<?php echo $rsOrder["Address_Province"];?>,<?php echo $rsOrder["Address_City"];?>,<?php echo $rsOrder["Address_Area"];?>);
				});
				</script>
				<?php }?>
                </span></td>
            </tr>
            <tr>
              <td nowrap>订单备注：</td>
              <td><span class="cp_item_view"><?php echo $rsOrder["Order_Remark"] ?></span> <span class="cp_item_mod">
                <textarea name="Remark" rows="5" cols="50"><?php echo $rsOrder["Order_Remark"] ?></textarea>
                </span></td>
            </tr>
            <tr class="cp_item_mod">
              <td></td>
              <td><input type="submit" class="btn_green" name="submit_button" value="提交保存" />
                <input type="button" class="back btn_gray" name="back" value="取消" /></td>
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
			if(empty($Attr['Name'])){
				echo '<dd>'.$Attr.'</dd>';
			}else{
				echo '<dd>'.$Attr['Name'].': '.$Attr['Value'].'</dd>';
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