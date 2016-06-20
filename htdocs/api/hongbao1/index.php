<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');


if(isset($_GET["UsersID"])){
	if(!strpos($_SERVER['REQUEST_URI'],"OpenID=")){
		$UsersID = $_GET["UsersID"];
		$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$UsersID."'");
		if(!$rsConfig){
			echo '未开通抢红包';
			exit;
		}
	}else{
		header("location:/api/hongbao/index.php?UsersID=".$_GET["UsersID"]."&wxref=mp.weixin.qq.com");
	}
}else{
	echo '缺少必要的参数';
	exit;
}
$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."' and PaymentWxpayEnabled=1");

$is_login = 1;
$shopConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$shopConfig = array_merge($shopConfig,$dis_config);
$owner = get_owner($shopConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

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
$people = count($rank);
$start = 1;
$time_diff = 0;
$actinfo = $DB->GetRs("hongbao_act","*","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]." order by addtime desc");
if($actinfo && $rsConfig["pertime"]){
	$diff = time()-intval($rsConfig["pertime"])*60;
	if($actinfo["addtime"]>$diff){
		$start = 0;
		$time_diff = $actinfo["addtime"]+intval($rsConfig["pertime"])*60-time();
	}
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

require_once('skin/index.php');
?>
