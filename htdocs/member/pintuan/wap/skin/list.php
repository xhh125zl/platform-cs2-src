<?php require_once('top.php'); ?>
<body>
<script type='text/javascript' src='/wap/js/toast.js'></script>
<link href='/wap/css/products.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<div id="shop_page_contents">
	<div id="cover_layer"></div>
    <!--header-->
 	<div id="header_common">
  		<div class="remark"><span onClick="history.go(-1);"></span>商品列表</div>
  		<div class="clear"></div>
 	</div>
    <!--banner-->
     <div class="search">
            <form action="/wap/search.php" method="get">
                <input type="text" name="kw" class="input" value="<?php echo empty($kw) ? '' : $kw;?>" placeholder="输入商品名称..." />
                <input type="submit" class="submit" value=" " />
            </form>
     </div>
    <!--lists-->
    <div class="list_sorts">
    	<?php
		require_once("skin/order_filter.php");
		?>
    </div>
    <?php if(!empty($kw)):?>
    <div class="lists_title">搜索<span><?=$kw?></span>找到相关结果：</div>
	<?php endif;?>
    <?php if(count($products)>0):?>
    <div id="products">
    	<div class="list-0">
	    <?php foreach($products as $key=>$product):?>
        	<div class="item">
				<div class="img"><a href="/api/<?php echo $product["Users_ID"];?>/shop/products/<?=$product['Products_ID']?>/"><img src="<?=$product['thumb']?>" /></a></div>
				<div class="info">
					<h1><a href="/api/<?php echo $product["Users_ID"];?>/shop/products/<?=$product['Products_ID']?>/"><?=$product['Products_Name']?></a></h1>
					<h2>&yen;<?=$product['Products_PriceX']?></h2>
					<h3>&yen;<?=$product['Products_PriceY']?></h3>
				</div>
			</div>
         <?php endforeach;?>
        </div>
	</div>
    <?php else:?>
    <div class="nodata">没有符合条件的商品</div>
    <?php endif;?>
    
    <?php
	   //确定是category.php,还是search.php
       if(isset($catid)){
	   	 $page_url = '/wap/category.php?catid='.$catid;
	   }else{
	     $page_url = '/wap/search.php?kw='.$_GET['kw'];
	   }
	   
	   //如果存在order_by,则将order_by参数加上
	   if(!empty($_GET['order_by'])){
		 $page_url .= '&order_by='.$_GET['order_by'];
	   }
	   
	   //最后加上页码参数
	   $page_url .=  '&page=';
	?>
    
    <?php $DB->showWechatPage1($page_url); ?>
	
    
</div>
<div id="footer_points"></div>
<!--页脚导航 begin-->
<?php
require_once("footer.php");
?>
<!--页脚导航 end-->
</body>
</html>
