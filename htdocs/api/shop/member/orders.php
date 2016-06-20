<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');

$Status=empty($_GET["Status"])?0:$_GET["Status"];

//如果设置了action
if(!empty($_GET['action'])){
	$action = $_GET['action'];
	$Order_ID = $_GET['OrderID'];
	
	if($action == 'delete'){
		
		//若是分销订单，删除分销记录
		if(is_distribute_order($DB,$UsersID,$Order_ID)){
			delete_distribute_record($DB,$UsersID,$Order_ID);
		}
	    $condition = "where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID=".$Order_ID;
		$rsOrder = $DB->getRs('user_order','Integral_Consumption',$condition);
		mysql_query("BEGIN"); //开始事务定义
		
		$Flag_a = TRUE;
		if($rsOrder['Integral_Consumption']>0){
			$Falg_a = remove_userless_integral($UsersID,$_SESSION[$UsersID."User_ID"],$rsOrder['Integral_Consumption']);
		}
		
		$condition = "Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID=".$Order_ID;
		$Flag_b =$DB->Del("user_order",$condition);
		
		
		if($Flag_a&&$Flag_b)
		{
			mysql_query("COMMIT"); //执行事务
			$url = $base_url.'api/'.$UsersID.'/shop/member/status/0/';	
			echo '<script language="javascript">alert("订单取消成功");window.location="'.$url.'";</script>';
		}else
		{
			mysql_query("ROLLBACK"); //回滚事务
			echo '<script language="javascript">alert("订单取消失败");history.back();</script>';
		}
		exit;
		
	}

}

$_STATUS = array('<font style="color:#F00; font-size:12px;">申请中</font>','<font style="color:#F60; font-size:12px;">卖家同意</font>','<font style="color:#0F3; font-size:12px;">买家发货</font>','<font style="color:#600; font-size:12px;">卖家收货并确定退款价格</font>','<font style="color:blue; font-size:12px;">完成</font>','<font style="color:#999; font-size:12px; text-decoration:line-through;">卖家拒绝退款</font>');

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css?' rel='stylesheet' type='text/css' />
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
  <div id="order_list">
