<?php 
require_once('global.php');

$Select_Cat_ID = !empty($_GET['categoryID'])?$_GET['categoryID']:0;
$CategoryList = array();
$DB->get("shop_category","Category_Name,Category_ID,Category_ParentID","where Users_ID='".$UsersID."' order by Category_ParentID asc,Category_Index asc,Category_ID asc");
while($rsCategory=$DB->fetch_assoc()){
	if($rsCategory["Category_ParentID"] != $rsCategory["Category_ID"]){
		if($rsCategory["Category_ParentID"]==0){
			$CategoryList[$rsCategory["Category_ID"]] = $rsCategory;
		}else{
			$CategoryList[$rsCategory["Category_ParentID"]]["child"][] = $rsCategory;
		}
	}
}

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

include("skin/allcategory.php");
?>