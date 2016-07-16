<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

$shop_url = '/wap/search.php?search=1';
$condition = "where Products_SoldOut=0";

if(!empty($_GET["kw"])){
	$kw = $_GET["kw"];
	$condition .= " and Products_Name like '%".$_GET["kw"]."%'";
	$shop_url .= "&kw=".$_GET["kw"];
}

$order_by = !empty($_GET['order_by'])?$_GET['order_by']:'sales';

if($order_by == 'sales'){
	$condition .= " order by Products_Sales desc";
}else if($order_by == 'price'){
	$condition .= " order by Products_PriceX asc";
}else if($order_by == 'comments'){
	$condition .= " order by Products_CreateTime desc";
}
$shop_url .= "&order_by=";

$DB->getPage("shop_products","*",$condition,$pageSize=10);
$products = array();
while($rsProducts=$DB->fetch_assoc()){
	$JSON=json_decode($rsProducts['Products_JSON'],true);
	$rsProducts["thumb"] = empty($JSON["ImgPath"])?'':$JSON["ImgPath"][0];
	$products[] = $rsProducts; 
}
//调用模版
include("skin/list.php");
?>