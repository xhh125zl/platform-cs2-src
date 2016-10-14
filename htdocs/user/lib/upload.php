<?php
require_once "../config.inc.php";
require_once(CMS_ROOT . '/include/api/product.class.php');

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
    $input_productData = $_POST['productData'];

    //封面图片路径处理
    $imsge_path['ImgPath'] = explode(',' ,$input_productData['Products_JSON']);
    $input_productData['Products_JSON'] = json_encode($imsge_path,JSON_UNESCAPED_UNICODE);
    //商品详情处理  图片，内容
    $des_img = explode(',' ,$input_productData['Products_JSON1']);
    $img_show = '';
    foreach ($des_img as $k => $v) {
    	$img_show .= '<br/><img src="'.$v.'"/>';
    }
    $input_productData['Products_Description'] = preg_replace('/\n|\r/', "<br/>", $input_productData['Products_Description']);
    $input_productData['Products_Description'] = htmlspecialchars($input_productData['Products_Description'].$img_show, ENT_QUOTES);
    //分类处理
    $input_productData['Products_Category'] = ','.(int)$input_productData['firstCate'].','.(int)$input_productData['firstCate']. ',' . (int)$input_productData['secondCate'] . ',';
    //$input_productData['Products_BriefDescription'] = htmlspecialchars($input_productData['Products_BriefDescription'], ENT_QUOTES);	//产品简介
    $input_productData['Shipping_Free_Company'] = 0;	//免运费  0为全部 ，n为指定快递
    //$input_productData['Products_Index'] = 1/9999;	//产品排序
    //$input_productData['Products_Type'] = 0/n;		//产品类型
    $input_productData['Products_SoldOut'] = 0;			//其他属性  不能为空  1: 下架
    //$input_productData['Products_IsPaysBalance'] = 0/1;		//特殊属性  余额支付
    //$input_productData['Products_IsShow'] = 0/1;		//特殊属性  是否显示
    //$input_productData['Products_IsVirtual'] = 1;		//订单流程		0,0  1,0  1,1 
	//$input_productData['Products_IsRecieve'] = 1;
    //$input_productData['Products_Description'] = htmlspecialchars($input_productData['Products_Description'], ENT_QUOTES);	//详细介绍
    //$input_productData['Products_Parameter'] = '[{"name":"","value":""}]';		//产品参数
    $input_productData['Users_ID'] = $UsersID;
    $input_productData['Products_Status'] = 1;

    //数据验证	原价、现价、产品利润、赠送积分、产品重量、库存
    if (!check_number($input_productData['Products_PriceY']) || !check_number($input_productData['Products_PriceX']) || !check_number($input_productData['Products_Profit']) || !check_number($input_productData['Products_Integration'], 1) || !check_number($input_productData['Products_Weight']) || !check_number($input_productData['Products_Count'], 1)) {
    	echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
    }
    //推荐后  检测供货价
    if ($input_productData['is_Tj'] == 1) {
    	if (!check_number($input_productData['Products_PriceS'])) {
    		echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
    	}
    } else {
    	unset($input_productData['B2CProducts_Category']);
    }

    //判断是上架商品还是编辑商品
    if (empty($input_productData['Products_ID'])) {		//上架商品
    	$input_productData['Products_CreateTime'] = time();

    	unset($input_productData['Products_ID']);
    	unset($input_productData['firstCate']);
    	unset($input_productData['secondCate']);
    	unset($input_productData['isSolding']);

    	$postdata['Biz_Account'] = $BizAccount;
    	$postdata['productData'] = $input_productData;
    	$resArr = product::addProductTo401($postdata);
	    if ($resArr['errorCode'] == 0) {
	        echo json_encode(['errorCode' => 0, 'msg' => '上架成功', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/user/admin.php?act=products'], JSON_UNESCAPED_UNICODE);
	    } else {
	        echo json_encode(['errorCode' => 1, 'msg' => '上架失败']);
	    }
    } elseif (ctype_digit($input_productData['Products_ID']) && $input_productData['Products_ID'] > 0) {		//编辑商品
    	//获取旧数据
    	$postdata['Biz_Account'] = $BizAccount;
		$postdata['Products_ID'] = $input_productData['Products_ID'];
		$postdata['is_Tj'] = $input_productData['is_Tj'];
		$resArr = product::getProductArr($postdata);
		unset($postdata);
		$old_productData = $resArr['data'];     //产品参数
		unset($old_productData['Category401']);
		unset($old_productData['b2cCategory']);

		//合并数据
    	$new_productData = array_merge($old_productData,$input_productData);
    	$old_is_Tj = $old_productData['is_Tj'];
    	$is_Tj = $new_productData['is_Tj'];
    	
    	$postdata['productdata'] = $new_productData;
		$resArr = product::editProductTo401($postdata);
		unset($postdata);
		if ($resArr['errorCode'] == 0) {
			//判断推荐的可能，并操作
			if ($is_Tj == 0 && $old_is_Tj == 1 && $new_productData['isSolding'] == 0) {
				//取消推荐
				unset($new_productData['isSolding']);
				$product_id = ['Products_ID' => $new_productData['Products_ID']];
				$b2c_resArr = product::b2cProductDelete($product_id);

			} elseif($is_Tj == 1 && $old_is_Tj == 1) {
				unset($new_productData['isSolding']);
				$postdata['productdata'] = $new_productData;
				$b2c_resArr = product::edit($postdata);
			} elseif ($is_Tj == 1 && $old_is_Tj == 0) {
				//推荐
				unset($new_productData['isSolding']);
				$new_productData['Products_FromId'] = $new_productData['Products_ID'];
				$new_productData['Users_Account'] = $BizAccount;
				$new_productData['Products_CreateTime'] = time();
				$postdata['productdata'] = $new_productData;
				$postdata['productAttr'] = '';
				$b2c_resArr = product::add($postdata);
			}
			if (isset($b2c_resArr)) {
				if ($b2c_resArr['errorCode'] == 0) {
		        	echo json_encode(['errorCode' => 0, 'msg' => '编辑成功', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/user/admin.php?act=products'], JSON_UNESCAPED_UNICODE);
		    	} else {
		    		//推荐编辑不成功，做数据还原处理
			    	$postdata['productdata'] = $old_productData;
					$rock_resArr = product::editProductTo401($postdata);
		    		echo json_encode(['errorCode' => 1, 'msg' => 'b2c编辑失败']);
		    	}
		    } else {
		        echo json_encode(['errorCode' => 0, 'msg' => '编辑成功', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/user/admin.php?act=products'], JSON_UNESCAPED_UNICODE);
		    }
		} else {
			echo json_encode(['errorCode' => 1, 'msg' => '401编辑失败']);
		}

    }
    
}