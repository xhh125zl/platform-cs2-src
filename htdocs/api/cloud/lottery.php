<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

if(isset($_GET["DetailID"]))
{
	$DetailID = intval($_GET["DetailID"]);
}else{
	echo '缺少必要的参数';
	exit;
}
$rsDetail = $DB->GetRS('cloud_products_detail','*','where Cloud_Detail_ID='.$DetailID);
if(empty($rsDetail)){
	echo '数据不存在！';
	exit;
}
$rsProducts = $DB->GetRS('cloud_products','*','where Users_ID="'.$UsersID.'" and Products_ID='.$rsDetail['Products_ID']);
$JSON = json_decode($rsProducts['Products_JSON'], TRUE);
$rsProducts = handle_product($rsProducts);

$User_Info = unserialize($rsDetail['User_Info']);
$rsUser = $DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$rsDetail['User_ID']);

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

/*获取此商品thumb*/
$ImgPath = get_prodocut_cover_img($rsProducts);

//加入访问记录
$Data=array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"cloud",
	"S_CreateTime"=>time()
);
$DB->Add("statistics",$Data);
//调用模版
$share_link = $cloud_url.'lottery/'.$DetailID.'/';
require_once('../share.php');
$share_title = $rsProducts["Products_Name"];
if($owner['id'] != '0' && $rsConfig["Distribute_Customize"]==1){
	$share_desc = $owner['shop_announce'] ? $owner['shop_announce'] : $rsConfig["ShareIntro"];
	$share_img = strpos($owner['shop_logo'],"http://")>-1 ? $owner['shop_logo'] : 'http://'.$_SERVER["HTTP_HOST"].$owner['shop_logo'];
}else{
	$share_desc = $rsConfig["ShareIntro"];
	$share_img = strpos($rsConfig['ShareLogo'],"http://")>-1 ? $rsConfig['ShareLogo'] : 'http://'.$_SERVER["HTTP_HOST"].$rsConfig['ShareLogo'];
}

//往期数据
$DB->Get('cloud_products_detail','*','where Users_ID="'.$UsersID.'" and Products_ID='.$rsProducts['Products_ID'].' order by Products_End_Time desc limit 0,3');
$cloud_products_detail_list = array();
while($r = $DB->fetch_assoc()){
	$cloud_products_detail_list[] = $r;
}
include("skin/lottery.php");
?>