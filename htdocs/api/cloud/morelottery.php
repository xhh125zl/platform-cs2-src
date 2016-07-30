<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
ini_set("display_errors","On");
if(isset($_GET["UsersID"]))
{
	$UsersID=$_GET["UsersID"];
}else
{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

if(isset($_GET["ProductsID"])) {
	$ProductsID = $_GET["ProductsID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$rsProducts = $DB->GetRS('cloud_products','*','where Users_ID="'.$UsersID.'" and Products_ID='.$ProductsID);
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

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

$owner = get_owner($rsConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);


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
$share_link = $cloud_url.'Morelottery/'.$ProductsID.'/';
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
$DB->query('SELECT r.Cloud_Detail_ID,r.qishu,r.Products_End_Time,r.Luck_Sn,r.User_Info,r.Result,u.User_NickName,u.User_HeadImg,p.Products_Name FROM cloud_products p RIGHT JOIN cloud_products_detail r ON r.Products_ID = p.Products_ID LEFT JOIN user u ON r.User_ID = u.User_ID WHERE r.Products_ID='.$ProductsID);
$cloud_products_detail_list = array();
while($rs = $DB->fetch_assoc()){		
	$cloud_products_detail_list[] = $rs;	
}
include("skin/morelottery.php");
?>