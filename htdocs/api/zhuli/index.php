<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$actid = 0;
if(isset($_GET["UsersID"])){
	$_SESSION[$_GET["UsersID"]."HTTP_REFERER"]="/api/zhuli/index.php?UsersID=".$_GET["UsersID"];
	if(!strpos($_SERVER['REQUEST_URI'],"OpenID=")){
		if(!strpos($_GET["UsersID"],"_")){
			$UsersID = $_GET["UsersID"];
		}else{//help friend
			$arr = explode("_",$_GET["UsersID"]);
			$UsersID = $arr[0];
			$actid = $arr[1];
		}
		
		$rsConfig = $DB->GetRs("zhuli","*","where Users_ID='".$UsersID."'");
		
		if(!$rsConfig){
			echo '未开通微助力';
			exit;
		}
	}else{
		header("location:/api/zhuli/index.php?UsersID=".$_GET["UsersID"]."&wxref=mp.weixin.qq.com");
	}
}else{
	echo '缺少必要的参数';
	exit;
}

$rsUsers = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");

$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$Prizes = $rsRank = $firend = array();
//排行榜
$rsRank = $DB->query("SELECT u.User_HeadImg,u.User_NickName,za.Act_Score,za.Act_ID FROM zhuli_act za,user u where u.User_ID = za.User_ID and za.Users_ID='".$UsersID."' order by za.Act_Score desc");
$rank = $DB->toArray($rsRank);
foreach($rank as $key=>$item){
	$rank[$key]['rank'] = $key+1;
}
//奖品
$Prizes = json_decode($rsConfig['Prizes'],TRUE);
$prize_level_list = array('一等奖','二等奖','三等奖','四等奖','五等奖','六等奖');
if(!empty($Prizes)){
	foreach($Prizes as $key=>$prize){
		$Prizes[$key]['Level'] = $prize_level_list[$key];
	}
}

$myact = $my_rank = $my_score = $my_zhuli = 0;
if($actid==0){
	$actinfo = $DB->GetRs("zhuli_act","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
	if($actinfo){
		$actid = $actinfo["Act_ID"];
		Header("Location:/api/zhuli/index.php?UsersID=".$UsersID."_".$actid);
	}	
}
if($actid>0){//助力开始
	$actinfo = $DB->GetRs("zhuli_act","*","where Users_ID='".$UsersID."' and Act_ID=".$actid);
	if($actinfo){			
		foreach($rank as $key=>$act){
			if($act['Act_ID'] == $actid){
				$my_rank =  $act['rank'];
				$my_score =  $act['Act_Score'];
			}
		}
				
		//获取我的朋友
		$rsList = $DB->query("SELECT u.User_HeadImg,u.User_NickName,r.Record_Score,r.Record_Time FROM zhuli_record r,user u where u.User_ID = r.User_ID and r.Users_ID='".$UsersID."' and r.Act_ID=".$actid." order by Record_Score DESC");
		$firend = $DB->toArray($rsList);

		foreach($firend as $key=>$item){
			$firend[$key]['Record_Time'] = date("Y/m/d h:m",$item['Record_Time']);
		}
		if($_SESSION[$UsersID."User_ID"]==$actinfo["User_ID"]){//自己的助力
			//获取排名
			$myact = 1;
		}else{
		    $recordinfo = $DB->GetRs("zhuli_record","*","where Users_ID='".$UsersID."' and Act_ID=".$actid." and User_ID=".$_SESSION[$UsersID."User_ID"]);
			if($recordinfo){
				$my_zhuli = 1;
			}
		}
	}
}

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_config["title"] = $rsConfig["Zhuli_Name"];
	$share_config["desc"] = $rsConfig["Rules"];
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/zhuli.jpg';
}

require_once('skin/index.php');
?>
