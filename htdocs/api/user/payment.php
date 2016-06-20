<?php
//砍价订单付款
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

//获取指定订单
$OrderID=$_GET['OrderID'];
$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
$Order_Sn = date("Ymd",$rsOrder['Order_CreateTime']).'-'.($rsOrder['Order_ID']);

//计算余额支付是否可用
if($rsUser["User_Money"] >= $rsOrder["Order_TotalPrice"]){
	$yue_enabled = 1;
}else{
	$yue_enabled = 0;
}
//通用变量赋值
$smarty->assign('base_url',$base_url);
$smarty->assign('UsersID',$UsersID);
$smarty->assign('title','砍价订单付款');

//本页数据赋值
$smarty->assign('yue_enabled',$yue_enabled);
$smarty->assign('OrderSn',$Order_Sn);
$smarty->assign('Order',$rsOrder);
$smarty->assign('Pay',$rsPay);
$smarty->assign('user',$rsUser);

//渲染页面
$smarty->display('payment.html');
