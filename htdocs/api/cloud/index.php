<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
	$_SESSION[$UsersID."HTTP_REFERER"] = "/api/cloud/".$_GET["UsersID"].'/';
}else{
	echo '缺少必要的参数';
	exit;
}
$BizID = isset($_GET['BizID'])?$_GET['BizID']:0;
$ActiveID = isset($_GET['ActiveID'])?$_GET['ActiveID']:0;
$BizInfo = $DB->GetRs("biz","*","WHERE Biz_ID='{$BizID}'");
$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';
$cloud_jjjxurl = base_url().'api/'.$UsersID.'/cloud/';

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

$owner = get_owner($rsConfig,$UsersID);

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	//$error_msg = pre_add_distribute_account($rsConfig,$UsersID);
}

$owner = get_owner($rsConfig,$UsersID);
$share_name = '';

if($owner['id'] != '0'){
	$share_name = $rsConfig["ShopName"];
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
};
//加入访问记录
$Data = array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"cloud",
	"S_CreateTime"=>time()
);

$DB->Add("statistics",$Data);
//调用模版
$share_link = base_url().'api/'.$UsersID.'/cloud/';
//require_once('../share.php');

if($owner['id'] != '0' && $rsConfig["Distribute_Customize"]==1){
	$share_title = $rsConfig["ShopName"];
	$share_desc = $owner['shop_announce'] ? $owner['shop_announce'] : $rsConfig["ShareIntro"];
	$share_img = strpos($owner['shop_logo'],"http://")>-1 ? $owner['shop_logo'] : 'http://'.$_SERVER["HTTP_HOST"].$owner['shop_logo'];
}else{
	$share_title = $share_name;
	$share_desc = $rsConfig["ShareIntro"];
	$share_img = strpos($rsConfig['ShareLogo'],"http://")>-1 ? $rsConfig['ShareLogo'] : 'http://'.$_SERVER["HTTP_HOST"].$rsConfig['ShareLogo'];
}

$C = $DB->GetRS("users","Users_Logo","where Users_ID='".$UsersID."'");
$show_support = true;

//幻灯片
$slide_list = array();
$DB->Get('cloud_slide','*','where Users_ID="'.$UsersID.'"');
while($r = $DB->fetch_assoc()) {
	$slide_list[] = $r;
}
//分类
$category_list = array();
$DB->Get('cloud_category','*','where Users_ID="'.$UsersID.'"');
while($r = $DB->fetch_assoc()) {
	$category_list[] = $r;
}
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
$weixin_message = new weixin_message($DB,$UsersID,0);
$weixin_message->sendordernotice();
// echo '<pre>';
// print_R($rsConfig);
// exit;
include("skin/index.php");
?>