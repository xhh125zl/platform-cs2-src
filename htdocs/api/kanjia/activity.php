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
	$_SESSION[$UsersID."is_kan"] = 1;
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["KanjiaID"])){
	$KanjiaID = $_GET["KanjiaID"];
	$_SESSION[$UsersID.'KanjiaID'] = $KanjiaID;
	
}elseif(isset($_SESSION[$UsersID.'KanjiaID'])){
	$KanjiaID = $_SESSION[$UsersID.'KanjiaID'];
}else{
	echo '缺少必要的参数KanJiaID';
	exit;
}
$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/kanjia/activity/".$KanjiaID."/userid/";
require_once('../share.php');
$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");


$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');



//获取此活动信息
$condition = "where Users_ID = '".$UsersID."' and Kanjia_ID='".$KanjiaID."'";
$activity = $DB->GetRs('kanjia',"*",$condition);

//检查此活动是否过期
$now_time = time();
	if($now_time > $activity['Totime'] ){
		$expired = 1;
	}else{
		$expired = 0;
	}
	
 
//截止时间信息
$time_interval = $activity['Totime']-time();
$deadline_array = mkdeadline($time_interval);

//获取此活动关联产品信息
$product_id = $activity['Product_ID'];
$condition = "where Users_ID = '".$UsersID."' and Products_ID='".$product_id."'";
$product = $DB->GetRs('shop_products',"*",$condition);

$Product_JSON = json_decode($product['Products_JSON'],TRUE); 
	if(isset($Product_JSON['ImgPath'])){
		$product_image = $Product_JSON['ImgPath'];
		if(count($product_image)>0){
		
			$product['thumb'] = $product_image[0];
		}
	
	}else{
	   $product['thumb'] = 'static/api/kanjia/image/nopic.jpg';	
	}
     /*处理商品属性*/
	 
	 if(isset($Product_JSON["Property"])){
	 	 $Product_Property = $Product_JSON["Property"];
	 
		 foreach($Product_Property as $key=>$item){
			$values = array_keys($item);
		 	$value_list = $item;
			$default = $values[0];
			unset($Product_Property[$key]);
			$Product_Property[$key] = array('values'=>$item,'default'=>$default);
		 }
	 }else{
	 	 $Product_Property =  array();
	 }
	

//如果用户没有参加活动，则当前价是产品原价
if(!empty($_SESSION[$UsersID."User_ID"])){
	$condition = "where Users_ID = '".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'";
	$condition .= " and Kanjia_ID=".$KanjiaID;
	$member_activity = $DB->GetRs('kanjia_member','*',$condition);
	
	//若用户已经参加此活动
	if($member_activity){
		$self_kaned = 1;
		$cur_price = $member_activity['Cur_Price'];
	}else{
	//若用户没有参加此活动
		$cur_price = $product['Products_PriceX'];
		$self_kaned = 0;
	}
	
}else{
	$self_kaned = 0;
	$member_activity = array();
	$cur_price = $product['Products_PriceX'];
}

//获取此用户的砍友
$condition = "where Users_ID = '".$UsersID."' and Kanjia_ID='".$KanjiaID."'";
$condition .= ' and User_ID ='.$_SESSION[$UsersID.'User_ID'];

$rsHelpers = $DB->Get('kanjia_helper_record',"*",$condition);
$helper_list = $DB->toArray($rsHelpers);

$helper_id_array = array();
foreach($helper_list as $key=>$item){
	$helper_id_array[] = $item['Helper_ID'];
}

if(count($helper_id_array) >0 ){
	$helper_id_str = implode(',',$helper_id_array);
	$condition = "where User_ID in (".$helper_id_str.")";
	$helper_nicknames = $DB->Get('user',"User_ID,User_NickName,User_HeadImg",$condition);

	$nicknames = $DB->toArray($helper_nicknames);

	$user_list = array();
	foreach($nicknames  as $key=>$item){
		$user_list[$item['User_ID']] = $item; 
	}
}else{
	$user_list = array();
}

//全局变量赋值
$smarty->assign('base_url',$base_url);
$smarty->assign('public',$base_url.'/static/api/kanjia/');
$smarty->assign('kanjia_url',$base_url.'api/'.$UsersID.'/kanjia/');
$smarty->assign('title',$activity['Kanjia_Name']);

