<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');

if(isset($_GET["ProductsID"])){
	$ProductsID=$_GET["ProductsID"];
}else{
	echo '缺少必要的参数';
	exit;
}

//产品信息
$rsProducts = Product::find($ProductsID);  
if(!$rsProducts){
	echo "此商品不存在或已下架";
	exit;
}
$rsProducts = $rsProducts->toArray();

if($rsProducts["Products_Status"]==0){
	echo "此商品不存在或已下架";
	exit;
}

$ImgPath = get_prodocut_cover_img($rsProducts);
$JSON = json_decode($rsProducts['Products_JSON'],TRUE);
$rsProducts  = handle_product($rsProducts);
$rsProducts["Products_Description"] = htmlspecialchars_decode($rsProducts["Products_Description"],ENT_QUOTES);

$UserID = 0;
$Is_Distribute = 0;
$un_accept_coupon_num = 0;
$rsProducts['Products_IsFavourite'] = 0;

//用户已登录

if($rsUser){
	$UserID = $rsUser["User_ID"];
	$Is_Distribute = $distribute_flag ? 1 : 0;
		
	//获取用户未领取的优惠券数
	$DB->query("SELECT COUNT(*) as num FROM user_coupon WHERE Users_ID='".$UsersID."' and Coupon_StartTime<".time()." and Coupon_EndTime>".time()." and user_coupon.Coupon_ID NOT IN ( SELECT Coupon_ID FROM user_coupon_record WHERE Users_ID='".$UsersID."' and User_ID = ".$UserID." ) order by Coupon_CreateTime desc");
	$rs_unaccept_count = $DB->fetch_assoc();
	$un_accept_coupon_num = $rs_unaccept_count['num'];
		
	//判断此商品是否被当前登陆用户收藏
	$rsFavourites = $DB->getRs('user_favourite_products',"Products_ID","where User_ID='".$UserID."' and Products_ID=".$ProductsID);
	if($rsFavourites != FALSE){
		$rsProducts['Products_IsFavourite'] = 1;
	}
		
	//获取登录用户的用户级别及其是否对应优惠价
		
}

//会员级别对应的价格

$cur_price = $rsProducts['Products_PriceX'];
$rsUserConfig = $DB->GetRs("User_Config","UserLevel","where Users_ID='".$UsersID."'");
$discount_list = $rsUserConfig["UserLevel"] ? json_decode($rsUserConfig["UserLevel"],TRUE) : array();	
if(!empty($discount_list)){
	if(count($discount_list)>1){
		foreach($discount_list as $key=>$item){
			if(empty($item['Discount'])){
				$item['Discount'] = 0;
			}
			$discount_price = $rsProducts['Products_PriceX']*(1-$item['Discount']/100);
			$discount_price = getFloatValue($discount_price,2);
			$discount_list[$key]['price'] =  $discount_price;
			$cur = 0;
			if(!empty($rsUser["User_Level"])){
				if($rsUser['User_Level'] == $key){
					$cur = 1;
					$cur_price = $discount_price;
				}else{
					$cur = 0;
				}
			}
			$discount_list[$key]['cur'] =  $cur;
		}
	}
	array_shift($discount_list);
}

//必选属性价格
$properties = get_product_properties($ProductsID);  // 获得商品的规格和属性

$no_attr_price = $cur_price;
if(!empty($properties['spe'])){
	$specification = $properties['spe'];
	foreach($specification as $Attr_ID=>$item){
		if($item['Attr_Type'] == 1){
			foreach($item['Values'] as $k=>$v){
				if($k == 0){
					$cur_price += $v['price'];
				}
				
			}
		}
	}
}else{
	$specification = array();
}

/*获取本店满多少减多少条件*/
$man_list = json_decode($rsConfig['Man'],true);
/*获取此商品thumb*/

//自定义分享
if(!empty($share_config)){
	$share_config["link"] = $shop_url.'products/'.$ProductsID.'/';
	$share_config["title"] = $rsProducts["Products_Name"];
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

$DB->query("SELECT b.Biz_Name,b.Biz_Logo,b.Biz_ID,g.Group_IsStore FROM biz as b,biz_group as g WHERE b.Group_ID=g.Group_ID and b.Biz_ID=".$rsProducts["Biz_ID"]);
$rsBiz = $DB->fetch_assoc();
if(empty($rsBiz["Group_IsStore"])){
	$IsStore = 0;
}else{
	$IsStore = $rsBiz["Group_IsStore"];
	$biz_url = $base_url.'api/'.$UsersID.'/'.($owner['id']==0 ? '' : $owner['id'].'/').'biz/'.$rsBiz["Biz_ID"].'/';
}

//获取产品相关评论
$comment_aggregate = get_comment_aggregate($DB,$UsersID,$ProductsID);

include("skin/products.php");
?>