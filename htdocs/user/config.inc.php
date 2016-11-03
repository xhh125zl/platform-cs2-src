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
	if (isset($_GET['orderid'])) {
		header("Location:/user/admin.php?act=order_details&orderid=" . htmlspecialchars($_GET['orderid']));
	}
	exit;
} else {
	//die('请先登录');
	if (isset($_GET['uuid']) && isset($_GET['time']) && $_GET['bizID']) {
		$rsBiz = $DB->GetRs('biz', '*', "where uuid = '". htmlspecialchars(strip_tags($_GET['uuid'])) ."' and Biz_ID = " . (int)$_GET['bizID'] . " and loginTime = " . ((int)$_GET['time'] + 86400 * 30) . " and loginTime > " . time());
		if ($rsBiz) {
			$_SESSION["BIZ_ID"] = $rsBiz["Biz_ID"];
			$UsersID = $rsBiz["Biz_ID"];
			$BizID = $_SESSION['Biz_Account'] = $rsBiz['Biz_Account'];
			$BizID = $rsBiz['Biz_Account'];
			$BizAccount = $_SESSION["Users_ID"] = $rsBiz["Users_ID"];
			$BizAccount = $rsBiz["Users_ID"];
			if (isset($_GET['orderid'])) {
				header("Location:/user/admin.php?act=order_details&orderid=" . htmlspecialchars($_GET['orderid']));
			} else {
				header("Location:/user/admin.php?act=store");
			}
			exit;
		} else {
			if (isset($_SESSION["BIZ_ID"])) {
				$DB->Set('biz',['loginTime' => 0], "where Biz_ID = " . $_SESSION["BIZ_ID"]);
				session_unset();
			}
			header("Location:/user/login.php?uuid=". htmlspecialchars(strip_tags($_GET['uuid'])));
		}
	} else {
		if (isset($_SESSION["BIZ_ID"])) {
			$DB->Set('biz',['loginTime' => 0], "where Biz_ID = " . $_SESSION["BIZ_ID"]);
			session_unset();
		}
		if (isset($_GET['uuid'])) {
			header("Location:/user/login.php?uuid=". htmlspecialchars(strip_tags($_GET['uuid'])));
		} else {
			header('Location:/user/login.php');
		}
	}
}

?>