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

if(isset($_GET["ProductsID"]))
{
	$ProductsID = $_GET["ProductsID"];
}else{
	echo '缺少必要的参数';
	exit;
}

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

$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
//获取此产品
$rsProducts = $DB->GetRS('cloud_products','*','where Users_ID="'.$UsersID.'" and Products_ID='.$ProductsID);

$JSON = json_decode($rsProducts['Products_JSON'], TRUE);

$rsProducts = handle_product($rsProducts);

//产品详情
$rsProducts["Products_Description"] = str_replace('&quot;','"',$rsProducts["Products_Description"]);
$rsProducts["Products_Description"] = str_replace('&quot;','"',$rsProducts["Products_Description"]);
$rsProducts["Products_Description"] = str_replace("&quot;","'",$rsProducts["Products_Description"]);
$rsProducts["Products_Description"] = str_replace('&gt;','>',$rsProducts["Products_Description"]);
$rsProducts["Products_Description"] = str_replace('&lt;','<',$rsProducts["Products_Description"]);

/*若用户已经登陆，判断此商品是否被当前登陆用户收藏*/
$rsProducts['Products_IsFavourite'] = 0;

if(!empty($_SESSION[$UsersID."User_ID"])){
	$rsUser = $DB->GetRs("user","User_Level","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
	$rsFavourites = $DB->getRs('user_favourite_products',"Products_ID","where User_ID='".$_SESSION[$UsersID."User_ID"]."' and Products_ID=".$ProductsID);
		
	if($rsFavourites != FALSE){
		$rsProducts['Products_IsFavourite'] = 1;
	}
}

$biz = $rsProducts['Biz_ID'];
$isOpenShop = 0;
if($biz){
    $sql = "SELECT * FROM biz AS b LEFT JOIN biz_group AS g ON b.Group_ID=g.Group_ID WHERE b.Users_ID='{$UsersID}' AND b.Biz_ID={$biz} AND g.Group_IsStore=1";
    $result = $DB->query($sql);
    $flag = $DB->fetch_assoc($result);
    if($flag){
        $isOpenShop = 1;
    }
}

//获取登录用户的用户级别及其是否对应优惠价

$rsUserConfig = $DB->GetRs("User_Config","UserLevel","where Users_ID='".$UsersID."'");
$discount_list = json_decode($rsUserConfig["UserLevel"],TRUE);

$cur_price = $rsProducts['Products_PriceX'];

if(!empty($discount_list)){
	if(count($discount_list) >1 ){
		//计算出此商品的各级会员价
		foreach($discount_list as $key=>$item){
			if(empty($item['Discount'])){
				$item['Discount'] = 0;
			}
			$discount_price = $rsProducts['Products_PriceX']*(1-$item['Discount']/100);
			$discount_price = getFloatValue($discount_price,2);
			$discount_list[$key]['price'] =  $discount_price;
			$cur = 0;

			if(!empty($_SESSION[$UsersID.'User_ID'])){
				if($rsUser['User_Level'] == $key){
					$cur = 1;
					$cur_price = $discount_price;
				}else{
					$cur = 0;
				}
			}
			
			$discount_list[$key]['cur'] =  $cur;
		}
		array_shift($discount_list);
	}else{
		array_shift($discount_list);
	}
}

$owner = get_owner($rsConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);


if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
}

if(!$rsProducts){
	echo "此商品不存在或已下架";
	exit;
}

/*获取此商品thumb*/


$ImgPath = get_prodocut_cover_img($rsProducts);

$rsCommit = $DB->GetRs("user_order_commit","count(*) as num, sum(Score) as points","where Users_ID='".$UsersID."' and MID='cloud' and Status=1 and Product_ID=".$ProductsID);
if(empty($rsCommit['points'])){
	$rsCommit['points'] = 0;
}

//加入访问记录
$Data=array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"cloud",
	"S_CreateTime"=>time()
);
$DB->Add("statistics",$Data);
//调用模版
$share_link = $cloud_url.'products/'.$ProductsID.'/';
require_once('../share.php');
$share_title = $rsProducts["Products_Name"];
if($owner['id'] != '0' && $rsConfig["Distribute_Customize"]==1){
	$share_desc = $owner['shop_announce'] ? $owner['shop_announce'] : $rsConfig["ShareIntro"];
	$share_img = strpos($owner['shop_logo'],"http://")>-1 ? $owner['shop_logo'] : 'http://'.$_SERVER["HTTP_HOST"].$owner['shop_logo'];
}else{
	$share_desc = $rsConfig["ShareIntro"];
	$share_img = strpos($rsConfig['ShareLogo'],"http://")>-1 ? $rsConfig['ShareLogo'] : 'http://'.$_SERVER["HTTP_HOST"].$rsConfig['ShareLogo'];
}
include("skin/products.php");
?>