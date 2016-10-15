<?php
define('USER_PATH', dirname(__FILE__) . '/');

include USER_PATH . '../Framework/Conn.php';
require_once CMS_ROOT . '/include/helper/tools.php';
require_once CMS_ROOT . '/include/api/const.php';

$UsersID = '';
$BizID = 0;
$BizAccount = '';

if (isset($_SESSION['BIZ_ID'])) {
	$UsersID = $_SESSION['Users_ID'];
	$BizID = $_SESSION["BIZ_ID"];
	$BizAccount = $_SESSION['Biz_Account'];
} else {
	//die('请先登录');
	if (isset($_GET['uuid']) && isset($_GET['time']) && $_GET['bizID']) {
		$rsBiz = $DB->GetRs('biz', '*', "where uuid = '". $_GET['uuid'] ."' and Biz_ID = " . $_GET['bizID'] . " and loginTime > " . time()-86400*30);
		if ($rsBiz) {
			$_SESSION["BIZ_ID"]=$rsBiz["Biz_ID"];
			$_SESSION['Biz_Account'] = $rsBiz['Biz_Account'];
			$_SESSION["Users_ID"]=$rsBiz["Users_ID"];
		}
	} else {
		if (isset($_GET['uuid'])) {
			header("Location:/user/login.php?uuid=". $_GET['uuid']);
		} else {
			header('Location:/user/login.php');
		}
	}
}

?>