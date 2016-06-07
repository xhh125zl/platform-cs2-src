<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["ArticleID"])){
	$ArticleID=$_GET["ArticleID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$rsConfig=$DB->GetRs("web_config","*","where Users_ID='".$UsersID."'");
$rsArticle=$DB->GetRs("web_article","*","where Users_ID='".$UsersID."' and Article_ID=".$ArticleID);
$rsArticle["Article_Description"] = str_replace('&quot;','"',$rsArticle["Article_Description"]);
$rsArticle["Article_Description"] = str_replace("&quot;","'",$rsArticle["Article_Description"]);
$rsArticle["Article_Description"] = str_replace('&gt;','>',$rsArticle["Article_Description"]);
$rsArticle["Article_Description"] = str_replace('&lt;','<',$rsArticle["Article_Description"]);
if($rsArticle["Article_Link"]==1 && !empty($rsArticle["Article_LinkUrl"])){
	header("location:".$rsArticle["Article_LinkUrl"]);
}
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
	$share_config["link"] = 'http://'.$_SERVER["HTTP_HOST"].'/api/'.$UsersID.'/web/article/'.$ArticleID.'/';
	$share_config["title"] = $rsArticle["Article_Title"];
	$share_config["desc"] = $rsArticle["Article_BriefDescription"] ? str_replace(array("\r\n", "\r", "\n"), "", $rsArticle["Article_BriefDescription"]) : $rsArticle["Article_Title"];
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/web.jpg';
}

$header_title = $rsArticle["Article_Title"];
//调用模版
include($rsConfig['Skin_ID']."/article.php");
?>