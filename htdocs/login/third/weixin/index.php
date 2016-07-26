<?php
header("Cache-control:no-cache,no-store,must-revalidate");
header("Pragma:no-cache");
header("Expires:0");

define('CMS_ROOT', $_SERVER["DOCUMENT_ROOT"]);

require_once(CMS_ROOT .'./Framework/Conn.php');
require_once('./include/weixin.class.php');

$users_id = isset($GET['users_id']) ? $_GET['users_id'] : '';

$users_id = 'abcd';

$DB->debug=true;
$config = $DB->GetRs('third_login_config', 'appid, secret', " WHERE type='weixin' AND users_id = '" . $users_id . "' AND state=1");
if (empty($config)) {
	die('未启用微信登录');
}

$login = new Weixin($config['appid'], $config['secret']);
$redirect = 'http://'.$_SERVER['HTTP_HOST'] . '/login/third/weixin/callback.php?users_id=' . $users_id;
$login->set_login_callback_url($redirect);
$login_url = $login->get_login_url();	//用js或者php转到这个登录地址，进行扫描二维码

header('Location:' . $login_url);





