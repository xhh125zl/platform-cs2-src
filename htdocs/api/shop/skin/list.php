<?php require_once('top.php'); ?>

<body>
<script type='text/javascript' src='/static/api/shop/js/toast.js'></script>
<link href="/static/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="/static/css/font-awesome.css">
<link href="/static/api/shop/skin/24/css/style.css" rel="stylesheet">
<link href='/static/api/shop/skin/24/css/products.css' rel='stylesheet' type='text/css' />
<script type="text/javascript">
	var base_url = '<?=$base_url?>';
	var Users_ID = '<?=$UsersID?>';
</script>
<div id="wrap">
	<div id="cover_layer"></div>
     <!-- 导航栏begin -->
	<?php require_once('lbi/24/header.php');?>     	
    <!-- 导航栏end -->
    
 	 
    <!--lists begin -->
    <div class="list_sorts">
    	<?php
		require_once($rsConfig['Skin_ID']."/order_filter.php");
		?>
    </div>
    
	<?php if(isset($kw)>0):?>
    <div class="lists_title">搜索<span><?=$kw?></span>找到相关结果：</div>
	<?php endif;?>
    <?php if(count($product_list)>0):?>
    <div id="products">
    	<div class="list-0">
	    <?php foreach($product_list as $key=>$product):?>
        	<div class="item">
				<div class="img"><a href="<?=$shop_url?>products/<?=$product['Products_ID']?>/"><img src="<?=$product['ImgPath']?>" /></a></div>
				<div class="info">
					<h1><a href="<?=$shop_url?>products/<?=$product['Products_ID']?>/"><?=$product['Products_Name']?></a></h1>
					<h4>&yen;<?=$product['Products_PriceX']?></h4>
					<h5>&yen;<?=$product['Products_PriceY']?></h5>
				</div>
				<div class="detail">     <span class="addToCart fa fa-cart-plus" ProductID="<?=$product['Products_ID']?>"></span></div>
			</div>
         <?php endforeach;?>
        </div>
	</div>
    <?php else:?>
    没有符合条件的商品
    <?php endif;?>
    
    <?php
	   //确定是category.php,还是search.php
       if(isset($CategoryID)){
	   	 $page_url = '/api/shop/category.php?UsersID='.$UsersID.'&CategoryID='.$CategoryID;
	   }else{
	     $page_url = '/api/shop/search.php?UsersID='.$UsersID.'&kw='.$_GET['kw'];
	   }
	   
	   //如果存在order_by,则将order_by参数加上
	   if(!empty($_GET['order_by'])){
		 $page_url .= '&order_by='.$_GET['order_by'];  
	   }
	   
	   //最后加上页码参数
	   $page_url .=  '&page=';
	?>
    
    <?php $DB->showWechatPage1($page_url); ?>
	
    <!--lists end -->
    
</div>
 <?php require_once('distribute_footer.php');?>    