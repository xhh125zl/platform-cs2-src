<?php
$Dwidth = array('605','250');
$DHeight = array('205','60');
$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=2;$no++){
	$json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
		"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
		"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
		"Postion"=>"t0".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>$no==1?"1":"0"
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
	$(function(){		
		$('.search').click(function(){
			if($('.search_box').is(':hidden')){
				$('.search_box').show();
				$('.head_menu').hide();
			}
		});
	})
 </script>
 <div id="shop_skin_index">
	<div id="index_header">
		<div class="lbar fl">
			<div class="shop_skin_index_list logo" rel="edit-t02">
				<div class="img"></div>
   			 </div>
		</div>
		<div class="rbar fr">
			<div class="head_menu">
				<a href="/api/<?php echo $UsersID ?>/shop/cart/" class="cart"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/cart_icon.png" /></a>
				<a href="<?php echo $shop_url;?>allcategory/" class="cate" name="show_cate"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/cate_list.png" /></a>
				<a href="javascript:void(0)" class="search"><img src="/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/search_btn.png" /></a>
			</div>
			<div class="search_box">
              <?php require_once('skin/search_in.php'); ?>
            </div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="shop_skin_index_list banner" rel="edit-t01">
		<div class="img"></div>
    </div>
    <?php
		$i=0;
		$DB->get("shop_category","Category_Name,Category_ID","where Users_ID='".$UsersID."' and Category_ParentID=0 order by Category_Index asc");
		$bg_array = array('bg_blue','bg_ff8b3e','bg_78c92e');
		$Category_List = array();
		while($r=$DB->fetch_assoc()){
			$Category_List[] = $r;
		}
		foreach($Category_List as $rscategory){
			$bg = $bg_array[$i];
			echo '<div class="products_cont"><ul><li class="column '.$bg.'"><a href="'.$shop_url.'category/'.$rscategory["Category_ID"].'/">'.$rscategory["Category_Name"].'</a></li>';
			
			
			$condition = "where Users_ID='".$UsersID."' and Products_SoldOut=0 and Products_Status=1";
			
			$condition .= " and Products_Category like '%,".$rscategory["Category_ID"].",%' order by Products_Index asc,Products_ID desc";
			$j=0;
			$DB->get("shop_products","*",$condition,4);
			while($item=$DB->fetch_assoc()){
				$JSON=json_decode($item['Products_JSON'],true);
				if($j==1){
					echo '<li class="big mar_b"><div><a href="'.$shop_url.'products/'.$item["Products_ID"].'/">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</a></div><div class="price"><div class="bg"></div><div class="cont">'.$item["Products_PriceX"].'</div></div></li>';
				}else{
					echo '<li class="mar_lb"><div><a href="'.$shop_url.'products/'.$item["Products_ID"].'/">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</a></div><div class="price"><div class="bg"></div><div class="cont">'.$item["Products_PriceX"].'</div></div></li>';
				}
				$j++;
			}
			
			echo '</ul><div class="clear"></div></div>';
		   if($i>=2){
			   $i=0;
		   }else{
			   $i++;
		   }		   
		}
?>
 </div>
</div>
<?php require_once('skin/distribute_footer.php'); ?>
<!--懒加载--> 
<script type='text/javascript' src='/static/js/plugin/lazyload/jquery.scrollLoading.js'></script> 
<script>
    $("img").scrollLoading();
</script>
</body>
</html>