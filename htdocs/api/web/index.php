<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

//加入访问记录
$Data=array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"web",
	"S_CreateTime"=>time()
);
$DB->Add("statistics",$Data);

$rsConfig=$DB->GetRs("web_config","*","where Users_ID='".$UsersID."'");

//调用客服
$KfIco = '';
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsWeb=1");
$KfIco = empty($kfConfig["KF_Icon"]) ? '' : $kfConfig["KF_Icon"];

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_config["link"] = 'http://'.$_SERVER["HTTP_HOST"].'/api/'.$UsersID.'/web/';
	$share_config["title"] = $rsConfig["SiteName"];
	$share_config["desc"] = $rsConfig["SiteName"];
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/web.jpg';
}
$rsSkin=$DB->GetRs("web_home","*","where Users_ID='".$UsersID."' and Skin_ID=".$rsConfig['Skin_ID']);
$header_title = $rsConfig["SiteName"];
include($rsConfig['Skin_ID']."/index.php");
?>