<?php
$Dwidth = array('640','210','210','210','320','320');
$DHeight = array('320','320','320','320','320','320');
$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=6;$no++){
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
 <script type="text/javascript">
	var skin_index_init=function(){
		$('#index-h a.category').click(function(){
			if($('#category').height()>$(window).height()){
				$('html, body, #cover_layer').css({
					height:$('#category').height(),
					width:$(window).width(),
					overflow:'hidden'
				});
			}else{
				$('#category, #cover_layer').css('height', $(window).height());
				$('html, body').css({
					height:$(window).height(),
					overflow:'hidden'
				});
			}
			
			$('#cover_layer').show();
			$('#category').animate({left:'0%'}, 500);
			$('#shop_page_contents').animate({margin:'0 -70% 0 70%'}, 500);
			window.scrollTo(0);
			
			return false;
		});
	}
 </script>
 <div id="shop_skin_index">
    <div class="shop_skin_index_list banner" rel="edit-t01">
		<div class="img"></div>
    </div>
	<div id="index-h">
		<div><a href="<?php echo $shop_url;?>allcategory/" class="category"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/category-1.jpg" /><br />商品分类</a></div>
		<div class="c"><a href="/api/shop/search.php?UsersID=<?php echo $UsersID;?><?php echo $owner['id'] != '0' ? '&OwnerID='.$owner['id'] : '';?>&IsHot=1"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/hot.jpg" /><br />热销商品</a></div>
		<div class="d"><a href="/api/shop/search.php?UsersID=<?php echo $UsersID;?><?php echo $owner['id'] != '0' ? '&OwnerID='.$owner['id'] : '';?>&IsNew=1"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/new.jpg" /><br />新品上市</a></div>
	</div>
	<div class="shop_skin_index_list i0" rel="edit-t02">
        <div class="img"></div>
    </div>
	<div class="shop_skin_index_list i1" rel="edit-t03">
        <div class="img"></div>
    </div>
	<div class="shop_skin_index_list i0" rel="edit-t04">
        <div class="img"></div>
    </div>
	<div class="shop_skin_index_list i2" rel="edit-t05">
		<div class="img"></div>
	</div>
	<div class="shop_skin_index_list i2" rel="edit-t06">
		<div class="img"></div>
	</div>
 </div>
</div>
<?php require_once('skin/distribute_footer.php'); ?>
</body>
</html>