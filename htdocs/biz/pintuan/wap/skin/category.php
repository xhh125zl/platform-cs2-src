<?php

$DB->getPage("shop_products","*",$condition,$pageSize=10);	

$productList = array();
$products = array();
while($rsProducts=$DB->fetch_assoc()){
	 $products[] = $rsProducts; 
}

$product_list = array();
if(count($products)>0){
	$product_list = handle_product_list($products);
}

$order_filter_base = $base_url.'api/shop/category.php?UsersID='.$UsersID.'&CategoryID='.$CategoryID.'&order_by=';

require_once($rsConfig['Skin_ID']."/list.php");