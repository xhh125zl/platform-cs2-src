<?php
require_once('global.php');

if(!empty($_GET['CategoryID'])){
	$CategoryID = $_GET['CategoryID'];
}else{
	$CategoryID = 0;
}

$order_filter_base = $base_url.'api/shop/category.php?UsersID='.$UsersID.'&CategoryID='.$CategoryID;
$page_url = $base_url.'api/shop/category.php?UsersID='.$UsersID.'&CategoryID='.$CategoryID;

if($owner['id'] != '0'){
	$order_filter_base .= '&OwnerID='.$owner['id'];
	$page_url .= '&OwnerID='.$owner['id'];
}

$rsCategory = array(
	'Category_ID'=>0,
	'Category_Name'=>'全部商品',
	'Category_ListTypeID'=>1
);

if($CategoryID>0){
	$rsCategory=$DB->GetRs("shop_category","*","where Users_ID='".$UsersID."' and Category_ID=".$CategoryID);
	if($rsCategory["Category_ParentID"]>0){
		$rsPCategory=$DB->GetRs("shop_category","*","where Users_ID='".$UsersID."' and Category_ID=".$rsCategory["Category_ParentID"]);
	}
}

$condition = "where Users_ID='".$UsersID."' and Products_SoldOut=0 and Products_Status=1";
if($CategoryID>0){
	$condition .= " and Products_Category like '%,".$CategoryID.",%'";
}

$order_by = !empty($_GET['order_by'])?$_GET['order_by']:'';

if($order_by == 'sales'){
	$condition .= " order by Products_Sales desc,Products_Index asc,Products_ID desc";
}else if($order_by == 'price'){
	$condition .= " order by Products_PriceX asc,Products_Index asc,Products_ID desc";
}else if($order_by == 'comments'){
	$condition .= " order by Products_ID desc";
}else{
	$condition .= " order by Products_Index asc,Products_ID desc";
}

$order_filter_base .= '&order_by=';
$page_url .= '&order_by='.$order_by.'&page=';

//自定义分享
if(!empty($share_config)){
	$share_config["link"] = $shop_url.'category/'.$CategoryID.'/';
	$share_config["title"] = $rsConfig["ShopName"];
	if($owner['id'] != '0' && $rsConfig["Distribute_Customize"]==1){	
		$share_config["desc"] = $owner['shop_announce'] ? $owner['shop_announce'] : $rsConfig["ShareIntro"];
		$share_config["img"] = strpos($owner['shop_logo'],"http://")>-1 ? $owner['shop_logo'] : 'http://'.$_SERVER["HTTP_HOST"].$owner['shop_logo'];
	}else{
		$share_config["desc"] = $rsConfig["ShareIntro"];
		$share_config["img"] = strpos($rsConfig['ShareLogo'],"http://")>-1 ? $rsConfig['ShareLogo'] : 'http://'.$_SERVER["HTTP_HOST"].$rsConfig['ShareLogo'];
	}
	
	//商城分享相关业务
	include("share.php");
}

include("skin/category.php");
?>