<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID = $_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$_SESSION[$UsersID."HTTP_REFERER"] = "/api/".$UsersID."/cloud/member/products/";

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
$_STATUS_SHIPPING = array('<font style="color:#FF0000">待付款</font>','<font style="color:#03A84E">待发货</font>','<font style="color:#F60">待收货</font>','<font style="color:blue">已领取</font>','<font style="color:#999; text-decoration:line-through">&nbsp;已取消&nbsp;</font>');
$_STATUS = array('','<font style="color:#FF0000">未领取</font>','','<font style="color:blue">已领取</font>');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云购商品领取</title>
<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/cloud/js/shipping.js'></script>
<script type='text/javascript'>
	var ajax_url = '/api/<?php echo $UsersID;?>/cloud/member/products/ajax/';
	$(document).ready(function(){
		shipping_obj.shipping_init();
	});
</script>
</head>

<body>
<div id="gift">
   <div class="t_list"> <a href="/api/<?php echo $UsersID ?>/cloud/member/products/" class="c">已领取</a> <a href="/api/<?php echo $UsersID ?>/cloud/member/products/no/">未领取</a> </div>
  <?php
      $DB->query('SELECT p.Products_Name,p.Products_JSON,p.Products_Description,r.Cloud_Detail_ID,o.Orders_Status,o.Orders_ID,o.Orders_Code,o.Orders_IsShipping,o.Orders_TotalPrice,o.Orders_Shipping,o.Orders_ShippingID,o.Orders_FinishTime FROM shipping_orders o RIGHT JOIN cloud_products_detail r ON o.Detail_ID=r.Cloud_Detail_ID LEFT JOIN cloud_products p ON p.Products_ID = r.Products_ID WHERE r.User_ID = '.$_SESSION[$UsersID."User_ID"].' and o.Orders_Status<4 order by o.Orders_ID desc');
	  while($rs = $DB->fetch_assoc()){
		$ImgPath = get_prodocut_cover_img($rs);  
	  echo '<div class="item">
    <h1 style="font-size:14px;">【'.$rs['Products_Name'].'】</h1>
    <div class="d">
		<img src="'.$ImgPath.'" />
		<div class="others">';
		//print_r([$rs["Orders_Status"]]);
		//PRINT_R($rs);
	 if($rs["Orders_IsShipping"]==0){
		 echo '<p class="status">'.$_STATUS[$rs["Orders_Status"]].'</p>';
	 }else{
		 echo '<p class="status">'.$_STATUS_SHIPPING[$rs["Orders_Status"]].'</p>';
		 if($rs["Orders_Status"]==0){
			 echo '<a class="btns pay" href="/api/'.$UsersID.'/cloud/member/products/payment/'.$rs["Orders_ID"].'/">付&nbsp;款</a>';
			 echo '<a class="btns concel" href="javascript:void(0);" ret="'.$rs["Orders_ID"].'">取&nbsp;消</a>';
		 }elseif($rs["Orders_Status"]==2){
			 echo '<a class="btns recieve" href="javascript:void(0);" ret="'.$rs["Orders_ID"].'">收&nbsp;货</a>';
		 }
	 }
	 echo '
		</div>
		<div class="clear"></div>
	</div>';
	if($rs["Orders_Status"]==2){
		$Shipping=json_decode($rs["Orders_Shipping"],true);
		if(!empty($Shipping["Express"])){
			echo '<h2 style="text-align:left; text-indent:5px; font-weight:normal">'.$Shipping["Express"].($rs["Orders_ShippingID"] ? '&nbsp;&nbsp;&nbsp;单号：'.$rs["Orders_ShippingID"] : '').'</h2>';
		}
	}
	
	if($rs["Orders_IsShipping"]==0){
		echo '<h2 style="text-align:left; text-indent:5px; font-weight:normal">兑换码：'.$rs["Orders_Code"].'</h2>';
	}
	echo '
  </div>';
	  }
  ?>
</div>
<?php require_once('../member_footer.php'); ?>
</body>
</html>