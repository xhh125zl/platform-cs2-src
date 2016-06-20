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
<div id="plug_menu">
	<div class="bgcolor menu"><span class="close"></span></div>
     <?php $DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc",4);
		$i=1;
		while($rsColumn=$DB->fetch_assoc()){
			echo '<div class="bgcolor plug-btn plug-btn'.$i.' close"><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="'.$rsColumn["Column_ImgPath"].'" align="absmiddle" /></a></div>';
			$i++;
		}
	?>
</div>
<script type="text/javascript">
$(function(){
	$('#plug_menu .menu span').click(function(){
	    if($(this).attr('class')=='open'){
            $(this).removeClass('open').addClass('close');
            $('.plug-btn').removeClass('open').addClass('close');
	    }else{
            $(this).removeClass('close').addClass('open');
            $('.plug-btn').removeClass('close').addClass('open');
	    }
	});
	$('#plug_menu .plug-btn a').click(function(){
		$('#plug_menu .menu span').click();
	});
});
</script>
<div class="blank15"></div>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 0);});</script>

<?php require_once('footer.php');?>