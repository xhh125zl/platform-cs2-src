<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
ini_set("display_errors","On");
if (isset($_GET["UsersID"])) {
	$UsersID = $_GET["UsersID"];
} else {
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$shop_url = shop_url();

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

$is_login = 1;
$owner = get_owner($rsConfig,$UsersID);
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$owner = get_owner($rsConfig,$UsersID);

//分销级别处理文件
include($_SERVER["DOCUMENT_ROOT"].'/api/distribute/distribute.php');

//获取登录用户账号
$User_ID = $_SESSION[$UsersID."User_ID"];
			   
if(!$distribute_flag) {
	header("location:".$distribute_url."join/");
}

$_SESSION[$UsersID."HTTP_REFERER"] = "/api/".$UsersID."/shop/distribute/";
if(empty($rsUser["User_Profile"])){
	header("location:/api/".$UsersID."/user/complete/");
	exit;
}

//获取登录用户分销账号
$accountObj =  Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID))
			   ->first();
if(empty($accountObj->Account_ID)){//由于未知原因不存在此分销商
	$DB->Set("user","Is_Distribute=0","where User_ID=".$User_ID);
	header("location:".$distribute_url."join/");
}

if($accountObj->Is_Dongjie==1 || $accountObj->Is_Delete==1){//账号被冻结
	header("location:".$distribute_url."dongjie/");
}

if($accountObj->Is_Audit==0){
	echo '您的分销商账号正在审核...';
	exit;
}

if($accountObj->status==0){
	echo '您的分销商账号已被禁用...';
	exit;
}
			   
$rsAccount = $accountObj->toArray(); 
if($rsConfig["Withdraw_Type"]==3){
    $accountObj->Enable_Tixian = 0;
	$accountObj->save();
}
