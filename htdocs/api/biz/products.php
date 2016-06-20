<?php
require_once('global.php');
if(isset($_GET["CategoryID"])){
	$CategoryID=$_GET["CategoryID"];
}else{
	$CategoryID = 0;
}

$page_url = $order_filter_base = $base_url.'api/biz/products.php?UsersID='.$UsersID.'&BizID='.$BizID.'&CategoryID='.$CategoryID;
if($owner['id'] != '0'){
	$page_url .= '&OwnerID='.$owner['id'];
	$order_filter_base .= '&OwnerID='.$owner['id'];
};
$page_url .= '&page=';
$header_title = "全部商品";
$order_by = !empty($_GET['order_by'])?$_GET['order_by']:'';

$condition = "where Users_ID='".$UsersID."' and Biz_ID=".$BizID." and Products_Status=1 and Products_SoldOut=0";
if($CategoryID>0){
	$item = $DB->GetRs("biz_category","Category_Name","where Users_ID='".$UsersID."' and Biz_ID=".$BizID." and Category_ID=".$CategoryID);
	if($item){
		$cates = array();
		$cates[] = $CategoryID;
		$DB->Get("biz_category","Category_ID","where Users_ID='".$UsersID."' and Biz_ID=".$BizID." and Category_ParentID=".$CategoryID);
		while($r = $DB->fetch_assoc()){
			$cates[] = $r["Category_ID"];
		}
		$condition .= " and Products_BizCategory in(".(implode(",",$cates)).")";
		$header_title = $item["Category_Name"];
	}
}

if($order_by == 'sales'){
	$condition .= " order by Products_Sales desc";
}else if($order_by == 'price'){
	$condition .= " order by Products_PriceX asc";
}else if($order_by == 'comments'){
	$condition .= " order by Products_CreateTime desc";
}else{
	$condition .= " order by Products_ID desc";
}

$order_filter_base .= '&order_by=';
$page_url .= '&order_by='.$order_by.'&page=';
include($rsBiz['Skin_ID']."/products.php");
?>