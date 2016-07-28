<?php
//设置通过 appid, secret, callback
define('CMS_ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once(CMS_ROOT .'./Framework/Conn.php');

require_once("./API/qqConnectAPI.php");

$users_id = isset($_GET['users_id']) ? $_GET['users_id'] : '';
if (empty($users_id)) {
	die('信息丢失!');
} else {
	//解决手机下回调地址过滤自定义参数的问题
	$_SESSION['callback_users_id'] = $users_id;
}

$config = $DB->GetRs('third_login_config', 'appid, secret', " WHERE type='qq' AND users_id = '" . $users_id . "' AND state=1");
if (empty($config)) {
	die('未启用QQ登录');
}

$callback_url = 'http://'.$_SERVER['HTTP_HOST'] . '/login/third/qq/callback.php?users_id=' . $users_id;

$qc = new QC();
$qc->setRecorderValue('appid', $config['appid']);
$qc->setRecorderValue('appsecret', $config['secret']);
$qc->setRecorderValue('callback', $callback_url);

$qc->qq_login();
