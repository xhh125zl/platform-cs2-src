<?php
$Dwidth = array('640','640','203','203','203','609','640','608','640','608');
$DHeight = array('320','63','121','121','121','93','63','187','63','187');
$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=10;$no++){
	$json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
		"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
		"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
		"Postion"=>$no>9 ? "t".$no : "t0".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>"1"
	);
}
?>
<?php require_once('skin/top.php'); ?>

<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/page.css' rel='stylesheet' type='text/css' />
  <link href='/static/api/shop/skin/<?php echo $rsConfig['Skin_ID'];?>/page_media.css' rel='stylesheet' type='text/css' />
  <link href='/static/js/plugin/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
  <script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script> 
  <script type='text/javascript' src='/static/api/shop/js/index.js?t=<?php echo time();?>'></script> 
  <script language="javascript">
  var shop_skin_data=<?php echo json_encode($json) ?>;
  $(document).ready(index_obj.index_init);
 </script>
  <div id="shop_skin_index">
    <div class="shop_skin_index_list banner" rel="edit-t01">
      <div class="img" ></div>
    </div>
    <div style="background:#FFF; padding:8px 0;">
    <div class="search_box">
      <form action="/api/shop/search.php" method="get">
		<input type="hidden" name="UsersID" value="<?php echo $UsersID;?>" />
        <input type="text" name="kw" class="input" value="" placeholder="输入商品名称..." />
        <input type="submit" class="submit" value="搜索" />
      </form>
    </div>
    </div>
    <div class="shop_skin_index_list h2" rel="edit-t02">
      <div class="text" ></div>
    </div>
    <div class="index-h">
      <div class="shop_skin_index_list items" rel="edit-t03">
        <div class="img" ></div>
      </div>
      <div class="shop_skin_index_list items" rel="edit-t04">
        <div class="img" ></div>
      </div>
      <div class="shop_skin_index_list items" rel="edit-t05">
        <div class="img" ></div>
      </div>
    </div>
    <div class="shop_skin_index_list ad1" rel="edit-t06">
      <div class="img" ></div>
    </div>
    
    
    <div class="shop_skin_index_list h2" rel="edit-t07">
      <div class="text" ></div>
    </div>
    <div class="shop_skin_index_list ad2" rel="edit-t08">
      <div class="img" ></div>
    </div>
      <?php
			$j=0;
			echo '<ul class="products_list">';
			$condition = "where Users_ID='".$UsersID."' and Products_SoldOut=0 and Products_Status=1 and Products_IsHot=1 order by Products_Index asc,Products_ID desc";
			$DB->get("shop_products","*",$condition,3);
			while($item=$DB->fetch_assoc()){
				$JSON=json_decode($item['Products_JSON'],true);
				if($j==0){
					echo '<li class="big right_bor"><a href="/api/'.$UsersID.'/shop/products/'.$item["Products_ID"].'/"><div class="name">'.mb_substr($item["Products_Name"],0,10,'utf-8').'<br /><span class="price">￥'.$item["Products_PriceX"].'</span></div><div class="pic">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</div></a></li>';
				}elseif($j==1){
					echo '<li class="left_bor btm_bor"><a href="/api/'.$UsersID.'/shop/products/'.$item["Products_ID"].'/"><div class="name">'.mb_substr($item["Products_Name"],0,10,'utf-8').'<br /><span class="price">￥'.$item["Products_PriceX"].'</span></div><div class="pic small">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</div></a></li>';
				}else{
					echo '<li class="left_bor top_bor"><a href="/api/'.$UsersID.'/shop/products/'.$item["Products_ID"].'/"><div class="name">'.mb_substr($item["Products_Name"],0,10,'utf-8').'<br /><span class="price">￥'.$item["Products_PriceX"].'</span></div><div class="pic small">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</div></a></li>';
				}
				$j++;
			}
			echo '<div class="clear"></div></ul>';
		?>
    <div class="shop_skin_index_list h2" rel="edit-t09">
      <div class="text" ></div>
    </div>
    <div class="shop_skin_index_list ad2" rel="edit-t10">
      <div class="img" ></div>
    </div>
        <?php
			$j=0;
			echo '<ul class="products_list">';
			$condition = "where Users_ID='".$UsersID."' and Products_SoldOut=0 and Products_Status=1 and Products_IsNew=1 order by Products_Index asc,Products_ID desc";
			$DB->get("shop_products","*",$condition,3);
			while($item=$DB->fetch_assoc()){
				$JSON=json_decode($item['Products_JSON'],true);
				if($j==0){
					echo '<li class="big right_bor"><a href="/api/'.$UsersID.'/shop/products/'.$item["Products_ID"].'/"><div class="name">'.mb_substr($item["Products_Name"],0,10,'utf-8').'<br /><span class="price">￥'.$item["Products_PriceX"].'</span></div><div class="pic">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</div></a></li>';
				}elseif($j==1){
					echo '<li class="left_bor btm_bor"><a href="/api/'.$UsersID.'/shop/products/'.$item["Products_ID"].'/"><div class="name">'.mb_substr($item["Products_Name"],0,10,'utf-8').'<br /><span class="price">￥'.$item["Products_PriceX"].'</span></div><div class="pic small">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</div></a></li>';
				}else{
					echo '<li class="left_bor top_bor"><a href="/api/'.$UsersID.'/shop/products/'.$item["Products_ID"].'/"><div class="name">'.mb_substr($item["Products_Name"],0,10,'utf-8').'<br /><span class="price">￥'.$item["Products_PriceX"].'</span></div><div class="pic small">'.(empty($JSON["ImgPath"])?'暂无图片':'<img data-url="'.$JSON["ImgPath"][0].'" src="/static/js/plugin/lazyload/grey.gif" />').'</div></a></li>';
				}
				$j++;
			}
			echo '<div class="clear"></div></ul>';
		?>
  </div>
</div>
<div class="b15"></div>
<?php require_once('skin/distribute_footer.php'); ?>
<!--懒加载--> 
<script type='text/javascript' src='/static/js/plugin/lazyload/jquery.scrollLoading.js'></script> 
<script language="javascript">
	$("img").scrollLoading();
</script>
</body>
</html>