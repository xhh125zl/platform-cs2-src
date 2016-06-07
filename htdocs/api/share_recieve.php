<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

if(isset($_GET["UsersID"])){
	$UsersID = $_GET["UsersID"];
}else{
	echo "缺少必要的参数";
	exit;
}


$base_url = base_url();
$shop_url = shop_url();

if(isset($_GET["RecordID"])){
	$id = $_GET["RecordID"];
}else{
	echo "缺少必要的参数";
	exit;
}

$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
$owner = get_owner($rsConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);

$record = $DB->GetRs("share_record","*","where Users_ID='".$UsersID."' and Record_ID=".$id);
if(!$record){
	echo "非法地址";
	exit;
}
$score = $record["Score"];
$share_user = $DB->GetRs("user","User_Integral,User_TotalIntegral,Is_Distribute,User_OpenID","where Users_ID='".$UsersID."' and User_ID=".$record["User_ID"]);

if(!$share_user){
	echo "地址不存在";
	exit;
}

if($score>0){
	if(isset($_SESSION[$UsersID."User_ID"]) && !empty($_SESSION[$UsersID."User_ID"])){		
		$r = $DB->GetRs("share_click","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and from_user=".$record["User_ID"]);
		if(!$r){
			if($_SESSION[$UsersID."User_ID"] != $record["User_ID"]){
				$flag=true;
				mysql_query("begin");
			
				$Data = array(
					"Record_ID"=>$id,
					"Users_ID"=>$UsersID,
					"User_ID"=>$_SESSION[$UsersID."User_ID"],
					"from_user"=>$record["User_ID"],
					"CreateTime"=>time()				
				);
				$Add = $DB->Add("share_click",$Data);
				$flag=$flag&&$Add;
				$Data = array(
					"User_TotalIntegral"=>$share_user["User_TotalIntegral"]+$score,
					"User_Integral"=>$share_user["User_Integral"]+$score				
				);
				$Set = $DB->Set("user",$Data,"where Users_ID='".$UsersID."' and User_ID=".$record["User_ID"]);
				$flag=$flag&&$Set;
				
				$Data = array(
					'Record_Integral' => $score,
					'Record_SurplusIntegral' => $share_user['User_Integral'] + $score,
					'Operator_UserName' => '',
					'Record_Type' => 2,
					'Record_Description' => '分享页面，用户点击获取积分',
					'Record_CreateTime' => time(),
					'Users_ID' => $UsersID,
					'User_ID' => $record["User_ID"]
				);
				
				$Add1 = $DB->Add('user_Integral_record', $Data);
				$flag=$flag&&$Add1;
				
				if($flag){
					mysql_query("commit");
					require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
					$weixin_message = new weixin_message($DB,$UsersID,$record["User_ID"]);
					$contentStr = "用户点击您分享的页面，获取".$score."积分";
					$weixin_message->sendscorenotice($contentStr);
				}else{
					mysql_query("roolback");
				}
			}
		}
	}
}

header("location:".$record["Transfer"]);
?>