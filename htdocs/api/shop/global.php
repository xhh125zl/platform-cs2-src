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
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

//授权
$owner = get_owner($rsConfig,$UsersID);
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

if(!$distribute_flag && $rsConfig['Distribute_ShopOpen']==1){
	header("location:".$distribute_url."join/");
	exit;
}

if($rsConfig["Fuxiao_Open"]==1){
	//冻结前复销提醒处理
	distribute_fuxiao_tixing($rsConfig,$DB);
	//冻结动作
	distribute_dongjie_action($rsConfig,$DB);
	//冻结后复销提醒处理
	distribute_dongjie_tixing($rsConfig,$DB);
	//删除动作
	distribute_delete_action($rsConfig,$DB);
}

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();
?>