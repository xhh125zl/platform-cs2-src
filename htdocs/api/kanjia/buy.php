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
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["KanjiaID"])){
	$KanjiaID=$_GET["KanjiaID"];
}else{
	echo '缺少必要的参数';
	exit;
}

//获取此活动信息
$condition = "where Users_ID = '".$UsersID."' and Kanjia_ID='".$KanjiaID."'";
$activity = $DB->GetRs('kanjia',"*",$condition);


//获取配送方式
$rsPay = $DB->GetRs("users_payconfig","Shipping,Delivery_AddressEnabled,Delivery_Address","where Users_ID='".$UsersID."'");
$shipping_list = json_decode($rsPay['Shipping'],true); 

foreach($shipping_list  as $key=>$item){
	$item['Price'] = empty($item['Price']) ? 0.00 : number_format($item['Price'],2);
	$shipping_list[$key] = $item;
}

//获取用户地址
$rsAddress = $DB->get("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'");
$address_list = $DB->toArray($rsAddress);

//获取用户参加此活动信息
$condition = "where Users_ID = '".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'";
$condition .= " and Kanjia_ID=".$KanjiaID;
$member_activity = $DB->GetRs('kanjia_member','*',$condition);

//计算订单总价
$cur_price = $member_activity['Cur_Price'];

$shipping_price = $shipping_list[0]['Price']; 
$order_sum = $cur_price+$shipping_price;

//通用变量赋值
$smarty->assign('base_url',$base_url);
$smarty->assign('UsersID',$UsersID);
$smarty->assign('public',$base_url.'/static/api/kanjia/');
$smarty->assign('kanjia_url',$base_url.'api/'.$UsersID.'/kanjia/');
$smarty->assign('title','确认订单');

//本页变量赋值
$smarty->assign('KanjiaID',$KanjiaID);
$smarty->assign('activity',$activity);
$smarty->assign('member_activity',$member_activity);
$smarty->assign('shipping_list',$shipping_list);
$smarty->assign('address_list',$address_list);
$smarty->assign('shipping_price',$shipping_price);
$smarty->assign('order_sum',$order_sum);
$smarty->display('buy.html');



