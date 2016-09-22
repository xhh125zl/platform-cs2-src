<?php
require_once "../config.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/product.class.php';

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
        'Users_Account' => $BizAccount
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
} elseif ($_POST['act'] == 'addProduct') {

    //数据处理
    $productData = $_POST['productData'];
    //图片路径处理
    $imsge_path['ImgPath'] = explode(',' ,$productData['Products_JSON']);
    $productData['Products_JSON'] = json_encode($imsge_path,JSON_UNESCAPED_UNICODE);
    //分类处理
    $productData['Products_Category'] = ','.(int)$productData['firstCate']. ',' . $productData['secondCate'] . ',';
    //
    $productData['Products_IsHot'] = isset($productData['Products_IsHot']) ? 1 : 0;
    $productData['Products_IsRecommend'] = isset($productData['Products_IsRecommend']) ? 1 : 0;
    $productData['Products_IsNew'] = isset($productData['Products_IsNew']) ? 1 : 0;

    $postdata['Biz_Account'] = $BizAccount;
    $postdata['productData'] = $productData;

    $resArr = product::addProductTo401($postdata);
    if ($resArr['errorCode'] == 0) {
        echo json_encode(['errorCode' => 0, 'msg' => '上架成功'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['errorCode' => 101, 'msg' => $resArr['msg']]);
    }
}