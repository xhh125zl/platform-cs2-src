<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');
 
//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";

$template_dir = $_SERVER["DOCUMENT_ROOT"].'/api/kanjia/skin/1';
$smarty->template_dir = $template_dir;

$base_url = base_url();

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/kanjia/";
	
}else{
	echo '缺少必要的参数';
	exit;
}

require_once('../share.php');

//授权登录
$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');


//获取当前用户砍价情况
if(!empty($_SESSION[$UsersID."User_ID"])){
	$user_id = $_SESSION[$UsersID."User_ID"];
	$condition = "where Users_ID = '".$UsersID."' and User_ID='".$user_id."'";
	$rs_member_kanjia = $DB->Get('kanjia_member','*',$condition);
	$member_kanjias = $DB->toArray($rs_member_kanjia);
	$member_kanjia_list = array();
	//处理数据
	foreach($member_kanjias as $key=>$item){
		$member_kanjia_list[$item['Kanjia_ID']] = $item;
	}
	
}else{
	$member_kanjia_list = array();	
}

//获取砍价活动列表
//获取活动列表
$condition = "where Users_ID = '".$UsersID."'";

	$filter = '';
	if(isset($_GET['is_hot'])){
		$filter = 'is_hot';
		$condition .= 'order by Member_Count';	
	}else if(isset($_GET['is_new'])){
		$filter =  'is_new';
		$condition .= 'order by Kanjia_Createtime';
	}else if(isset($_GET['is_recommend'])){
		$filter = 'is_recommend';
		$condition .= 'and is_recommend = 1';
	}
	
if(isset($_GET['search'])){
	$condition .= " and Product_Name like '%".$_GET["Keyword"]."%'";
}
	

$rsKanjia = $DB->getPage("kanjia","*",$condition,10);
$kanjia_list = $DB->toArray($rsKanjia);

//获取活动产品信息
$id_array = array();
foreach($kanjia_list as $key=>$item){
	$id_array[] = $item['Product_ID'];
}

$ids_string = implode(',',$id_array);
$condition = "where Users_ID = '".$UsersID."'";

if(strlen($ids_string)>0){
	$condition .= ' and Products_ID in ('.$ids_string.')';
	$fields = 'Products_ID,Products_Name,Products_Count,Products_JSON,Products_PriceY,Products_PriceX';
	$rs_product = $DB->Get('shop_products',$fields,$condition);
	$products = $DB->toArray($rs_product);
}else{
	$products = array();
}

foreach($products as $key=>$item){
	
	$Products_JSON = json_decode($item['Products_JSON'],TRUE); 
	if(isset($Products_JSON['ImgPath'])){
		$product_image = $Products_JSON['ImgPath'];
		if(count($product_image)>0){
		
			$item['thumb'] = $product_image[0];
		}
	
	}else{
	   $item['thumb'] = 'static/api/kanjia/image/nopic.jpg';	
	}
	
	$product_list[$item['Products_ID']] = $item;
}

foreach($kanjia_list as $key=>$activity){
	$product_id = $activity['Product_ID'];
	unset($activity['Product_ID']);
	unset($activity['Product_Name']);
	$activity['product'] = $product_list[$product_id];
	 //如果用户已经登录
	if(!empty($_SESSION[$UsersID."User_ID"])){
		$activity['activity_url'] = $base_url.'api/'.$UsersID.'/kanjia/activity/'.$activity['Kanjia_ID'];
		$activity['activity_url'] .= '/userid/'.$_SESSION[$UsersID.'User_ID'].'/';
	}else{
		$activity['activity_url'] = $base_url.'api/'.$UsersID.'/kanjia/activity/'.$activity['Kanjia_ID'].'/';
	}
	
	//检查此活动是否过期
	$now_time = time();
	
	if($now_time > $activity['Totime'] ){
		$activity['expired'] = 1;
	}else{
		$activity['expired'] = 0;
	}
	$kanjia_list[$key] = $activity;
}


//全局变量赋值
$smarty->assign('base_url',$base_url);
$smarty->assign('public',$base_url.'/static/api/kanjia/');
$smarty->assign('kanjia_url',$base_url.'api/'.$UsersID.'/kanjia/');
$smarty->assign('UsersID',$UsersID);
$smarty->assign('title','微砍价活动列表');
//分享变量
$smarty->assign('share_flag',($share_flag==1 && $signature<>""));
if($share_flag){
	$smarty->assign('signature',$signature);
	$smarty->assign('appId',$share_user["Users_WechatAppId"]);
	$smarty->assign('timestamp',$timestamp);
	$smarty->assign('noncestr',$noncestr);
	$smarty->assign('url',$url);
	$smarty->assign('desc','微砍价');
	$smarty->assign('img_url','http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/kanjia.jpg');
	$smarty->assign('link','');
}

//本页变量赋值
$smarty->assign('filter',$filter);
$smarty->assign('kanjia_list',$kanjia_list);
$smarty->assign('member_kanjia_list',$member_kanjia_list);
$smarty->display('index.html');
