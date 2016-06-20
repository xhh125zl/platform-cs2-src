<?php
/*返回用于存放bootstrap,ratchet等公用css和js的路径*/
function dist_url()
{	
	$dist_url = base_url().'static/api/dist/';
    return $dist_url;
}


function generate_product_sn($num){
	
	$sn = date('Ymd',time()).$num+1;
	$product_sn = 'RR'.$sn;
	return $product_sn;
}


/**
 *处理购物车中产品销量数据
 *
 */
function handle_products_sales($Cart_list){
	
	$sales_list = array();
	$cartList = json_decode($Cart_list,TRUE);
	
	foreach($cartList as $key=>$value){
		
		if(!isset($sales_list[$key])){
			$sales_list[$key] = $value[0]['ProductsSales'];
		}
		
		foreach($value as $k=>$v){
			$sales_list[$key] += $v['Qty'];	
		}
	}
	$ids = implode(',', array_keys($sales_list));
	$sql = 'UPDATE shop_products set Products_Sales = CASE Products_ID ';
	
	foreach($sales_list as $id=>$item){
		 $sql .= sprintf("WHEN %d THEN %d ", $id, $item);
	}
	
	$sql .= "END WHERE Products_ID in($ids)";
	return $sql;

}


/*计算分销返利利润*/
function count_distribute_interest($distribute_laws,$product_price){
	
    $interest = 0;
    $distribute_bootm_price = $distribute_laws[0]["from"];   //分销起始价格
	$distribute_top_price = 0;	  //分销结束价格
		
	foreach($distribute_laws as $key=>$distribute){
			if($distribute['from'] <= $distribute_bootm_price){
					$distribute_bootm_price =  $distribute['from'];
					$distribute_bottom = $key;
		    }
			if($distribute['to'] > $distribute_top_price){
			  	$distribute_top_price = $distribute['to'];
			  	$distribute_top = $key;
			}
	}
	
	//如果产品价格小于最少分销价格		
	if($product_price < $distribute_bootm_price){
		return $interest;
	}
	
	//如果产品价格大于最大分销价格			
	if($product_price >= $distribute_top_price){
		 $interest = $product_price*($distribute_laws[$distribute_top]['rate']/100);
	   	  return $interest;
	}
	
	if($product_price > $distribute_bootm_price and $product_price < $distribute_top_price){		
    	foreach($distribute_laws as $key=>$distribute){	   
				//如果产品价格符合返利条件
				if($product_price > $distribute['from']&&$product_price <= $distribute['to']){
			 	  $interest = $product_price*($distribute['rate']/100);
				  break;
				}
			
		}
	}
			
	return $interest;
}

/*@desc生成二维码
 *@param  String $data 要生成二维码的数据
 *@param  String $logo 如果中间放logo,则为此logo路径
 *@return String $filename  所生成二维码的相对路径 
 *@author JohnGuo
 */
function generate_qrcode($data){
	
	if(strlen($data) == 0){
		echo '数据不可为空';
		return false;
	}
	
	//引入phpqrcode库文件
	include($_SERVER["DOCUMENT_ROOT"].'/include/library/phpqrcode/phpqrcode.php'); 
	// 生成的文件名 
	$PNG_TEMP_DIR = $_SERVER["DOCUMENT_ROOT"].'/data/temp/';
	//html PNG location prefix
	$PNG_WEB_DIR = '/data/temp/';
	$filename = $PNG_TEMP_DIR.'test.png';
	// 纠错级别：L、M、Q、H 
	$errorCorrectionLevel = 'H';  
	// 点的大小：1到10 
	if(strlen($data)< 15){
		$matrixPointSize = 8; 	
	}else{
	   $matrixPointSize = 5;
	}
	 
	//创建一个二维码文件 

	$filename = $PNG_TEMP_DIR.'test'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
	QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	
	$filename = $PNG_WEB_DIR.'test'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
	
	
	/*增加logo页面
	if($logo != FALSE)
	{	
		$filename_logo = $PNG_TEMP_DIR.'test'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'_logo.png';
		$QR = imagecreatefromstring(file_get_contents($filename));
		$logo = imagecreatefromstring(file_get_contents($logo));
		$QR_width = imagesx($QR);
		$QR_height = imagesy($QR);
		$logo_width = imagesx($logo);
		$logo_height = imagesy($logo);
		$logo_qr_width = $QR_width / 5;
		$scale = $logo_width / $logo_qr_width;
		$logo_qr_height = $logo_height / $scale;
		$from_width = ($QR_width - $logo_qr_width) / 2;
		imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		
		imagepng($QR,$filename_logo);
		$filename = $PNG_WEB_DIR.'test'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'_logo.png';
	}
	*/
	
	return $filename;
}

	
	//获取支付宝有关配置

function get_alipay_conf($DB,$UsersID){
	
	//获取用户的支付宝partner,安全校验码,卖家账号
	require($_SERVER["DOCUMENT_ROOT"].'/include/config/alipay_config.php');
	$setting = $DB->GetRs("setting","*","where id=1");
	
	$alipay_cnf['partner'] =  $setting['alipay_partner'];
	$alipay_cnf['key'] = $setting['alipay_key'];
	$alipay_cnf['seller_email'] = $setting['alipay_selleremail'];
	
	return $alipay_cnf;
	
}

/*获取浮点数*/
if(!function_exists('getFloatValue')){
	function getFloatValue($f,$len){
	  $tmpInt=intval($f);

	  $tmpDecimal=$f-$tmpInt;
	  $str="$tmpDecimal";
	  $subStr=strstr($str,'.');
	  if($tmpDecimal != 0){	
		 if(strlen($subStr)<$len+1)
		{
			$repeatCount=$len+1-strlen($subStr);
			$str=$str."".str_repeat("0",$repeatCount);

		}
		
		$result = $tmpInt."".substr($str,1,1+$len);
	  }else{
		$result =  	$tmpInt.".".str_repeat("0",$len);
	  }

	  return   $result;

	}
}

