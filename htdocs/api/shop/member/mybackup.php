<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

$Status  = !empty($_GET['status'])?$_GET['status']:0;

//获取退货单
$condition = "where Users_ID='".$UsersID."'";
$condition .= " and User_ID=".$_SESSION[$UsersID.'User_ID'];
//$condition .= " and Back_Status=".$Status ;
$condition .= ' order by Back_UpdateTime desc, Back_CreateTime desc';

$rsBackList = $DB->Get("user_back_order","*",$condition);
$back_list = $DB->toArray($rsBackList);


$_STATUS = array('<font style="color:#F00">申请中</font>','<font style="color:#F60">卖家同意</font>','<font style="color:#0F3">买家发货</font>','<font style="color:#600">卖家收货并确定退款价格</font>','<font style="color:blue">完成</font>','<font style="color:#999; text-decoration:line-through;">卖家拒绝退款</font>');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的退款单</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">$(document).ready(shop_obj.page_init);</script>
</head>

<body>
<div id="shop_page_contents" style="color:#0F3"
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/member.css' rel='stylesheet' type='text/css' />
  <h2 style="width:100%; height:40px; line-height:38px; font-size:16px; text-align:center; font-weight:bold; border-bottom:1px #dfdfdf solid">我的退款单</h2>
  <div id="order_list">
  
  <?php foreach($back_list as $key=>$item):?>  
  <?php $CartList_back = json_decode(htmlspecialchars_decode($item['Back_Json']),TRUE)?>
    <div class="item">
      <h1> 退货单号：<a href="/api/<?=$UsersID?>/shop/member/backup/detail/<?=$item['Back_ID']?>/"><?=$item['Back_Sn']?></a></h1>
      <?php
	  echo '<div class="pro">
			<div class="img"><a href="/api/'.$UsersID.'/shop/products/'.$item["ProductID"].'/"><img src="'.$CartList_back["ImgPath"].'" width="70" height="70"></a></div>
			<dl class="info">
				<dd class="name"><a href="/api/'.$UsersID.'/shop/products/'.$item["ProductID"].'/">'.$CartList_back["ProductsName"].'</a></dd>				
				<dd>￥'.$CartList_back["ProductsPriceX"].'×'.$CartList_back["Qty"].'=￥'.$CartList_back["ProductsPriceX"]*$CartList_back["Qty"].'</dd>';
			echo '<dd style="height:26px; color:#ff0000">'.$_STATUS[$item["Back_Status"]];
			if($item["Back_Status"]==1){
				echo '<a href="'.$base_url.'api/'.$UsersID.'/shop/member/backup/detail_send/'.$item["Back_ID"].'/" style="display:block; width:70px; height:26px; line-height:24px; color:#FFF; background:#F60; border-radius:8px; text-align:center; font-size:12px; font-weight:normal; float:right">我要发货</a>';
			}
			echo '</dd>';
	  echo '</dl>';
	  echo '<div class="clear"></div>
			</div>';
?>
    </div>
  <?php endforeach;?>
  </div>
</div>
<?php
 	require_once('../skin/distribute_footer.php');
 ?>
</body>
</html>
