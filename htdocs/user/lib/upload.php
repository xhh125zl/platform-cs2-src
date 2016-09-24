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
//验证是否为数字	默认检查正浮点数  1：非负浮点数  2：检查正整数 3: 检查非负整数
function check_number($value, $type = 0) {
	$number_regular = '';
	if ($type == 0) {
        $number_regular = $value > 0 ;
    } else if ($type == 1) {
        $number_regular = $value >= 0 ;
    } else if ($type == 2) {
        $number_regular = $value > 0 && $value%1 == 0 ;
    } else if ($type == 3) {
        $number_regular = $value >= 0 && $value%1 == 0 ;
    }

	if (is_numeric($value) && $number_regular) {
		return true;
	} else {
		return false;
	}
}

if ($_POST['act'] == 'uploadFile') {
	$uri = rtrim(IMG_SERVER, '/').'/user/lib/upload.php';
	// 参数数组
	$data = array (
		'act' => $_POST['act'],
        'data' => $_POST['data'],
        'filepath' => '../../uploadfiles',
        'Users_Account' => $BizAccount
	);
	echo curl_post($uri, $data);

} elseif ($_POST['act'] == 'delImg') {
    $uri = rtrim(IMG_SERVER, '/').'/user/lib/upload.php';
	// 参数数组
	$data = array (
		'act' => $_POST['act'],
        'image_path' => htmlspecialchars(trim($_POST['image_path'])),
        'index' => (int)$_POST['index']
	);
	echo curl_post($uri, $data);

} elseif ($_POST['act'] == 'addEditProduct') {
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

    //数据验证	原价、现价、产品利润、赠送积分、产品重量、库存
    if (!check_number($productData['Products_PriceY']) || !check_number($productData['Products_PriceX']) || !check_number($productData['Products_Profit']) || !check_number($productData['Products_Integration'], 1) || !check_number($productData['Products_Weight']) || !check_number($productData['Products_Count'], 1)) {
    	echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
    }
    //推荐后  检测供货价
    if ($productData['is_Tj'] == 1) {
    	if (!check_number($productData['Products_PriceS'])) {
    		echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
    	}
    } else {
    	unset($productData['B2CProducts_Category']);
    }

    //判断是上架商品还是编辑商品
    if (empty($productData['Products_ID'])) {		//上架商品
    	$productData['Products_CreateTime'] = time();

    	unset($productData['Products_ID']);
    	unset($productData['firstCate']);
    	unset($productData['secondCate']);
    	unset($productData['isSolding']);
    	unset($productData['old_is_Tj']);

    	$postdata['Biz_Account'] = $BizAccount;
    	$postdata['productData'] = $productData;
    	$resArr = product::addProductTo401($postdata);
	    if ($resArr['errorCode'] == 0) {
	        echo json_encode(['errorCode' => 0, 'msg' => $resArr['msg']], JSON_UNESCAPED_UNICODE);
	    } else {
	        echo json_encode(['errorCode' => 1, 'msg' => $resArr['msg']]);
	    }
    } elseif (ctype_digit($productData['Products_ID']) && $productData['Products_ID'] > 0) {		//编辑商品

    	//判断推荐的可能，并操作
    	if ($productData['is_Tj'] == 0 && $productData['old_is_Tj'] == 0) {
    		unset($productData['isSolding']);
			$postdata['productdata'] = $productData;
	    	$resArr = product::editProductTo401($postdata);

		} elseif ($productData['is_Tj'] == 0 && $productData['old_is_Tj'] == 1 && $productData['isSolding'] == 0) {
			//取消推荐
			unset($productData['isSolding']);
			$product_id = ['Products_ID' => $productData['Products_ID']];
			$resArr = product::b2cProductDelete($product_id);

		} elseif($productData['is_Tj'] == 1 && $productData['old_is_Tj'] == 1) {
			unset($productData['isSolding']);
			$postdata['productdata'] = $productData;
			$resArr = product::edit($data);

		} elseif ($productData['is_Tj'] == 1 && $productData['old_is_Tj'] == 0) {
			//推荐
			/*unset($productData['isSolding']);
			$productData['Products_FromId'] = $productData['Products_ID'];
			$Data['Users_Account'] = $_SESSION['Users_Account'];

			$data = [
				'productdata' => $Data, 
				'productAttr' => $product_attr_arr
			];
			$arrRes = product::add($data);*/
		}

		if ($resArr['errorCode'] == 0) {
	        echo json_encode(['errorCode' => 0, 'msg' => $resArr['msg']], JSON_UNESCAPED_UNICODE);
	    } else {
	        echo json_encode(['errorCode' => 1, 'msg' => $resArr['msg']]);
	    }
	    	
    } else {
    	echo json_encode(['errorCode' => 1, 'msg' => '22222222']);die;
    }
    
}