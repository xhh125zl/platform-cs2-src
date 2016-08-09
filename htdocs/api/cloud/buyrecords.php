<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

if(isset($_GET["DetailID"]))
{
	$DetailID = $_GET["DetailID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$rsDetail = $DB->GetRS('cloud_products_detail','*','where Cloud_Detail_ID='.$DetailID);
$rsProducts = $DB->GetRS('cloud_products','*','where Users_ID="'.$UsersID.'" and Products_ID='.$rsDetail['Products_ID']);
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
$share_link = $cloud_url.'buyrecords/'.$DetailID.'/';
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
$DB->query('SELECT o.Order_ID,o.Order_CreateTime,o.Order_CartList,r.Cloud_Code,r.User_ID,u.User_NickName,u.User_HeadImg FROM user_order o RIGHT JOIN cloud_record r ON o.Order_ID = r.Order_ID LEFT JOIN user u ON r.User_ID = u.User_ID WHERE r.Products_ID='.$rsProducts['Products_ID'].' and r.qishu='.$rsDetail['qishu']);
$records = array();
while($rs = $DB->fetch_assoc()){	
	$records[$rs['Order_ID']][] = $rs;	
}
include("skin/buyrecords.php");
?>