<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$shop_url = shop_url();

//商城配置信息
$rsConfig = shop_config($UsersID);
if(!$rsConfig){
    die("商城没有配置");
}
//分销相关设置
$dis_config = dis_config($UsersID);
if(!$rsConfig){
    $dis_config = array();
}
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

//授权
$owner = get_owner($rsConfig,$UsersID);
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);

//更换商城配置信息
if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$shop_url = $shop_url.$owner['id'].'/';
};

//分销级别处理文件
include($_SERVER["DOCUMENT_ROOT"].'/api/distribute/distribute.php');
?>