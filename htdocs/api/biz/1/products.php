<?php require_once($rsBiz["Skin_ID"].'/top.php');?>
<div class="list_sorts">
<?php require_once($rsBiz['Skin_ID']."/order_filter.php");?>
</div>
 <div id="products" style="border-top:none; padding-top:10px;">
  <?php
  	  $i=0;
      $DB->getPage("shop_products","*",$condition,$pageSize=20);
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
 </div>
 <?php $DB->showWechatPage($page_url); ?>
<?php require_once($rsBiz["Skin_ID"].'/footer.php');?>