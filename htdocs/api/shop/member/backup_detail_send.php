<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

$BackID = $_GET['BackID'];

$rsBackup = $DB->GetRs("user_back_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Back_ID='".$BackID."'");

$CartList_back = json_decode(htmlspecialchars_decode($rsBackup['Back_Json']),TRUE);

$Status = $rsBackup["Back_Status"];
$_STATUS = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:#blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');

$Shipping = json_decode($rsBackup["Back_Shipping"],true);

$rsBiz = $DB->GetRs("biz","*","where Users_ID='".$UsersID."' and Biz_ID=".$rsBackup["Biz_ID"]);

$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
$area_array = json_decode($area_json,TRUE);
$province_list = $area_array[0];
$Province = '';
if(!empty($rsBiz['Biz_RecieveProvince'])){
	$Province = $province_list[$rsBiz['Biz_RecieveProvince']].',';
}
$City = '';
if(!empty($rsBiz['Biz_RecieveCity'])){
	$City = $area_array['0,'.$rsBiz['Biz_RecieveProvince']][$rsBiz['Biz_RecieveCity']].',';
}

$Area = '';
if(!empty($rsBiz['Biz_RecieveArea'])){
	$Area = $area_array['0,'.$rsBiz['Biz_RecieveProvince'].','.$rsBiz['Biz_RecieveCity']][$rsBiz['Biz_RecieveArea']];
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
<title>退款发货 - 个人中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">$(document).ready(shop_obj.backup_send);</script>
</head>

<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/member.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
  <div id="order_detail">
  	<div class="item">
	  <?php
	  echo '<div class="pro">
			<div class="img"><a href="/api/'.$UsersID.'/shop/products/'.$rsBackup["ProductID"].'/"><img src="'.$CartList_back["ImgPath"].'" width="70" height="70"></a></div>
			<dl class="info">
				<dd class="name"><a href="/api/'.$UsersID.'/shop/products/'.$rsBackup["ProductID"].'/">'.$CartList_back["ProductsName"].'</a></dd>				
				<dd>￥'.$CartList_back["ProductsPriceX"].'×'.$CartList_back["Qty"].'=￥'.$CartList_back["ProductsPriceX"]*$CartList_back["Qty"].'</dd>';
		foreach($CartList_back["Property"] as $Attr_ID=>$Attr){
			echo '<dd style="padding:0px; margin:0px;">'.$Attr['Name'].': '.$Attr['Value'].'</dd>';
		}
	  echo '</dl>';
	  echo '<div class="clear"></div>
			</div>';
	  ?>
    </div>
    <div class="item">
      <ul>
        <li>退款编号：<?php echo $rsBackup["Back_Sn"]; ?></li>
        <li>退款时间：<?php echo date("Y-m-d H:i:s",$rsBackup["Back_CreateTime"]) ?></li>
        <li>退款数量：<strong><?php echo $rsBackup["Back_Qty"] ?></strong></li>
        <li>退款总价：<strong class="fc_red">￥<?php echo $rsBackup["Back_Amount"] ?></strong></li>
        <li>账号：<?php echo $rsBackup["Back_Account"] ?></li>
        <li>退款状态：<?php echo $_STATUS[$rsBackup["Back_Status"]] ?></li>
		<li>商家收货地址：<?php echo $Province.$City.$Area.'【'.$rsBiz["Biz_RecieveAddress"].' ， '.$rsBiz["Biz_RecieveName"].'，'.$rsBiz["Biz_RecieveMobile"].'】';?></li>
      </ul>
    </div>
    <form action="<?=$base_url?>api/<?=$UsersID?>/shop/member/" name="send_form" id="send_form" />
     <input type="hidden" name="action" value="backup_send" />
     <input type="hidden" name="BackID" value="<?php echo $BackID;?>" />
     	<div class="backup_reason">
         <dl>
           <dd><input name="shipping" style="width:80%; margin:8px auto 0px; display:block; border:#dfdfdf solid 1px; height:28px; line-height:28px;" placeholder="请输入物流方式" notnull /></dd>
           <dd><input name="shippingID" placeholder="请输入物流单号" style="width:80%; margin:8px auto 0px; display:block; border:#dfdfdf solid 1px; height:28px; line-height:28px;" notnull /></dd>
           <dt><input type="submit" name="submit" class="submit" value="确定发货" /></dt>
         </dl>
        </div>
    </form>
  </div>
</div>
<?php
 	require_once('../skin/distribute_footer.php');
 ?>
</body>
</html>