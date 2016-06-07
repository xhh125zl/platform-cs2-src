<?php
namespace framework;
use base\base;
session_start();
header('Content-Type:text/html;charset=utf-8');  //设置系统的输出字符为utf-8
date_default_timezone_set('PRC');    		 //设置时区（中国）
// 检测PHP环境
if(version_compare(PHP_VERSION, '5.4.0', '<')) die('require PHP > 5.4.0 !');
if(JDPHP_DEBUG) {
	error_reporting(E_ALL);
}else {
	error_reporting(E_ALL & ~E_NOTICE);
}
defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', __DIR__);
require_once(SITE_PATH . '/Framework/dbconfig.php');
include_once __DIR__ . '/helper/core.php'; //系统核心函数文件
include_once __DIR__ . '/base/base.php';
base::start();
?>