<?php
$DB->get("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_Type='shop' and Order_Status=".$Status." order by Order_CreateTime desc");
$lists = array();
while($r=$DB->fetch_assoc()){
	$lists[] = $r;
}
foreach($lists as $rsOrder){
	$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
	$lists_back = array();
	if($rsOrder["Is_Backup"]==1){
		$condition = "where Users_ID='".$UsersID."' and Order_ID=".$rsOrder["Order_ID"]." and Back_Type='shop'";
		$DB->Get("user_back_order","*",$condition);
		while($b=$DB->fetch_assoc()){
			$lists_back[] = $b;
		}
	}
?>
    <div class="item">
      <h1> 订单号：<a href="<?php echo '/api/'.$UsersID.'/shop/member/detail/'.$rsOrder["Order_ID"].'/' ?>"><?php echo date("Ymd",$rsOrder["Order_CreateTime"]).$rsOrder["Order_ID"] ?></a> （<strong class="fc_red">￥<?php echo $rsOrder["Order_TotalPrice"] ?></strong>）<?php echo $rsOrder["Coupon_ID"]==0 ? '' : '<font style="color:blue; font-size:12px;">已使用优惠券</font>';?> </h1>
	  <?php if(count(json_decode(htmlspecialchars_decode($rsOrder['Order_Shipping']), true))>0 && !empty($rsOrder["Order_ShippingID"])){?>
      
      <?php 
	  	$shipping = json_decode(htmlspecialchars_decode($rsOrder['Order_Shipping']),true) ;
		if(!empty($shipping['Express'])){
		$shipping_trace = 'http://m.kuaidi100.com/index_all.html?type='.$shipping['Express'].'&postid='.$rsOrder["Order_ShippingID"].'&callbackurl='.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			$Shipping_Express = !empty($shipping['Express'])?$shipping['Express'].'-':'';
			$Express_Name = !empty($shipping['Express'])?$shipping['Express']:'';
		}else{
			$shipping_trace = 'javascript:void(0)';
			$Shipping_Express = '';
		}
	  ?>
      
	  <h1 style='height:30px;line-height:30px;'><a href="<?=$shipping_trace?>"><?php echo $Shipping_Express.$rsOrder["Order_ShippingID"] ?></a><a href="<?php echo 'http://m.kuaidi100.com/index_all.html?type='.$Express_Name.'&postid='.$rsOrder["Order_ShippingID"].'&callbackurl='.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>"><span style='background-color:#DE2337;padding:3px 10px;border-radius:5px;color:#FFF;margin-left:15px;'>快递跟踪</span></a> </h1>
	  <?php }?>
<?php
	if(isset($CartList)){
		foreach($CartList as $key=>$value){
			foreach($value as $k=>$v){
				echo '<div class="pro">
					<div class="img"><a href="/api/'.$UsersID.'/shop/products/'.$key.'/"><img src="'.$v["ImgPath"].'" width="70" height="70"></a></div>
					<dl class="info">
						<dd class="name"><a href="/api/'.$UsersID.'/shop/products/'.$key.'/">'.$v["ProductsName"].'</a></dd>				
						<dd>￥'.$v["ProductsPriceX"].'×'.$v["Qty"].'=￥'.$v["ProductsPriceX"]*$v["Qty"].'</dd>';
				if($rsOrder['Order_Status'] == 2 || $rsOrder['Order_Status'] == 3){
					echo '<dd style="height:26px"><a href="'.$base_url.'api/'.$UsersID.'/shop/member/backup/'.$rsOrder["Order_ID"].'_'.$key.'_'.$k.'/" style="display:block; width:70px; height:26px; line-height:24px; color:#FFF; background:#F60; border-radius:8px; text-align:center; font-size:12px; font-weight:normal; float:right">申请退款</a></dd>';
				}
				echo '</dl>';
				
				echo '<div class="clear"></div>
				</div>';
			}
		}
	}
	if(!empty($lists_back)){
		foreach($lists_back as $item){
			$CartList_back=json_decode(htmlspecialchars_decode($item["Back_Json"]),true);
			
				echo '<div class="pro">
					<div class="img"><a href="/api/'.$UsersID.'/shop/products/'.$item["ProductID"].'/"><img src="'.$CartList_back["ImgPath"].'" width="70" height="70"></a></div>
					<dl class="info">
						<dd class="name"><a href="/api/'.$UsersID.'/shop/products/'.$item["ProductID"].'/">'.$CartList_back["ProductsName"].'</a></dd>				
						<dd>￥'.$CartList_back["ProductsPriceX"].'×'.$CartList_back["Qty"].'=￥'.$CartList_back["ProductsPriceX"]*$CartList_back["Qty"].'</dd>';
				echo '<dd style="height:26px; font-size:12px;">'.$_STATUS[$item["Back_Status"]].'<a href="'.$base_url.'api/'.$UsersID.'/shop/member/backup/detail/'.$item["Back_ID"].'/" style="display:block; width:70px; height:26px; line-height:24px; color:#FFF; background:#696969; border-radius:8px; text-align:center; font-size:12px; font-weight:normal; float:right">退款详情</a></dd>';
				echo '</dl>';
					
				echo '<div class="clear"></div>
				</div>';
		}
	}
?>
  
   
   <?php if($rsOrder['Order_Status'] == 0): ?>
   <div class="button_panel">   
     <div class="cancel"><a href="<?=$base_url?>api/shop/member/orders.php?UsersID=<?=$UsersID?>&action=delete&OrderID=<?=$rsOrder["Order_ID"]?>">取消</a></div>
	
	 <div class="clear"></div>
   </div>
   <?php endif;?>
   
   <?php if($rsOrder['Order_Status'] == 1): ?>
   		<div class="button_panel">   
          <div class="cancel"><a href="<?=$base_url?>api/shop/member/orders.php?UsersID=<?=$UsersID?>&action=delete&OrderID=<?=$rsOrder["Order_ID"]?>">取消</a></div>
	 <div class="payment"><a href="<?=$base_url?>api/<?=$UsersID?>/shop/cart/payment/<?=$rsOrder["Order_ID"]?>/">付款</a></div>
	 <div class="clear"></div>
   </div>
   
   <?php endif;?>
   
    <?php if($rsOrder['Order_Status'] == 3 && !empty($CartList)): ?>
     <div class="button_panel">
		<div class="confirm_receive"><a class="confirmreceive" href="javascript:void(0)"  Order_ID="<?=$rsOrder['Order_ID']?>" style="margin:0px 0px 0px 8px">确认收货</a></div>  
	</div>
	<?php endif;?>
    <?php if($rsOrder['Order_Status'] == 4 && $rsOrder["Is_Commit"]==0): ?>
   <div class="button_panel">   
     <div class="payment"><a href="<?=$base_url?>api/<?php echo $UsersID ?>/shop/member/commit/<?php echo $rsOrder["Order_ID"] ?>/">评论</a></div>
	 <div class="clear"></div>
   </div>
   <?php endif;?>
    </div>
<?php }?>
  </div>
</div>
<?php
 	require_once('../skin/distribute_footer.php');
 ?>
</body>
</html>