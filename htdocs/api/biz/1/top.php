<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $header_title;?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<link href='/static/api/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/biz/<?php echo $rsBiz["Skin_ID"]?>/style.css?t=<?php echo time() ?>' rel='stylesheet' type='text/css' />
</head>
<body>
 <div id="header_biz">
  <a class="back"></a><a class="more"></a><?php echo $header_title;?>
  <ul><?php //PRINT_R($_SERVER['HTTP_HOST'])?>
   <li><a href="<?php echo $shop_url;?>" class="shop_home">商城首页</a></li>
    <li><a href="/api/<?php echo $_GET['UsersID'];?>/distribute/" class="shop_dis">分销中心</a></li>
   <li><a href="/api/<?php echo $UsersID?>/shop/cart/" class="shop_cart">购物车</a></li>
   <li class="last"><a href="<?php echo $shop_url;?>member/" class="shop_member">个人中心</a></li>
  </ul>
 </div>