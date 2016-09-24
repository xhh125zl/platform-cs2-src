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
//验证是否为数字
function check_number($value, $type = 0) {
	if ($type == 0) {	//验证是否为正数
		if (is_numeric($value) && $value > 0) {
			return true;
		} else {
			return false;
		}
	} else if ($type == 1) {	//验证是否为正整数
		if (is_numeric($value) && $value > 0 && (floor($value) == $value)) {
			return true;
		} else {
			return false;
		}
	}
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
    $productData['Products_Category'] = ','.(int)$productData['firstCate'].','.(int)$productData['firstCate']. ',' . (int)$productData['secondCate'] . ',';
    $productData['Products_BriefDescription'] = htmlspecialchars($productData['Products_BriefDescription'], ENT_QUOTES);	//产品简介
    $productData['Shipping_Free_Company'] = 0;		//免运费  0为全部 ，n为指定快递
    //$productData['Products_Index'] = 1/9999;	//产品排序
    //$productData['Products_Type'] = 0/n;		//产品类型
    //$productData['Products_SoldOut'] = 0/1;		//其他属性  下架
    //$productData['Products_IsPaysBalance'] = 0/1;		//特殊属性  余额支付
    //$productData['Products_IsShow'] = 0/1;		//特殊属性  是否显示
    //$productData['Products_IsVirtual'] = 1;	//订单流程		0,0  1,0  1,1 
	//$productData['Products_IsRecieve'] = 1;
    //$productData['Products_Description'] = htmlspecialchars($productData['Products_Description'], ENT_QUOTES);	//详细介绍
    //$productData['Products_Parameter'] = '[{"name":"","value":""}]';		//产品参数
    $productData['Users_ID'] = $UsersID;
    $productData['Products_status'] = 1;
    $productData['Products_CreateTime'] = time();

    //数据验证	原价、现价、产品利润、赠送积分、产品重量、库存
    if (!check_number($productData['Products_PriceY']) || !check_number($productData['Products_PriceX']) || !check_number($productData['Products_Profit']) || !check_number($productData['Products_Integration'], 1) || !check_number($productData['Products_Weight']) || !check_number($productData['Products_Count'], 1)) {
    	echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
    }
    //推荐后  检测供货价
    if ($productData['is_Tj'] == 1) {
    	if (!check_number($productData['Products_PriceS'])) {
    		echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
    	}
    }

    $postdata['Biz_Account'] = $BizAccount;
    $postdata['productData'] = $productData;
    $resArr = product::addProductTo401($postdata);
    if ($resArr['errorCode'] == 0) {
        echo json_encode(['errorCode' => 0, 'msg' => '上架成功'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['errorCode' => 101, 'msg' => $resArr['msg']]);
    }
} elseif ($_POST['act'] == 'editProduct') {
	//获取数据
	$postdata['Biz_Account'] = $BizAccount;
	$postdata['Products_ID'] = 60;
    $postdata['is_Tj'] = 0;
    $resArr = product::getProductArr($postdata);
}