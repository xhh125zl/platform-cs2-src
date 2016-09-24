<?php
define('USER_PATH', dirname(__FILE__) . '/');
define('IMG_SERVER', 'http://401.wzw.com/');

include USER_PATH . '../Framework/Conn.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$UsersID = '';
$BizID = 0;
$BizAccount = '';

if (isset($_SESSION['BIZ_ID'])) {
	$UsersID = $_SESSION['Users_ID'];
	$BizID = $_SESSION["BIZ_ID"];
	$BizAccount = $_SESSION['Biz_Account'];
} else {
	//die('请先登录');
	header('Location:/biz/login.php');
}

?>