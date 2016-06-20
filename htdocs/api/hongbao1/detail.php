<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	
	if(!strpos($_SERVER['REQUEST_URI'],"OpenID=")){
		if(!strpos($_GET["UsersID"],"_")){
			echo '缺少必要的参数';
			exit;
		}else{//help friend
			$arr = explode("_",$_GET["UsersID"]);
			$UsersID = $arr[0];
			$actid = $arr[1];
		}
		$_SESSION[$UsersID."HTTP_REFERER"]="/api/hongbao/detail.php?UsersID=".$_GET["UsersID"];
		$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$UsersID."'");
		if(!$rsConfig){
			echo '未开通抢红包';
			exit;
		}
	}else{
		header("location:/api/hongbao/detail.php?UsersID=".$_GET["UsersID"]."&wxref=mp.weixin.qq.com");
	}
}else{
	echo '缺少必要的参数';
	exit;
}

$actinfo = $DB->GetRs("hongbao_act","*","where usersid='".$UsersID."' and actid=$actid");
if(!$actinfo){
	echo '该红包不存在';
	exit;
}

$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");

$is_login = 1;
$shopConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$shopConfig = array_merge($shopConfig,$dis_config);
$owner = get_owner($shopConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");
$rank = $ranklist = array();
//排行榜
$rsRank = $DB->query("SELECT u.User_HeadImg,u.User_NickName,sum(za.money) as money,za.userid FROM hongbao_act za,`user` u where u.User_ID = za.userid and za.usersid='".$UsersID."' group by za.userid order by sum(za.money) desc");
$ranklist = $DB->toArray($rsRank);
foreach($ranklist as $key=>$item){
	if(empty($rank[$item["userid"]])){
		$rank[$item["userid"]] = $item;
	}else{
		continue;
	}
}
$chai_pass = 0;
if($actinfo["status"]==1){
	$chai_pass = 1;
}

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_config["title"] = $rsConfig["name"];
	$share_config["desc"] = '红包疯抢，大奖领不停';
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/hongbao.jpg';
}

if($actinfo["userid"]==$_SESSION[$UsersID."User_ID"]){//我的红包
	if($chai_pass==1){
		echo '<script type="text/javascript">alert("该红包已拆启");window.location.href="/api/'.$UsersID.'/hongbao/mycenter/";</script>';
		exit;
	}
	if($actinfo["expire"] >= $actinfo["friend"]){
		$chai = 1;
		$diff = 0;
	}else{
		$chai = 0;
		$diff = $actinfo["friend"] - $actinfo["expire"];
	}	
	require_once('skin/detail.php');
}else{
	$people = count($rank);
	$myrecord = 0;
	$rsRecord = $DB->GetRs("hongbao_record","count(*) as num","where usersid='".$UsersID."' and actid=$actid and userid=".$_SESSION[$UsersID."User_ID"]);
	if($rsRecord["num"]>0){
		$myrecord = 1;
	}
	require_once('skin/help.php');
}
?>