//本页变量赋值
$smarty->assign('activity',$activity);
$smarty->assign('UsersID',$UsersID);
$smarty->assign('KanjiaID',$KanjiaID);
$smarty->assign('product',$product);
$smarty->assign('cur_price',$cur_price);
$smarty->assign('helper_list',$helper_list);
$smarty->assign('member_activity',$member_activity);
$smarty->assign('self_kaned',$self_kaned);
$smarty->assign('Product_Property',$Product_Property);
$smarty->assign('expired',$expired);
$smarty->assign('user_list',$user_list);
$smarty->assign('time_interval',$time_interval);
$smarty->assign('deadline_array',$deadline_array);


//分享变量
$smarty->assign('share_flag',($share_flag==1 && $signature<>""));
if($share_flag){
	$smarty->assign('signature',$signature);
	$smarty->assign('appId',$share_user["Users_WechatAppId"]);
	$smarty->assign('timestamp',$timestamp);
	$smarty->assign('noncestr',$noncestr);
	$smarty->assign('url',$url);
	$smarty->assign('desc',str_replace(array("\r\n", "\r", "\n"), "", $product["Products_BriefDescription"]));
	$smarty->assign('link','');
}

$login_user_id = isset($_SESSION[$UsersID."User_ID"])?$_SESSION[$UsersID."User_ID"]:0;

$is_help = 0;

if(isset($_GET['UserID'])){
	//帮助他人页面
	
	if($_GET['UserID'] != $login_user_id){	
		$help_user_id = $_GET['UserID']; //被帮助人user_id
		$is_help = 1;
		//获取被帮助人的信息
		$condition = "where Users_ID = '".$UsersID."' and User_ID='".$_GET['UserID']."'";
		$rsUser = $DB->GetRs('user',"User_NickName",$condition);
		$Nick_Name = $rsUser['User_NickName'];
		//查询是否已经帮助过此用户砍价
		$condition = "where Users_ID = '".$UsersID."' and User_ID='".$help_user_id."'";
		$condition .= 'and Kanjia_ID='.$KanjiaID;
		$condition .= ' and Helper_ID='.$_SESSION[$UsersID.'User_ID'];

		$rsHelpRecord = $DB->GetRs('kanjia_helper_record','*',$condition);
		if($rsHelpRecord){
			$helped = 1;
			$smarty->assign('Record_Reduce',$rsHelpRecord['Record_Reduce']);
		}else{
			$helped = 0; 
		}
		
		//获取被帮助人活动产品现价
		$condition = "where Users_ID = '".$UsersID."' and User_ID='".$help_user_id."'";
		$condition .= " and Kanjia_ID=".$KanjiaID;
		$member_activity = $DB->GetRs('kanjia_member','*',$condition);
		$cur_price = $member_activity ['Cur_Price'];
		$smarty->assign('cur_price',$cur_price);
		$smarty->assign('helped',$helped);
		$smarty->assign('User_ID',$_SESSION[$UsersID.'User_ID']);
		$smarty->assign('help_user_id',$_GET['UserID']); //被帮助者的id
		$smarty->assign('nick_name',$Nick_Name);

	}
}

if($is_help){
   $smarty->display('help.html');		
}else{
   $smarty->display('activity.html');
}


/*获取截止时间信息*/
function mkdeadline($intDiff) {
       $day=0;
        $hour=0;
        $minute=0;
        $second=0;//时间默认值       
    if($intDiff > 0){
        $day =  floor($intDiff / (60 * 60 * 24));
        $hour =  floor($intDiff / (60 * 60)) - ($day * 24);
        $minute = floor($intDiff / 60) - ($day * 24 * 60) - ($hour * 60);
        $second = floor($intDiff) - ($day * 24 * 60 * 60) - ($hour * 60 * 60) - ($minute * 60);
    }
	
    if ($minute <= 9) $minute = '0' + $minute;
    if ($second <= 9) $second = '0' + $second;
	
	$deadline_array = array('day'=>$day,'hour'=>$hour,'minute'=>$minute,'second'=>$second);
    
	return $deadline_array;
}