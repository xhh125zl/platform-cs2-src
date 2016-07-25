<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$OrderID=empty($_REQUEST['OrderID'])?0:$_REQUEST['OrderID'];
$sql = "select * from user_order as o left join user as u on o.User_ID=u.User_ID where u.Users_ID='".$_SESSION["Users_ID"]."' and o.Order_ID in(".$OrderID.")";
$result =$DB->query($sql);
$orderlist = array();
while($res=$DB->fetch_assoc($result))
{
  $res['Order_SN'] = date("Ymd",$res['Order_CreateTime']).'-'.$res['Order_ID'];
  $res['Order_CreateTime'] = date("Y-m-d H:i:s",$res['Order_CreateTime']);
  $res['Order_Shipping'] = json_decode(htmlspecialchars_decode($res['Order_Shipping']),true);
  $res['Order_ShippingID'] = !empty($res['Order_ShippingID'])?$res['Order_ShippingID']:'未发货';
  //订单商品信息处理
  $res['Order_CartList'] = json_decode(htmlspecialchars_decode($res['Order_CartList']), true);
  $res['Order_PaymentMethod'] = $res['Order_PaymentMethod']?$res['Order_PaymentMethod']:'未支付'; 
  if(is_numeric($res['Address_Province'])){
    $area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
    $area_array = json_decode($area_json,TRUE);
    $province_list = $area_array[0];
    $Province = '';
    if(!empty($res['Address_Province'])){
      $Province = $province_list[$res['Address_Province']];
    }
    $City = '';
    if(!empty($res['Address_City'])){
      $City = $area_array['0,'.$res['Address_Province']][$res['Address_City']];
    }

    $Area = '';
    if(!empty($res['Address_Area'])){
      $Area = $area_array['0,'.$res['Address_Province'].','.$res['Address_City']][$res['Address_Area']];
    }
    $res['Address_Province'] = $Province;
    $res['Address_City'] = $City;
    $res['Address_Area'] = $Area;
  }
  //购货人信息
  $orderlist[] = $res;
}
//获取寄件人信息
$receiveInfo = $DB->GetRs("user_recieve_address","*","WHERE Users_ID='".$_SESSION["Users_ID"]."'");
if(is_numeric($receiveInfo['RecieveProvince'])){
    
    $area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
    $area_array = json_decode($area_json,TRUE);
    $province_list = $area_array[0];
    $Province = '';
    if(!empty($receiveInfo['RecieveProvince'])){
        $Province = $province_list[$receiveInfo['RecieveProvince']];
    }
    $City = '';
    if(!empty($receiveInfo['RecieveCity'])){
        $City = $area_array['0,'.$receiveInfo['RecieveProvince']][$receiveInfo['RecieveCity']];
    }
    
    $Area = '';
    if(!empty($receiveInfo['RecieveArea'])){
        $Area = $area_array['0,'.$receiveInfo['RecieveProvince'].','.$receiveInfo['RecieveCity']][$receiveInfo['RecieveArea']];
    }
    $receiveInfo['Address_Province'] = $Province;
    $receiveInfo['Address_City'] = $City;
    $receiveInfo['Address_Area'] = $Area;
}
?>
<html>
  <head>
  <script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
  <script type='text/javascript' src='/static/member/js/jquery.jqprint-0.3.js'></script>
    <style type="text/css">
      body,td {font-size:13px;}
      .lbl {width:100px;}
      table thead td {  }
      .bold { font-weight:bold;font-size:22px;    border-top: solid 2px #555; }
      .pd200 { margin-right:200px;}
      .pd100 { margin-right:100px;}
      .pdtop30 { margin-top:30px;}
      .grypd10 { margin-top:5px;  border-top: solid 1px #999;}
    </style>
    <script language="javascript">
    function  print(){
    	$("#printArea").jqprint();
    }
 
</script>
  </head>
  <body>
      <input type="button" onclick=" print()" value="打印"/>
      <div id="printArea" class="pdtop30">
      <?php foreach($orderlist as $Key => $order): ?>

          <table width="100%" cellpadding="1" style="margin-bottom: 50px;">
          	 <thead>
          	 	<td class="bold">订单编号：<?=$order['Order_Code']?></td>
          	 	<td></td>
          	 </thead>
          	 <tbody>
          	 	<tr>
          	 		<td colspan="2">
          	 			<p><span class="lbl">收件人：</span><span class="pd200"><?=$order['Address_Name']?:$order['Address_Name'] ?></span><span>电话：</span><span><?=$order['Address_Mobile']?></span></p>
          	 			<p><span  class="lbl">收货地址：</span><span>【<?=$order['Address_Province']?> <?=$order['Address_City']?> <?=$order['Address_Area']?>】&nbsp;<?=$order['Address_Detailed']?>&nbsp;</span></p>
          	 			<p><span  class="lbl">货品名：</span><span><?=$order['Order_CartList']['ProductsName']?>（￥ <?=$order['Order_Type']=='pintuan'?$order['Order_CartList']['ProductsPriceT']*$order['Order_CartList']['num']:$order['Order_CartList']['ProductsPriceD']*$order['Order_CartList']['num'] ?>）</span>
          	 			<span class="pd100">数量：1</span></p>
          	 			<p><span  class="lbl">货品费用：</span><span>￥ <?=$order['Order_Type']=='pintuan'?$order['Order_CartList']['ProductsPriceT']*$order['Order_CartList']['num']:$order['Order_CartList']['ProductsPriceD']*$order['Order_CartList']['num'] ?> + 配送费用：￥ <?=isset($order['Order_Shipping']['Price'])?$order['Order_Shipping']['Price']:1?>  = 总金额：￥ <?=$order['Order_TotalAmount']?></span></p>
          	 		</td>
          	 	</tr>
          	 	<?php if(isset($receiveInfo['RecieveName']) && !empty($receiveInfo['RecieveName']) && isset($receiveInfo['RecieveMobile']) && !empty($receiveInfo['RecieveMobile'])){?>
          	 	<tr>
          	 		<td colspan="2" class="grypd10">
          	 			<p ><span class="lbl">发件人：</span><span class="pd200"><?=$receiveInfo['RecieveName'] ?></span><span>联系方式：</span><span><?=$receiveInfo['RecieveMobile'] ?></span></p>
          	 			<p><span  class="lbl">地址：</span><span>【<?=$receiveInfo['Address_Province']?> <?=$receiveInfo['Address_City']?> <?=$receiveInfo['Address_Area']?>】&nbsp;<?=$receiveInfo['RecieveAddress']?>&nbsp;</span></p>
          	 		</td>
          	 	</tr>
          	 	<?php }?>
          	 </tbody>
          </table>
      <?php endforeach; ?>
      </div>
  </body>
</html>