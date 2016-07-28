<?php
//设置通过 appid, secret, callback
define('CMS_ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once(CMS_ROOT .'./Framework/Conn.php');
require_once("./API/qqConnectAPI.php");

//禁止重复登录
if(isset($_SESSION["Users_ID"]) && !empty($_SESSION["Users_ID"])) {
	header("location:/login/");
}

$users_id = $UsersID = isset($_GET['users_id']) ? $_GET['users_id'] : '';

if (empty($users_id)) {
	if (isset($_SESSION['callback_users_id'])) {
		$users_id = $UsersID = $_SESSION['callback_users_id'];

		if (empty($users_id)) {
			die('信息丢失!');
		}
	} else {
		die('信息丢失!');	
	}
}

//检查users_id 是否为有效的信息
//$DB->debug=true;
$config = $DB->GetRs('third_login_config', 'appid, secret', "WHERE type='qq' AND users_id = '" . $users_id . "' AND state=1");
if (empty($config)) {
	die('未启用QQ登录');
}

$callback_url = 'http://'.$_SERVER['HTTP_HOST'] . '/login/third/qq/callback.php?users_id=' . $users_id;

$qc = new QC();

$qc->setRecorderValue('appid', $config['appid']);
$qc->setRecorderValue('appsecret', $config['secret']);
$qc->setRecorderValue('callback', $callback_url);

$access_token = $qc->qq_callback();	//$qc->get_access_token();
$openid = $qc->get_openid();

//获取用户基本信息
$qc1 = new QC($access_token, $openid);
$arg = [
	'access_token' => $access_token,
	'oauth_consumer_key' => $config['appid'],
	'openid' => $openid,
	
];
$userInfo = $qc1->get_user_info($arg);
unset($qc1);
 
//检查是否为新用户
$user = $DB->GetRs('third_login_users', '*', "WHERE users_id='" . $users_id . "' AND client='qq' AND openid='" . $openid . "'");
if (empty($user)) {

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
		"User_NickName" => $userInfo['nickname'],
		'User_Gender' => $userInfo['gender'],
		'User_HeadImg' => $userInfo['figureurl_qq_2'],
		'User_Province' => $userInfo['province'],
		'User_City' => $userInfo['city'],
		"User_From" => 2,	//微信
		"User_CreateTime" => time(),
		"User_Status" => 1,
		"User_Remarks" => "",
		"User_No" => $User_No,
		"User_Json_Input" => isset($User_Json_Input) ? json_encode($User_Json_Input,JSON_UNESCAPED_UNICODE) : "",
		"User_Json_Select" => isset($User_Json_Select) ? json_encode($User_Json_Select,JSON_UNESCAPED_UNICODE) : "",
		"User_ExpireTime" => $expiretime==0 ? 0 : ( time() + $expiretime*86400 ),
		"Users_ID" => $UsersID,
		'User_PayPassword' => 'e10adc3949ba59abbe56e057f20f883e', //初始密码为123456		
	);
	
	if ($owner['id'] != 0){
		$Data["Owner_Id"] = $owner['id'] ;
		$Data["Root_ID"] = $owner['Root_ID'];
	}

	$Data['User_OpenID'] = $openid;
	
	$Flag = $DB->Add("user", $Data);
	if (!$Flag) {
		die("注册失败！");
	}
	
	$_SESSION[$UsersID."User_ID"] = $DB->insert_id();
	$_SESSION[$UsersID."User_Mobile"] = '';
	$OpenID = $openid;

	//新用户
	$ret = [
		'user_id' => $_SESSION[$UsersID."User_ID"],
		'users_id' => $users_id,
		'client' => 'qq',
		'access_token' => $access_token,
		'expires_in' => time() + 86400,
		'refresh_token' => '',
		'openid' => $openid,
	];

	$result = $DB->Add('third_login_users', $ret);
	// if ($result) {
		//注册新用户成功

	// }

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
		$url = '/api/' . $UsersID . '/shop/';
		header('Location:' . $url);
	}
	
} else {
	echo '<script language="javascript">alert("帐号锁定，禁止登录！");</script>';
}
