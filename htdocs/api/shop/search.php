<?php
require_once('global.php');

$order_filter_base = $base_url.'api/shop/search.php?UsersID='.$UsersID;
$page_url = $base_url.'api/shop/search.php?UsersID='.$UsersID;

if($owner['id'] != '0'){
	$order_filter_base .= '&OwnerID='.$owner['id'];
	$page_url .= '&OwnerID='.$owner['id'];
}

$condition = "where Users_ID='".$UsersID."' and Products_SoldOut=0 and Products_Status=1";

$position = '';
if(!empty($_GET["IsHot"])){
	$position = " &gt; 热卖商品";
	$condition .= " and Products_IsHot=1";
	$order_filter_base .= "&IsHot=1";
	$page_url .= "&IsHot=1";
}
if(!empty($_GET["IsNew"])){
	$position = " &gt; 最新商品";
	$condition .= " and Products_IsNew=1";
	$order_filter_base .= "&IsNew=1";
	$page_url .= "&IsNew=1";
}
if(!empty($_GET["kw"])){
	$position = ' &gt; 商品搜索 “<font style="color:#ff0000">'.$_GET["kw"].'</font>”';
	$condition .= " and Products_Name like '%".$_GET["kw"]."%'";
	$order_filter_base .= "&kw=".$_GET["kw"];
	$page_url .= "&kw=".$_GET["kw"];
}


$order_by = !empty($_GET['order_by'])?$_GET['order_by']:'sales';

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
	$share_config["link"] = $shop_url;
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

include("skin/search.php");
?>