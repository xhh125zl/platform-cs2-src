<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["ColumnID"])){
	$ColumnID=$_GET["ColumnID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$rsConfig=$DB->GetRs("web_config","*","where Users_ID='".$UsersID."'");
$rsColumn=$DB->GetRs("web_column","*","where Users_ID='".$UsersID."' and Column_ID=".$ColumnID);
$rsColumn["Column_Description"] = str_replace('&quot;','"',$rsColumn["Column_Description"]);
$rsColumn["Column_Description"] = str_replace("&quot;","'",$rsColumn["Column_Description"]);
$rsColumn["Column_Description"] = str_replace('&gt;','>',$rsColumn["Column_Description"]);
$rsColumn["Column_Description"] = str_replace('&lt;','<',$rsColumn["Column_Description"]);

//加入访问记录
$Data=array(
	"Users_ID"=>$UsersID,
	"S_Module"=>"web",
	"S_CreateTime"=>time()
);
$DB->Add("statistics",$Data);

//客服
$KfIco = '';
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsWeb=1");
$KfIco = empty($kfConfig["KF_Icon"]) ? '' : $kfConfig["KF_Icon"];

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_config["link"] = 'http://'.$_SERVER["HTTP_HOST"].'/api/'.$UsersID.'/web/column/'.$ColumnID.'/';
	$share_config["title"] = $rsColumn["Column_Name"];
	$share_config["desc"] = $rsColumn["Column_Name"];
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/web.jpg';
}
$header_title = $rsColumn["Column_Name"];

if($rsColumn["Column_PageType"]){
	include($rsConfig['Skin_ID']."/childlist.php");
}else{
	include($rsConfig['Skin_ID']."/column.php");
}
?>