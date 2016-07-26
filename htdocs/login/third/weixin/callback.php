<?php
header("Cache-control:no-cache,no-store,must-revalidate");
header("Pragma:no-cache");
header("Expires:0");

define('CMS_ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once(CMS_ROOT .'./Framework/Conn.php');
require_once('./include/weixin.class.php');

$users_id = $UsersID = isset($_GET['users_id']) ? $_GET['users_id'] : '';
	
if (empty($users_id)) {
	die('信息丢失!');
}

//检查users_id 是否为有效的信息

$DB->debug=true;
$config = $DB->GetRs('third_login_config', 'appid, secret', "WHERE users_id = '" . $users_id . "' AND state=1");
if (empty($config)) {
	die('未启用微信登录');
}

$Login = new Weixin($config['appid'], $config['secret']);
$code = $Login->get_code();
if (empty($code)) {
	die('code信息丢失');
}

$ret = $Login->get_access_token($code);

$openid = $ret['openid'];

//根据 openid 查询用户信息
$row = $DB->GetRs('third_login_users', '*', "WHERE users_id=" . $users_id. " AND client='weixin' AND openid=" . $openid);

if (empty($row)) {
	//获取来源网站owner_id
	$shop_config = shop_config($UsersID);
	$dis_config = dis_config($UsersID);
	//合并参数
	$shop_config = array_merge($shop_config,$dis_config);
	$owner = get_owner($shop_config,$UsersID);


	$item = $DB->GetRs("user_config", "ExpireTime", "WHERE Users_ID='".$UsersID."'");
	$expiretime = $item["ExpireTime"];
	unset($item);

	//旧表
	$rsConfig = $DB->GetRs("user_profile", "*", "WHERE Users_ID='".$UsersID."'");

	$rsUser = $DB->GetRs("user", "User_No","WHERE Users_ID='".$UsersID."' ORDER BY User_No DESC");
	if (empty($rsUser["User_No"])) {
		$User_No = "600001";
	} else {
		$User_No = $rsUser["User_No"]+1;
	}

	if (isset($rsConfig['Profile_Input'])) {
		$Home_Json_Input=json_decode($rsConfig['Profile_Input'],true);
		for ($no=0; $no<=4; $no++) {
			if(isset($Home_Json_Input[$no]['InputName'])){
				$User_Json_Input[] = array(
					"InputName"=>$Home_Json_Input[$no]['InputName'],
					"InputValue"=>isset($_POST["InputValue_".$no]) ? $_POST["InputValue_".$no] : ""
				);
			}
		}
	}

	if (isset($rsConfig['Profile_Select'])) {
		$Home_Json_Select=json_decode($rsConfig['Profile_Select'],true);
		for($no=0;$no<=4;$no++){
			if(isset($Home_Json_Select[$no]['SelectName'])){
				$User_Json_Select[] = array(
					"SelectName"=>$Home_Json_Select[$no]['SelectName'],
					"SelectValue"=>isset($_POST["SelectValue_".$no]) ? $_POST["SelectValue_".$no] : ""
				);
			}
		}
	}

	$Data = array(
		//"User_Mobile"=>$_POST['Mobile'],
		//"User_Password"=>md5($_POST['Password']),
		//"User_PayPassword"=>md5($_POST['Password']),				
		"User_From" => 2,	//微信
		"User_CreateTime" => time(),
		"User_Status" => 1,
		"User_Remarks" => "",
		"User_No" => $User_No,
		"User_Json_Input" => isset($User_Json_Input) ? json_encode($User_Json_Input,JSON_UNESCAPED_UNICODE) : "",
		"User_Json_Select" => isset($User_Json_Select) ? json_encode($User_Json_Select,JSON_UNESCAPED_UNICODE) : "",
		"User_ExpireTime" => $expiretime==0 ? 0 : ( time() + $expiretime*86400 ),
		"Users_ID" => $UsersID,
	);
	
	/*
	if ($rsConfig["Profile_Name"]) {
		$Data["User_Name"] = $_POST['Name'];
	}
	
	if ($rsConfig["Profile_Area"]) {
		$Data["User_Province"] = $_POST['Province'];
		$Data["User_City"] = $_POST['City'];
		$Data["User_Area"] = $_POST['Area'];
	}
	
	if ($rsConfig["Profile_Gender"]) {
		$Data["User_Gender"] = $_POST['Gender'];
	}
	
	if ($rsConfig["Profile_Age"]) {
		$Data["User_Age"] = $_POST['Age'];
	}
	
	if ($rsConfig["Profile_NickName"]) {
		$Data["User_NickName"] = removeEmoji($_POST['NickName']);
	}
	
	if ($rsConfig["Profile_IDNum"]) {
		$Data["User_IDNum"] = $_POST['IDNum'];
	}
	if ($rsConfig["Profile_Telephone"]) {
		$Data["User_Telephone"] = $_POST['Telephone'];
	}
	if ($rsConfig["Profile_Fax"]) {
		$Data["User_Fax"] = $_POST['Fax'];
	}
	if ($rsConfig["Profile_QQ"]) {
		$Data["User_QQ"] = $_POST['QQ'];
	}
	if ($rsConfig["Profile_Email"]) {
		$Data["User_Email"] = $_POST['Email'];
	}
	if ($rsConfig["Profile_Company"]) {
		$Data["User_Company"] = $_POST['Company'];
	}
	if ($rsConfig["Profile_Address"]) {
		$Data["User_Address"] = $_POST['Address'];
	}
	 */

	if ($owner['id'] != 0){
		$Data["Owner_Id"] = $owner['id'] ;
		$Data["Root_ID"] = $owner['Root_ID'];
	}

	$Flag = $DB->Add("user", $Data);
	if (!$Flag) {
		die("注册失败！");
	}

	$_SESSION[$UsersID."User_ID"] = $DB->insert_id();
	$_SESSION[$UsersID."User_Mobile"] = '';
	$OpenID = $opendid;
	$DB->Set("user", 
		array('User_OpenID'=>$OpenID), 
		'WHERE Users_ID="' . $UsersID . '" AND User_ID=' . $_SESSION[$UsersID . "User_ID"]
	);
	$Data = array(
		"status" => 1,
		"msg" => "恭喜，注册成功！",
		"url" => $HTTP_REFERER
	);	

	//新用户
	$data = [
		'user_id' => $DB->insert_id(),
		'users_id' => $users_id,
		'client' => 'weixin',
	];
	$data = array_merge($ret, $data);

	$result = $DB->Add($data);
	if ($result) {
		//注册新用户成功

	}

} 


//已注册过的用户
if (empty($_SESSION[$UsersID."OpenID"])) {
	$_SESSION[$UsersID."OpenID"] = $openid;
}

$rsUser=$DB->GetRs("user", "*", "WHERE Users_ID='" . $UsersID . "' AND User_OpenID='".$_SESSION[$UsersID."OpenID"]."'");

if ($rsUser["User_Status"]) {
	$_SESSION[$UsersID."User_ID"] = $rsUser["User_ID"];
	$_SESSION[$UsersID."User_Name"] = $rsUser["User_Name"];
	$_SESSION[$UsersID."User_Mobile"] = $rsUser["User_Mobile"];
	$_SESSION[$UsersID."HTTP_REFERER"] = "";

	//如果是砍价来源的url	
	if(isset($_SESSION[$UsersID.'is_kanjia'])){
		$HTTP_REFERER .= $_SESSION[$UsersID."User_ID"].'/';
		header("location:".$HTTP_REFERER);
	} else {
		$url = '/api/' . $User_ID . '/shop/';
		header('Location:' . $url);
	}
	
} else {
	echo '<script language="javascript">alert("帐号锁定，禁止登录！");</script>';
}
