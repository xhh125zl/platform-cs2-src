<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/biz/global.php');
$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID='".$OrderID."'");


if($_POST){
	if($rsOrder["Order_Status"]<2){
		$Data = array(
			"Order_TotalPrice"=>empty($_POST["TotalPrice"]) ? 0 : $_POST["TotalPrice"],
			"Order_Status"=>$_POST["Status"]
		);
		$Flag = $DB->Set("user_order",$Data,"where Order_ID=".$OrderID);
		if($Flag){
			echo '<script language="javascript">alert("修改成功");window.location.href="orders_view.php?OrderID='.$OrderID.'";</script>';
		}else{
			echo '<script language="javascript">alert("修改失败");history.back();</script>';
		}
		exit;
	}
}


$rsConfig=$DB->GetRs("shop_config","ShopName,NeedShipping","where Users_ID='".$_SESSION["Users_ID"]."'");

$Status=$rsOrder["Order_Status"];
$Order_Status=array("待确认","待付款","已付款","已发货","已完成");
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
    <div id="orders" class="r_con_wrap">
      <div class="control_btn">
      <!-- <a href="javascript:void(0);" class="btn_gray" onClick="history.go(-1);">返 回</a> -->
      <a href="javascript:"  class="btn_gray" onclick="self.location=document.referrer;">返回</a> 
      <a class="btn_gray" id="backup" style="border-left-width: 100px; margin-left: 50px;">退款</a>
      </div>     
      <div class="detail_card">
        <form method="post" action="?OrderID=<?php echo $OrderID;?>">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>订单编号：</td>
              <td width="92%"><?php echo $rsOrder["Order_Code"] ?>
                  <input type="hidden" name="orderid" value="<?php echo $rsOrder["Order_ID"];?>">（<?php if($rsOrder['Order_Type']=='pintuan'){
                      echo "此订单是团购";
                  }else{
                      echo "此订单是单购";
                  }?>）
              </td>
            </tr>
			
            <tr>
              <td nowrap>订单总价：</td>
              <td>￥<input type="text" size="10" name="TotalPrice" value="<?php echo $rsOrder["Order_TotalPrice"] ?>"<?php echo $rsOrder["Order_Status"]>=2 ? ' disabled="disabled"' : '';?> notnull><?php echo $rsOrder["Back_Amount"]>0 ? '&nbsp;&nbsp;<font style="text-decoration:line-through; color:#999">&nbsp;退款金额：￥'.$rsOrder["Back_Amount"].'&nbsp;</font>&nbsp;&nbsp;' : "";?></td>
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
              <td><select name="Status"<?php echo $rsOrder["Order_Status"]>=2 ? ' disabled="disabled"' : ''?>>
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
            <tr>
              <td nowrap>手机号码：</td>
              <td><?php echo $rsOrder["Address_Mobile"] ?></td>
            </tr>
            <tr>
              <td nowrap>联系人：</td>
              <td><?php echo $rsOrder["Address_Name"] ?></td>
            </tr>
            <tr>
              <td nowrap>付款信息：</td>
              <td><?php echo $rsOrder["Order_PaymentInfo"] ?></td>
            </tr>
            <tr>
              <td nowrap>地址信息：</td>
              <td><?php echo $Province.$City.$Area.'【'.$rsOrder["Address_Name"].'，'.$rsOrder["Address_Mobile"].'】'.'&nbsp;&nbsp;&nbsp;&nbsp;详细地址: '.$rsOrder["Address_Detailed"] ?></td>
            </tr>
            <tr>
              <td nowrap>订单备注：</td>
              <td><?php echo $rsOrder["Order_Remark"] ?></td>
            </tr>
			<?php if($rsOrder["Order_Status"]<2){?>
			<tr>
              <td nowrap>&nbsp;</td>
              <td><input type="submit" name="submit" value="确定" /></td>
            </tr>
			<?php }?>
			
          </table>
          
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

//转换数组  

$arr[]=$CartList;
$update=array();
$update[$CartList['Products_ID']]=$arr;

foreach($update as $key=>$value){
	foreach($value as $k=>$v){
		$total+=$v["num"]*$v["ProductsPriceT"];
		$qty+=$v["num"];
		echo '<tr class="item_list" align="center">
            <td valign="top"><img src="'.$v["ImgPath"].'" width="100" height="100" /></td>
            <td align="center" class="flh_180">'.$v["ProductsName"].'<br>';
        echo '</td>
            <td>￥'.($rsOrder['Order_Type']=='dangou'?$v["ProductsPriceD"]:$v["ProductsPriceT"]).'</td>
            <td>'.$v["num"].'</td>
            <td>￥'.($rsOrder['Order_Type']=='dangou'?$v["ProductsPriceD"]:$v["ProductsPriceT"])*$v["num"].'</td>
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
  <script type="text/javascript">
   $(document).ready(function(){
       var price='40002';
       var orderid=<?php echo $rsOrder["Order_ID"];?>;
       $.post('ajax.php',{price:price,Order:orderid},function(data){
          var res=data.t;
          var wenzi=data.msg;
              if(res=='2'){
                  $('#backup').unbind('click').html(wenzi);
              }
              if(res=='3'){
                  $('#backup').unbind('click').html(wenzi);
              }

            },'json');
    });
    //退款按钮
    $('#backup').click(function(){
      //获取当前的商品信息
      var action='40001';
      var orderid=<?php echo $rsOrder["Order_ID"];?>;
      if(confirm('您确定您已经手动退款了!')){
         $.post('ajax.php',{action:action,Order:orderid},function(data){
          var res=data.a;
              if (res){
                  alert('退款已成功');
                  $('#backup').unbind('click').html('已手动退款');
              }
            },'json');
      }     
    });
  </script>
</html>