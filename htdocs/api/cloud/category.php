<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

if(isset($_GET['CategoryID'])){
	$CategoryID = $_GET['CategoryID'];
	
}else{
	echo '缺少分类ID';
	exit();
}

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}

if(empty($CategoryID)){
	$rsCategory = $DB->GetRs("cloud_category","*","where Users_ID='".$UsersID."'");
}else{
	$rsCategory = $DB->GetRs("cloud_category","*","where Users_ID='".$UsersID."' and Category_ID=".$CategoryID);
}

if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
}
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

if($rsCategory["Category_ParentID"]>0){
	$rsPCategory=$DB->GetRs("cloud_category","*","where Users_ID='".$UsersID."' and Category_ID=".$rsCategory["Category_ParentID"]);
}
$condition = "where Users_ID='".$UsersID."' and Products_SoldOut=0";

//加入访问记录
$Data=array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"cloud",
	"S_CreateTime"=>time()
);
$DB->Add("statistics",$Data);

//调用模版
$share_link = $cloud_url.'category/'.$CategoryID.'/';
require_once('../share.php');
if($owner['id'] != '0' && $rsConfig["Distribute_Customize"]==1){
	$share_desc = $owner['shop_announce'] ? $owner['shop_announce'] : $rsConfig["ShareIntro"];
	$share_img = strpos($owner['shop_logo'],"http://")>-1 ? $owner['shop_logo'] : 'http://'.$_SERVER["HTTP_HOST"].$owner['shop_logo'];
}else{
	$share_desc = $rsConfig["ShareIntro"];
	$share_img = strpos($rsConfig['ShareLogo'],"http://")>-1 ? $rsConfig['ShareLogo'] : 'http://'.$_SERVER["HTTP_HOST"].$rsConfig['ShareLogo'];
}

$C = $DB->GetRS("users","Users_Logo","where Users_ID='".$UsersID."'");
include("skin/category.php");
?>