<?php
//砍价订单评论
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

if(empty($_SESSION[$UsersID."User_ID"])){
	header("location:/api/".$UsersID."/user/login/");
}


//订单ID
$OrderID = $_GET['OrderID'];

$rsConfig=$DB->GetRs("shop_config","ShopName","where Users_ID='".$UsersID."'");
$rsOrder=$DB->GetRs("user_order","*","where Order_ID=".$OrderID." and User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Order_Status=3");
if(!$rsOrder){
	echo "此订单不存在";
	exit;
}elseif($rsOrder["Is_Commit"]==1){
	echo "此订单已评论，不可重复评论！";
	exit;
}


//通用变量赋值
$smarty->assign('base_url',$base_url);
$smarty->assign('UsersID',$UsersID);
$smarty->assign('title','砍价订单付款');

//本页数据赋值
$smarty->assign("OrderID",$OrderID);

//渲染页面
$smarty->display('commit.html');