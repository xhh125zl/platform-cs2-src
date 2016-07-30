<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$share_user = $DB->GetRs("users","*","where Users_ID='".$UsersID."'");
$share_flag = 0;
$signature = "";

if($share_user["Users_WechatAppId"] && $share_user["Users_WechatAppSecret"]){

	$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
	$temArr = $weixin_jssdk->jssdk_get_signature();
	
	if(is_array($temArr)){
		$share_flag = 1;
		$timestamp = $temArr["timestamp"];
		$noncestr = $temArr["noncestr"];
		$url = $temArr["url"];
		$signature = $temArr["signature"];
	
		$xpcshare_shop = $DB->GetRs("shop_config","Distribute_Share,Distribute_ShareScore,Member_Share,Member_ShareScore","where Users_ID='".$UsersID."'");
		if(isset($_SESSION[$UsersID."User_ID"]) && !empty($_SESSION[$UsersID."User_ID"])){
			$xpcuser = $DB->GetRs("user","Is_Distribute","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
			$share_addscore = 0;
			if($xpcshare_shop["Distribute_Share"] && $xpcuser["Is_Distribute"]==1){
				$share_addscore = $xpcshare_shop["Distribute_ShareScore"];
			}elseif($xpcshare_shop["Member_Share"] && $xpcuser["Is_Distribute"]==0){
				$share_addscore = $xpcshare_shop["Member_ShareScore"];
			}
			if($share_addscore>0){
				$transfer = empty($share_link) ? $url : $share_link;
				$Data = array(
					"Users_ID"=>$UsersID,
					"User_ID"=>$_SESSION[$UsersID."User_ID"],
					"Type"=>"shop",
					"Score"=>$share_addscore,
					"CreateTime"=>time(),
					"Transfer"=>$transfer
				);
				$DB->Add("share_record",$Data);
				$share_id = $DB->insert_id();
			}
		}
	}
}
?>