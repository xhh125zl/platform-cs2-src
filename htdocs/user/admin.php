<?php
include './config.inc.php';

//检查是否已登录
// if ($UsersID == '') {
// 	header('Location:' . USER_PATH . 'login.php');
// }

$act = isset($_GET['act']) ? $_GET['act'] : 'products';

$actionArr = [
	'products', 
	'product',
	'category',
	'search',
	'main',
	'my_cate',
	'order_details',
	//'order_list',
	'order_confirm',
	'order_unpaid',
	'order_paid',
	'order_delivered',
	'order_completed',
	'store',
	'learn',
	'learn_list',
	'learn_detail',
	'web',
	'setting',
	'setting_avatar',
	'setting_shopname',
	'setting_wechat',
	'setting_receive',
	'setting_share',
	'setting_qrcode',
	'setting_announce',
	'setting_backgoods',
	'user_list',
	'user_detail',
	'product_add',
	'product_supply',
	'product_edit',
	'data_statistics',
	'financial_analysis',
	//'distribute_list',
	'distribute_listn',
	'distribute_oneLevel',
	'distribute_twoLevel',
	'distribute_threeLevel',
	'distribute_detail'
];

if (! in_array($act, $actionArr)) {
	$act = 'products';
}


$file = $act . '.php';

include_once $file;


