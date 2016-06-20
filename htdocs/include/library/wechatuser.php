<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/userinfo.class.php');
$rswechat = $DB->GetRs("users","Users_WechatAppId,Users_WechatAppSecret,Users_WechatType","where Users_ID='".$UsersID."'");
if($rswechat["Users_WechatAppId"] && $rswechat["Users_WechatAppSecret"] && $rswechat["Users_WechatType"]==3){
	
	$service = 1;
	$u = new userinfo();
	$u->appid = $rswechat["Users_WechatAppId"];
	$u->appsecret = $rswechat["Users_WechatAppSecret"];
	$u->DB = $DB;
	$u->UsersID = $UsersID;
	
}else{
	$service = 0;
}
if(!isset($is_login)){
	$is_login = 0;
}

if(!isset($is_address)){
	$is_address = 0;
}

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","User_ID","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}
}

$codebyurl = "";
$ownerid = isset($owner['id']) ? $owner['id'] : 0;
$rootid =isset($owner['Root_ID']) ? $owner['Root_ID'] : 0;
if(isset($ownerid)){
	$login_url = "/api/".$UsersID."/user/".$ownerid."/login/";
}else{
	$login_url = "/api/".$UsersID."/user/login/";
}

if(empty($_SESSION[$UsersID."User_ID"]) || !isset($_SESSION[$UsersID."User_ID"])){
	if(strpos($_SERVER['REQUEST_URI'],"state=") !== false){//微信通信回来
		if(strpos($_SERVER['REQUEST_URI'],"code=") !== false){//同意授权获取code
			$arr_temp = explode("code=",$_SERVER['REQUEST_URI']);
			if(strpos($arr_temp[1],"?") != false){
				$code_arr = explode("?",$arr_temp[1]);
				$codebyurl = $code_arr[0];
			}elseif(strpos($arr_temp[1],"&") != false){
				$code_arr = explode("&",$arr_temp[1]);
				$codebyurl = $code_arr[0];
			}else{
				$codebyurl = $arr_temp[1];
			}

			$u->getOpenid($codebyurl);
			$login_flag = $u->loginbyopenid();
			if(!empty($_SESSION[$UsersID."OpenID"])){
				if(!$login_flag){
					$dis_config_limit_result = $DB->GetRs('distribute_config','Distribute_Limit','where Users_ID="'.$UsersID.'"');
					if($dis_config_limit_result['Distribute_Limit'] == 1 && $ownerid==0){
						echo '必须通过邀请人才能成为会员';
						exit;
					}
					$register_flag = $u->registerbyopenid($ownerid,$rootid,$codebyurl);
					if(!$register_flag && $is_login==1){
						header("Location:$login_url");
					}
				}
			}
		}else{//用户不同意授权
			$dis_config_limit_result = $DB->GetRs('distribute_config','Distribute_Limit','where Users_ID="'.$UsersID.'"');
			if($dis_config_limit_result['Distribute_Limit'] == 1 && $ownerid==0){
				echo '必须通过邀请人才能成为会员';
				exit;
			}
			if($is_login==1){
				header("Location:$login_url");
			}
		}	
	}else{//网站和微信通信开始
	
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){//weixin client
			if($service==1){
				$url = $u->createOauthUrlForCodeweb(urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
				Header("Location: $url");
			}else{
				if($is_login==1){
					header("Location:$login_url");
				}
			}
		}else{
			if($is_login==1){
				header("Location:$login_url");
			}
		}
	}
}
//
if($is_address && !empty($_SESSION[$UsersID."access_token_web"]) && $service==1){
	$editAddress = $u->GetEditAddressParameters();
}
?>