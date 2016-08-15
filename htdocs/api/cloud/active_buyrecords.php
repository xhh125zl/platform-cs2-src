<?php
//正在进行的产品参与记录情况
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

if(isset($_GET["ProductsID"]))
{
	$ProductsID = $_GET["ProductsID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$rsProducts = $DB->GetRS('cloud_products','*','where Users_ID="'.$UsersID.'" and Products_ID='.$ProductsID.' and Products_SoldOut=0 and ROUND(canyurenshu/zongrenci,2)<1');
$JSON = json_decode($rsProducts['Products_JSON'], TRUE);
$rsProducts = handle_product($rsProducts);

$UserID = 0;
$Is_Distribute = 0;  //用户是否为分销会员

if(!empty($_SESSION[$UsersID."User_ID"])){
	$UserID = $_SESSION[$UsersID."User_ID"];
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	$Is_Distribute = $userexit['Is_Distribute'];
	
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
		$UserID = 0;
	}
}
//用户没有登录
if(empty($_SESSION[$UsersID."User_ID"])){
	if(empty($_GET['myself'])){
        $_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/cloud/active_buyrecords/".$ProductsID.'/';
	}else{
		$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/cloud/active_buyrecords/".$ProductsID."/myself/1/";
	}
	header("location:/api/".$UsersID."/user/login/");
}

if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
}

//加入访问记录
$Data=array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"cloud",
	"S_CreateTime"=>time()
);
$DB->Add("statistics",$Data);
//调用模版
$share_link = $cloud_url.'active_buyrecords/'.$ProductsID.'/';
require_once('../share.php');
$share_title = $rsProducts["Products_Name"];
if($owner['id'] != '0' && $rsConfig["Distribute_Customize"]==1){
	$share_desc = $owner['shop_announce'] ? $owner['shop_announce'] : $rsConfig["ShareIntro"];
	$share_img = strpos($owner['shop_logo'],"http://")>-1 ? $owner['shop_logo'] : 'http://'.$_SERVER["HTTP_HOST"].$owner['shop_logo'];
}else{
	$share_desc = $rsConfig["ShareIntro"];
	$share_img = strpos($rsConfig['ShareLogo'],"http://")>-1 ? $rsConfig['ShareLogo'] : 'http://'.$_SERVER["HTTP_HOST"].$rsConfig['ShareLogo'];
}
//记录列表
if(empty($_GET['myself'])){
	$DB->query('SELECT o.Order_ID,o.Order_CreateTime,r.Cloud_Code,u.User_NickName,u.User_HeadImg,r.qishu FROM user_order o RIGHT JOIN cloud_record r ON o.Order_ID = r.Order_ID LEFT JOIN user u ON r.User_ID = u.User_ID WHERE r.Products_ID='.$rsProducts['Products_ID']);
}else{
	$DB->query('SELECT o.Order_ID,o.Order_CreateTime,r.Cloud_Code,u.User_NickName,u.User_HeadImg,r.qishu FROM user_order o RIGHT JOIN cloud_record r ON o.Order_ID = r.Order_ID LEFT JOIN user u ON r.User_ID = u.User_ID WHERE r.Products_ID='.$rsProducts['Products_ID'].' and r.User_ID='.$_SESSION[$UsersID."User_ID"]);
}
$records = array();
while($rs = $DB->fetch_assoc()){	
	$records[$rs['Order_ID']][] = $rs;	
}
include("skin/active_buyrecords.php");
?>