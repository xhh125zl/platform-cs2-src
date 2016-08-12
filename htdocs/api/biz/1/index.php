<?php
$rsSkin = $DB->GetRs("biz_home","Home_Json","where Skin_ID=".$rsBiz["Skin_ID"]." and Biz_ID=".$BizID);
$Home_Json=json_decode($rsSkin['Home_Json'],true);

foreach($Home_Json as $home_key=>$home_value){
	if(is_array($home_value["Title"])){
		$Home_Json[$home_key]["Title"] = json_encode($home_value["Title"]);
	}
	if(is_array($home_value["ImgPath"])){
		$Home_Json[$home_key]["ImgPath"] = json_encode($home_value["ImgPath"]);
	}
	if(is_array($home_value["Url"])){
		$Home_Json[$home_key]["Url"] = json_encode($home_value["Url"]);
	}
}
require_once($rsBiz["Skin_ID"]."/top.php");
?>
<link href='/static/api/shop/biz/page.css?t=<?php echo time() ?>' rel='stylesheet' type='text/css' />
<link href='/static/js/plugin/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script>
<script type='text/javascript' src='/static/api/shop/js/index.js'></script>
<script language="javascript">
var shop_skin_data=<?php echo json_encode($Home_Json);?>;
$(document).ready(index_obj.index_init);
</script>
 <div id="shop_skin_index">
    <div class="shop_skin_index_list banner" rel="edit-t01">
        <div class="img"></div>
    </div>
 </div>
 <div id="products">
  <h1>新品上市</h1>
  <?php
  	  $i=0;
      $DB->get("shop_products","*","where Users_ID='".$UsersID."' and Biz_ID=".$BizID." and Products_SoldOut=0 and Products_Status=1 and Products_BizIsNew=1 order by Products_CreateTime desc");
	  while($v=$DB->fetch_assoc()){
		  $i++;
		  $JSON=json_decode($v['Products_JSON'],true);
  ?>
  <div class="item">
	<ul>
		<li class="img"><a href="<?php echo $shop_url;?>products/<?php echo $v["Products_ID"];?>/"><img src="<?php echo $JSON["ImgPath"][0];?>" /></a></li>
		<li class="name"><a href="<?php echo $shop_url;?>products/<?php echo $v["Products_ID"];?>/"><?php echo $v["Products_Name"];?></a></li>
        <li class="price">￥<?php echo $v["Products_PriceX"];?><span>￥<?php echo $v["Products_PriceY"];?></span></li>
	</ul>
  </div>
  <?php echo $i%2==0 ? '<div class="clear"></div>' : '';?>
  <?php }?>
  <div class="clear"></div>
 </div>
 <div class="b8"></div>
 <?php
     $DB->get("shop_products","*","where Users_ID='".$UsersID."' and Biz_ID=".$BizID." and Products_Status=1 and Products_SoldOut=0 and Products_BizIsRec=1 order by Products_CreateTime desc");
	 if($DB->num_rows()){
 ?>
 <div id="products">
  <h1>热销推荐</h1>
  <?php
	  $j=0;
	  while($v=$DB->fetch_assoc()){
		  $j++;
		  $JSON=json_decode($v['Products_JSON'],true);
  ?>
  <div class="item">
	<ul>
		<li class="img"><a href="<?php echo $shop_url;?>products/<?php echo $v["Products_ID"];?>/"><img src="<?php echo $JSON["ImgPath"][0];?>" /></a></li>
		<li class="name"><a href="<?php echo $shop_url;?>products/<?php echo $v["Products_ID"];?>/"><?php echo $v["Products_Name"];?></a></li>
        <li class="price">￥<?php echo $v["Products_PriceX"];?><span>￥<?php echo $v["Products_PriceY"];?></span></li>
	</ul>
  </div>
  <?php echo $j%2==0 ? '<div class="clear"></div>' : '';?>
  <?php }?>
  <div class="clear"></div>
 </div>
 <?php }?>
 <?php
  	  $i=0;
  	  $pintuan_url = "/api/{$UsersID}/pintuan/biz/{$BizID}/";
  	  $time = time();
      $result = $DB->get("pintuan_products","*","where Users_ID='{$UsersID}' and Biz_ID={$BizID} and Products_Status=1 and starttime<={$time} and stoptime>={$time} order by Products_CreateTime desc limit 0,4");
      $pt_goods = $DB->toArray($result);
       if(!empty($pt_goods)){
 ?>     
 <div id="products">
  <h1>拼团产品</h1>
  <?php
    foreach($pt_goods as $k => $v){
		  $i++;
		  $JSON=json_decode($v['Products_JSON'],true);
  ?>
  <div class="item">
	<ul>
		<li class="img"><a href="<?=$pintuan_url ?>"><img src="<?php echo $JSON["ImgPath"][0];?>" /></a></li>
		<li class="name"><a href="<?=$pintuan_url ?>"><?php echo $v["Products_Name"];?></a></li>
     <li class="price">团购：￥<?php echo $v["Products_PriceT"];?> 单购：￥<?php echo $v["Products_PriceD"];?></li>
	</ul>
  </div>
  <?php echo $i%2==0 ? '<div class="clear"></div>' : '';?>
  <?php }?>
  <div class="clear"></div>
 </div>
 <?php } ?>
 
 <?php
  	  $i=0;
  	  $pintuan_url = "/api/{$UsersID}/cloud/biz/{$BizID}/";
  	  $time = time();
      $result = $DB->get("cloud_products","*","where Users_ID='{$UsersID}' and Biz_ID={$BizID} and Products_Status=1 and Products_SoldOut=0 order by Products_CreateTime desc limit 0,4");
      $pt_goods = $DB->toArray($result);
       if(!empty($pt_goods)){
 ?>     
 <div id="products">
  <h1>云购产品</h1>
  <?php
    foreach($pt_goods as $k => $v){
		  $i++;
		  $JSON=json_decode($v['Products_JSON'],true);
  ?>
  <div class="item">
	<ul>
		<li class="img"><a href="<?=$pintuan_url ?>"><img src="<?php echo $JSON["ImgPath"][0];?>" /></a></li>
		<li class="name"><a href="<?=$pintuan_url ?>"><?php echo $v["Products_Name"];?></a></li>
     <li class="price">￥<?php echo $v["Products_PriceX"];?><span>￥<?php echo $v["Products_PriceY"];?></span></li>
	</ul>
  </div>
  <?php echo $i%2==0 ? '<div class="clear"></div>' : '';?>
  <?php }?>
  <div class="clear"></div>
 </div>
 <?php } ?>
 <?php require_once($rsBiz["Skin_ID"]."/footer.php");?>