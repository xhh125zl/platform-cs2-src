<?php require_once('top.php'); ?>
<style>
#description div img{
	width:100%;
}
</style>
</head>

<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/description.css' rel='stylesheet' type='text/css' />
  <div class="description_title"><a href="<?php echo $shop_url;?>products/<?php echo $ProductsID;?>/"></a>商品详情</div>
  <div id="description">
    <div class="contents"><?php echo $rsProducts["Products_Description"] ?></div>
  </div>
</div>
<?php require_once('distribute_footer.php'); ?>
</body>
</html>