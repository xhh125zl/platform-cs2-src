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
<title>退款单详情 - 个人中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">$(document).ready(shop_obj.backup_init);</script>
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
			<dl class="info" style="margin-top:0px; margin-bottom:0px; padding:0px">
				<dd class="name" style="margin:0px; padding:0px"><a href="/api/'.$UsersID.'/shop/products/'.$rsBackup["ProductID"].'/">'.$CartList_back["ProductsName"].'</a></dd>				
				<dd style="margin-top:0px; margin-bottom:0px; padding:0px">￥'.$CartList_back["ProductsPriceX"].'×'.$CartList_back["Qty"].'=￥'.$CartList_back["ProductsPriceX"]*$CartList_back["Qty"].'</dd>';
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
		
		<?php if($rsBackup["Back_Status"]==1){?>
                        <li>商家收货地址：<?php echo $Province.$City.$Area.'【'.$rsBiz["Biz_RecieveAddress"].' ， '.$rsBiz["Biz_RecieveName"].'，'.$rsBiz["Biz_RecieveMobile"].'】';?></li>
        <li><a href="<?php echo $base_url;?>api/<?php echo $UsersID;?>/shop/member/backup/detail_send/<?php echo $BackID;?>/" style="display:block; width:100px; height:30px; line-height:28px; color:#FFF; background:#F60; border-radius:8px; text-align:center; font-size:12px; font-weight:normal; margin:3px auto">我要发货</a></li>
		<?php }?>
      </ul>
    </div>
   
    <div class="item">
      <ul>
      	<?php
          	$DB->Get("user_back_order_detail","*","where backid=".$BackID." order by createtime asc");
			while($r = $DB->fetch_assoc()){
		?>
        <li style="position:relative; padding-left:120px; color:#999; font-size:12px; margin-bottom:3px"><em style="position:absolute; top:3px; left:0px; font-weight:normal; font-family:'Times New Roman'; font-size:12px; font-style:normal"><?php echo date("Y-m-d H:i:s",$r["createtime"]);?></em><?php echo $r["detail"];?></li>
        <?php }?>
      </ul>
    </div>
    
    
  </div>
</div>
<?php
 	require_once('../skin/distribute_footer.php');
 ?>
</body>
</html>