<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["catid"])){
	$catid=$_GET["catid"];
}else{
	echo '缺少必要的参数';
	exit;
}

$condition = "where Products_SoldOut=0";
$companys = get_company($catid);

if($companys){
	$condition .= " and Users_ID in(".$companys.")";
}else{
	$condition .= " and Users_ID in('0')";
}

$order_by = !empty($_GET['order_by'])?$_GET['order_by']:'sales';

if($order_by == 'sales'){
	$condition .= " order by Products_Sales desc";
}else if($order_by == 'price'){
	$condition .= " order by Products_PriceX asc";
}else if($order_by == 'comments'){
	$condition .= " order by Products_CreateTime desc";
}

$shop_url = '/wap/category.php?catid='.$catid.'&order_by=';

$DB->getPage("shop_products","*",$condition,$pageSize=10);

$products = array();
while($rsProducts=$DB->fetch_assoc()){
	$JSON=json_decode($rsProducts['Products_JSON'],true);
	$rsProducts["thumb"] = empty($JSON["ImgPath"])?'':$JSON["ImgPath"][0];
	$products[] = $rsProducts; 
}

function get_company($catid){
	global $DB1;
	$catids = array();
	$companys = "";
	$catids[] = $catid;
	$DB1->get("industry","*","where parentid=".$catid);
	while($r = $DB1->fetch_assoc()){
		$catids[] = $r["id"];
	}
	$DB1->get("users","Users_ID","where Users_Industry in(".(implode(",",$catids)).")");
	while($r = $DB1->fetch_assoc()){
		$companys .= ",'".$r["Users_ID"]."'";
	}
	return $companys ? substr($companys,1) : '';	
}
include("skin/list.php");
?>