<?php
//关键字是
$kw = isset($_GET['kw'])?$_GET['kw']:'';

$order_by = !empty($_GET['order_by'])?$_GET['order_by']:'sales';

if($order_by == 'sales'){
	$condition .= " order by Products_Sales desc";
}else if($order_by == 'price'){
	$condition .= " order by Products_PriceX asc";
}else if($order_by == 'comments'){
	$condition .= " order by Products_CreateTime desc";
}


$DB->getPage("shop_products","*",$condition,$pageSize=10);
$productList = array();
$products = array();

while($rsProducts=$DB->fetch_assoc()){
	 $products[] = $rsProducts; 
}

$product_list = array();

/*如果没有搜索到任何商品，将此次搜索记录下来*/
$num = intval(count($products));
if($num == 0){
	$data = array('Record_Kw'=>$_GET['kw'],
				  'Record_Time'=>time(),
				  'Users_ID'=>$UsersID);
	$DB->Add('shop_search_record',$data);
}

if(count($products)>0){
	$product_list = handle_product_list($products);
}



$order_filter_base = $base_url.'api/shop/search.php?UsersID='.$UsersID.'&kw='.$kw.'&order_by=';

require_once($rsConfig['Skin_ID']."/list.php");
	
