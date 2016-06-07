<?php
$Dwidth = array('640','0','0','0','0');
$DHeight = array('1010','0','0','0','0');
$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=5;$no++){
	$json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
		"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
		"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
		"Postion"=>"t0".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>1
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
 <script type="text/javascript">
	var skin_index_init=function(){
		$('#shop_skin_index .banner *').not('img').height($(window).height());
		var sUserAgent = navigator.userAgent.toLowerCase();  
		var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";  
		var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";  
		var bIsMidp = sUserAgent.match(/midp/i) == "midp";  
		var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";  
		var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";  
		var bIsAndroid = sUserAgent.match(/android/i) == "android";  
		var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";  
		var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";  
		if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM){
			$('#index_m').css('position','fixed');
		} 
		$('#index_m').show();
	};
 </script>
 <div id="shop_skin_index">
   <div class="shop_skin_index_list banner" rel="edit-t01">
        <div class="img"></div>
    </div>
    <div id="index_m">
    	<div class="bg"></div>
        <div class="cont">
        	<div class="shop_skin_index_list" rel="edit-t02"><div class="text"></div></div>
            <div>|</div>
            <div class="shop_skin_index_list" rel="edit-t03"><div class="text"></div></div>
            <div>|</div>
            <div class="shop_skin_index_list" rel="edit-t04"><div class="text"></div></div>
            <div>|</div>
            <div class="shop_skin_index_list" rel="edit-t05"><div class="text"></div></div>
        </div>
	</div>
 </div>
</div>
<?php require_once('skin/distribute_footer.php'); ?>
</body>
</html>