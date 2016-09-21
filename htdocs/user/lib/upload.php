<?php
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/product.class.php';

function curl_post($uri, $data) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $uri );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$return = curl_exec ( $ch );
	curl_close ( $ch );
	return $return;
}

if ($_POST['act'] == 'uploadFile') {
	$uri = "http://401.wzw.com/user/lib/upload.php";
	// 参数数组
	$data = array (
		'act' => $_POST['act'],
        'data' => $_POST['data'],
        'filepath' => '../../uploadfiles',
        'Users_Account' => $_SESSION['Biz_Account']
	);
	echo curl_post($uri, $data);
} elseif ($_POST['act'] == 'delImg') {
    $uri = "http://401.wzw.com/user/lib/upload.php";
	// 参数数组
	$data = array (
		'act' => $_POST['act'],
        'image_path' => htmlspecialchars(trim($_POST['image_path'])),
        'index' => (int)$_POST['index']
	);
	echo curl_post($uri, $data);
}