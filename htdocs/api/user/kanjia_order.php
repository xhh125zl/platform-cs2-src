<?php
ini_set("display_errors","On");   
error_reporting(E_ALL);  
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');


//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = $_SERVER["DOCUMENT_ROOT"].'/api/user/html';
$smarty->template_dir = $template_dir;

$base_url = base_url();
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;	
}

//如果未登录
if(empty($_SESSION[$UsersID."User_ID"]) || !isset($_SESSION[$UsersID."User_ID"])){
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/kanjia_order/?wxref=mp.weixin.qq.com";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}


$User_ID = $_SESSION[$UsersID.'User_ID'];
$Status = isset($_GET['status'])?$_GET['status']:0;

$condition = "where Users_ID='".$UsersID."' and User_ID='".
$User_ID."' and Order_Type='kanjia' and Order_Status=".$Status." order by Order_CreateTime desc";

$fields = "Order_ID,Order_TotalAmount,Order_CartList,Order_CreateTime";

$rsOrders = $DB->get("user_order",$fields,$condition);
$order_list = $DB->toArray($rsOrders);
//处理订单数据

foreach($order_list  as $key=>$item){
	$item['Order_Sn'] = date("Ymd",$item['Order_CreateTime']).'-'.($item['Order_ID']);
    $item['Order_CartList'] = json_decode(htmlspecialchars_decode($item['Order_CartList']),true);
	$order_list[$key] = $item;
}


//通用变量赋值
$smarty->assign('base_url',$base_url);
$smarty->assign('UsersID',$UsersID);
$smarty->assign('title','我的砍价订单');


//本页变量赋值
$smarty->assign("Status",$Status);
$smarty->assign('order_list',$order_list);
//渲染页面
$smarty->display('kanjia_order.html');



