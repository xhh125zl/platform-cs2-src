<?php
include 'config.inc.php';
include USER_PATH . './lib/global.func.php';

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
	'order_list',
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
	'distribute_list',
	'distribute_detail',
	'msg_system',
	'msg_order',
	'msg_distribute',
	'msg_withdraw',
	'msg_setting',
	'basic_1',
	'basic_2',
	'basic_3',
];

if (! in_array($act, $actionArr)) {
	$act = 'store';
}


$file = $act . '.php';

include_once $file;


