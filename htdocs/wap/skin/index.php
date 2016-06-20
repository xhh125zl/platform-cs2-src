<?php require_once('top.php'); ?>
<body>
<link href='/wap/css/page.css' rel='stylesheet' type='text/css' />
<link href='/wap/css/page_media.css' rel='stylesheet' type='text/css' />
<link href='/wap/js/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/wap/js/flexslider/flexslider.js'></script>
<script type='text/javascript' src='/wap/js/toast.js'></script>
<script type='text/javascript' src='/wap/js/index.js'></script>
<script type="text/javascript">
  var shop_skin_data=<?php echo json_encode($Slide);?>;
  $(document).ready(index_obj.index_init);
</script>

<div id="shop_page_contents">
	<div id="cover_layer"></div>
    <!--header-->
 	<div id="header_index">
  		<div class="logo"><img src="<?php echo $SiteLogo;?>" /></div>
  		<div class="remark">全国领先的分销平台</div>
  		<div class="clear"></div>
 	</div>
    <!--banner-->
 	<div id="shop_skin_index">
    	<div class="shop_skin_index_list banner" rel="edit-t01">
			<div class="img"></div>
    	</div>
        <div class="menu">
            <ul>
                <li><a href="/wap/allcategory.php?categoryID=0"><img src="images/c0.png"><p>全部分类</p></a></li>
                <?php
                	foreach($catelist as $c){
				?>
                <li><a href="/wap/allcategory.php?categoryID=<?php echo $c["id"];?>"><img src="<?php echo $c["logo"];?>" /><p><?php echo $c["name"];?></p></a></li>
                <?php
					}
				?>
				<div class="clear"></div>
            </ul>
            <div class="clear"></div>
    	</div>
        <div class="search">
            <form action="/wap/search.php" method="get">
                <input type="text" name="kw" class="input" value="" placeholder="输入商品名称..." />
                <input type="submit" class="submit" value=" " />
            </form>
       </div>
 	</div>
    <!--lists-->
    <?php foreach($products_list as $key=>$cate){?>
	<?php if(!empty($cate['products'])){?>
    	<div class="index_prolist">
    	<h1><span><a class="more" href="/wap/allcategory.php?categoryID=<?php echo $cate['catid'];?>">更多&gt;&gt;</a></span>
		<a href="/wap/allcategory.php?categoryID=<?php echo $cate['catid'];?>"><?php echo $cate['catname'];?></a></h1>
    	<div class="prolist">
		<?php
        foreach($cate['products'] as $k=>$item){
			$JSON=json_decode($item['Products_JSON'],true);
			$item["thumb"] = empty($JSON["ImgPath"])?'':$JSON["ImgPath"][0];
	    ?>
        	<?php if($k < 2){?>
			<div class="item">
            	<ul>
              		<li class="img"><a href="/api/<?php echo $item["Users_ID"];?>/shop/products/<?php echo $item['Products_ID'];?>/"><img src="<?php echo $item['thumb'];?>" /></a></li>
              		<li class="name"><a href="/api/<?php echo $item["Users_ID"];?>/shop/products/<?=$item['Products_ID']?>/"><?php echo $item['Products_Name'];?></a></li>
                    <li class="price">
                    价格：<?php echo $item['Products_PriceX'];?></li>
            	</ul>
         	 </div> 
			 <?php }?>
        <?php }?>
         
        <div class="clear"></div>       
      </div>
    </div>
	<?php }?>
	<?php }?>
    <a href="sjrz.php"><img src="images/sjrz.jpg" style="width:98%; display:block; margin:8px auto;" /></a>
</div>
<?php
require_once("footer.php");
?>
</body>
</html>
