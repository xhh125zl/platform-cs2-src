<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

$base_url = base_url();
$shop_url = shop_url();
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["BizID"])){
	$BizID=$_GET["BizID"];
	$DB->query("SELECT b.*,g.Group_IsStore FROM biz as b,biz_group as g WHERE b.Group_ID=g.Group_ID and b.Biz_Status=0 and b.Biz_ID=".$BizID);
	$rsBiz = $DB->fetch_assoc();
	if(empty($rsBiz["Biz_ID"])){
		echo '您要访问的商铺不存在！';
		exit;
	}
	
	if(empty($rsBiz["Group_IsStore"])){
		$IsStore = 0;
	}else{
		$IsStore = $rsBiz["Group_IsStore"];
	}
	
	if($IsStore==0){
		echo '您要访问的商铺不存在！';
		exit;
	}
	
}else{
	echo '缺少必要的参数';
	exit;
}

$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

//授权
$owner = get_owner($rsConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);

$biz_url = $base_url.'api/'.$UsersID.'/'.($owner['id']==0 ? '' : $owner['id'].'/').'biz/'.$rsBiz["Biz_ID"].'/';
$shop_url = $shop_url.($owner['id']==0 ? '' : $owner['id'].'/');
?>