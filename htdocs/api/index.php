<?php
ini_set("display_errors","On");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_response.class.php');

$REQUEST_URI=explode("?",$_SERVER["REQUEST_URI"]);
$REQUEST_URI=empty($REQUEST_URI[1])?array():explode('&',$REQUEST_URI[1]);

foreach($REQUEST_URI as $value){
	list($k,$v)=explode('=',$value);
	$_GET[$k]=$v;
}

if(isset($_GET["UsersID"])){
	if($_GET["UsersID"]=="index"){
		echo '缺少必要的参数';
		exit;
	}else{
		$UsersID=$_GET["UsersID"];
	}
}else{
	echo '缺少必要的参数';
	exit;
}

$wechatObj = new weixin_response($DB, $UsersID);
if(isset($_GET["echostr"])){
	$wechatObj->valid();
}

if(isset($GLOBALS["HTTP_RAW_POST_DATA"])){
	$Data=array(
		"HTTP_RAW_POST_DATA"=>$GLOBALS["HTTP_RAW_POST_DATA"],
		"CreateTime"=>time()
	);
	$DB->Add("HTTP_RAW_POST_DATA",$Data);
	$wechatObj->responseMsg();
}
?>