<?php
define('USER_PATH', dirname(__FILE__) . '/');
include USER_PATH . '../Framework/Conn.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$UsersID = '';
$BizID = 0;

if (isset($_SESSION['BIZ_ID'])) {
	$UsersID = $_SESSION['Users_ID'];
	$BizID = $_SESSION["BIZ_ID"];
} else {
	die('请先登录');
}

?>