<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

$base_url = base_url();
$shop_url = shop_url();
if(isset($_GET["UsersID"]))
{
	$UsersID=$_GET["UsersID"];
}else
{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["ProductsID"]))
{
	$ProductsID=$_GET["ProductsID"];
}else
{
	echo '缺少必要的参数';
	exit;
}
$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$owner = get_owner($rsConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);
if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$shop_url = $shop_url.$owner['id'].'/';
};

$rsProducts=$DB->GetRs("shop_products","*","where Users_ID='".$UsersID."' and Products_ID=".$ProductsID);
$rsProducts["Products_Description"] = str_replace('&quot;','"',$rsProducts["Products_Description"]);
$rsProducts["Products_Description"] = str_replace("&quot;","'",$rsProducts["Products_Description"]);
$rsProducts["Products_Description"] = str_replace('&gt;','>',$rsProducts["Products_Description"]);
$rsProducts["Products_Description"] = str_replace('&lt;','<',$rsProducts["Products_Description"]);
$JSON=json_decode($rsProducts['Products_JSON'],true);
//加入访问记录
$Data=array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"shop",
	"S_CreateTime"=>time()
);
$DB->Add("statistics",$Data);
//调用模版
$share_link = $shop_url.'description/'.$ProductsID.'/';
require_once('../share.php');
$share_title = $rsProducts["Products_Name"];
$share_desc = $rsProducts["Products_BriefDescription"] ? str_replace(array("\r\n", "\r", "\n"), "", $rsProducts["Products_BriefDescription"]) : $rsProducts["Products_Name"];
$share_img = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/shop.jpg';
if(isset($JSON["ImgPath"])){
	$share_img = 'http://'.$_SERVER["HTTP_HOST"].$JSON["ImgPath"][0];
}
$C = $DB->GetRS("users","Users_Logo","where Users_ID='".$UsersID."'");
include($rsConfig['Skin_ID']."/description.php");
?>