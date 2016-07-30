<?php
$Dwidth = array('640','322','296','296','600','600','600');
$DHeight = array('315','108','240','240','200','200','200');
$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=7;$no++){
	$json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
		"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
		"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
		"Postion"=>"t0".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>"1"
	);
}
?>
<?php require_once('skin/top.php'); ?>

<body>
<?php
ad($UsersID, 1, 1);
?>
<div id="shop_page_contents">
 <div id="cover_layer"></div>
 <link href='/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/page.css' rel='stylesheet' type='text/css' />
 <link href='/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/page_media.css' rel='stylesheet' type='text/css' />
 <link href='/static/js/plugin/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
 <script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script>
 <script type='text/javascript' src='/static/api/shop/js/index.js'></script>
 <script language="javascript">
  var shop_skin_data=<?php echo json_encode($json) ?>;
  $(document).ready(index_obj.index_init);
 </script>
 <div id="shop_skin_index">
	<div class="shop_skin_index_list logo" rel="edit-t02">
		<div class="img" ></div>
    </div>
    <div class="search_box">
        <?php require_once('skin/search_in.php'); ?>
     </div>
     <?php require_once("skin/activelist.php"); ?>
	<div class="shop_skin_index_list banner" rel="edit-t01">
		<div class="img" ></div>
    </div>
	<div class="index-h">
		<div class="items"><a href="/api/<?php echo $UsersID ?>/shop/cart/"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/cart_icon.png" /></a></div>
		<div class="items"><a href="<?php echo $shop_url;?>allcategory/"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/products_icon.png" /></a></div>
		<div class="items"><a href="/api/shop/search.php?UsersID=<?php echo $UsersID;?><?php echo $owner['id'] != '0' ? '&OwnerID='.$owner['id'] : '';?>&IsNew=1"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/gift_icon.png" /></a></div>
        <div class="items"><a href="<?php echo $shop_url;?>member/"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/member_icon.png" /></a></div>
	</div>
	<div class="ind_wrap">
    	<div class="ind_one_box">
            <div class="lbar fl">
                <div class="shop_skin_index_list" rel="edit-t03"><div class="img"></div></div>
            </div>
            <div class="rbar fr">
                <div class="shop_skin_index_list" rel="edit-t04"><div class="img"></div></div>
            </div>
            <div class="clear"></div>
   		</div>
        <div class="ad_items"><div class="shop_skin_index_list" rel="edit-t05"><div class="img"></div></div></div>
        <div class="ad_items"><div class="shop_skin_index_list" rel="edit-t06"><div class="img"></div></div></div>
        <div class="ad_items"><div class="shop_skin_index_list" rel="edit-t07"><div class="img"></div></div></div>
    </div>
 </div>
</div>
<?php require_once('skin/distribute_footer.php'); ?>
</body>
</html>