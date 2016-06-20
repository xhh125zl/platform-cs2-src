<?php require_once('header.php');?>
<div id="web_page_contents">
  <style type="text/css">
body,html{background:#eee;}
</style>
  <div id="lbs" class="wrap">
    <div class="shop_img"><img src="<?php echo $rsConfig["Stores_ImgPath"] ?>"></div>
    <a class="gps" href="http://api.map.baidu.com/marker?location=<?php echo $rsConfig["Stores_PrimaryLat"].','.$rsConfig["Stores_PrimaryLng"] ?>&title=<?php echo $rsConfig["Stores_Name"] ?>&name=<?php echo $rsConfig["Stores_Name"] ?>&content=<?php echo $rsConfig["Stores_Address"] ?>&output=html&wxref=mp.weixin.qq.com" target="_self"><img src="/static/api/web/skin/default/images/gps.png"></a>
    <div class="item">
      <div class="name"><?php echo $rsConfig["Stores_Name"] ?></div>
    </div>
    <div class="item">
      <div class="tel_ico"></div>
      <div class="item_name">电话:</div>
      <div class="tel_number"><a href="tel:<?php echo $rsConfig["CallPhoneNumber"] ?>" target="_self"><?php echo $rsConfig["CallPhoneNumber"] ?></a></div>
    </div>
    <div class="item">
      <div class="address_ico"></div>
      <div class="item_name">地址:</div>
      <div class="address"><?php echo $rsConfig["Stores_Address"] ?></div>
    </div>
    <div class="item">
      <div class="description"><?php echo $rsConfig["Stores_Description"] ?></div>
    </div>
  </div>
</div>
<div id="menu"><a href="#"></a></div>
<div id="menu_cover"></div>
<div id="menu_list">
   <ul>
     <?php
				$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc");
				while($rsColumn=$DB->fetch_assoc()){
					echo '<li><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="'.$rsColumn["Column_ImgPath"].'" /></a><br />'.$rsColumn["Column_Name"].'</li>';
			}?>
   </ul>
</div>
<script type="text/javascript">
    $('#header ul li').eq(0).removeClass('first');
    $('<li class="first">&nbsp;</li>').prependTo('#header ul');
	$('#menu a').click(function(){
		if($('#menu_cover').css('display')=='none'||$('#menu_list').css('display')=='none'){
			$('#menu_cover, #menu_list').slideDown(500);
		}else{
			$('#menu_cover, #menu_list').slideUp(500);
		}
		return false;
	});
	$('#menu_list, #menu_list li, #menu_list li a').click(function(){
		$('#menu_cover, #menu_list').slideUp(500);
	});
</script>
<?php require_once('footer.php');?>