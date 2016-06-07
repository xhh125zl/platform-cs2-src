<?php
//网站根域名
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST']);
//入口文件 根目录
define( 'SITE_PATH', __DIR__ );
//定义时间
define ( 'TIMESTAMP', time() );
/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define( 'JDPHP_DEBUG', true );
/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define ( 'APP_PATH', SITE_PATH . '/pc' );
/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', APP_PATH . 'runtime' );
/**
 * 引入核心入口
 * FF亦可移动到WEB以外的目录
 */
require SITE_PATH.'/Framework/FF/core.php';
?>