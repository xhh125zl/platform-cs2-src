<?php

/**
 * 调用接口公共函数
 */

function curlInterFace($url, $method = 'post', $postdata = array()){
	$data = createTokenString($postdata);
	$timestamp = time();
	$key = '458f_$#@$*!fdjisdJDFHUk4%%653154%^@#(FSD#$@0-T';
	$sign = strtoupper(md5(base64_encode($data . $key . $timestamp)));
	$postfields = array('sign' => $sign, 'timestamp' => $timestamp);
	if (!empty($postdata)) {
		$postfields = http_build_query(array_merge($postfields, $postdata));
	}
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	if ($method == 'post') {
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	}
	$output = curl_exec($ch);
	$arr = json_decode($output, true);
	return $arr;
}

/**
 * 新版签名
 * 签名规则:根据传递过来的数组组装成一维数组，去掉sign，去掉空值，进行字典序排列，然后进行http_build_query组装成字符串,然后连接上$key依次进行base64_encode,md5和strtoupper处理
 */
function createTokenString($arr) {
	static $tmp = [] ;
	if (!is_array($arr)) {
		return false;
	}
	foreach ($arr as $key => $val) {
		if (is_array($val)) {
			createTokenString($val);
		} else {
			if ($key == 'sign' || $key == 'timestamp' || strlen($val) < 1) {
				continue;
			}else{
				$tmp[] = $val;
			}
		}
	}
	sort($tmp);
	return http_build_query($tmp);
}


/**
 *去除字符串中的emoji表情
 */
function removeEmoji($text) {

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);

    return $clean_text;
}





function get_property($usersid="",$typeid=0, $ProductsID=0){
	
	global $DB1;
	$html = "";
	$PROPERTY = array();
	if($ProductsID){
		$rsProducts=$DB1->GetRs("shop_products","*","where Users_ID='".$usersid."' and Products_ID=".$ProductsID);
		
		if($rsProducts){
			$JSON=json_decode($rsProducts['Products_JSON'],true);
			if(!empty($JSON["Property"])){
				$PROPERTY = $JSON["Property"];
			}
		}
	}
	
	$DB1->get("shop_property","*","where Users_ID='".$usersid."' and (Type_ID=".$typeid." or Type_ID=0) order by Property_Index asc,Property_ID asc");
	
	
	while($r=$DB->fetch_assoc()){
		if($r["Property_Type"]==0){//单行文本
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input"><input type="text" name="JSON[Property]['.$r["Property_Name"].']" value="'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) ? $PROPERTY[$r["Property_Name"]] : "").'" class="form_input" size="35" /></span>
			  <div class="clear"></div>
			</div>';
		}elseif($r["Property_Type"]==1){//多行文本
			
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input"><textarea name="JSON[Property]['.$r["Property_Name"].']" class="briefdesc">'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) ? $PROPERTY[$r["Property_Name"]] : "").'</textarea></span>
			  <div class="clear"></div>
			</div>';
			
		}elseif($r["Property_Type"]==2){//下拉框
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input"><select name="JSON[Property]['.$r["Property_Name"].']" style="width:180px">';
			  $List=json_decode($r["Property_Json"],true);
			  foreach($List as $key=>$value){
				  $html .='<option value="'.$value.'"'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) && $value==$PROPERTY[$r["Property_Name"]] ? " selected" : "").'>'.$value.'</option>';
			  }
			  $html .='</select></span>
			  <div class="clear"></div>
			</div>';
		}elseif($r["Property_Type"]==3){//多选框
			
		
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input">';
			  $List=json_decode($r["Property_Json"],true);
			  
			 foreach($List as $key=>$value){
				  
				  $html .='<input type="checkbox" name="JSON[Property]['.$r["Property_Name"].'][]" value="'.$value.'"'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) && in_array($value,$PROPERTY[$r["Property_Name"]]) ? " checked" : "").'>&nbsp;'.$value.'&nbsp;&nbsp;&nbsp;&nbsp;';
			  }
			  $html .='</span>
			  <div class="clear"></div>
			</div>';
		}else{//单选按钮
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input">';
			  $List=json_decode($r["Property_Json"],true);
			  foreach($List as $key=>$value){
				  $html .='<input type="radio" name="JSON[Property]['.$r["Property_Name"].']" value="'.$value.'"'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) && $value==$PROPERTY[$r["Property_Name"]] ? " checked" : "").'/>&nbsp;'.$value.'&nbsp;&nbsp;&nbsp;&nbsp;';
			  }
			  $html .='</span>
			  <div class="clear"></div>
			</div>';
		}
	}
	return $html;
}


/**
 * 截取UTF-8编码下字符串的函数
 *
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 *
 * @return  string
 */
function sub_str($str, $length = 0, $append = true)
{
	$ec_charset = 'utf-8';
    $str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength)
    {
        return $str;
    }
    elseif ($length < 0)
    {
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }

    if (function_exists('mb_substr'))
    {
        $newstr = mb_substr($str, 0, $length, $ec_charset);
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str, 0, $length,$ec_charset);
    }
    else
    {
        //$newstr = trim_right(substr($str, 0, $length));
        $newstr = substr($str, 0, $length);
    }

    if ($append && $str != $newstr)
    {
        $newstr .= '...';
    }

    return $newstr;
}



function filter($var)
{
	if($var == '')
	{
		return false;
	}
	return true;
}


/**
 *将积分移入不可用积分
 *@param $UsersID
 *@param $UserID  用户ID
 *@param $Integral 移动积分量 
 */
function  add_userless_integral($UsersID,$User_ID,$Integral){
	 
	 $Data = array(
	 			"User_Integral"=>'User_Integral-'.$Integral,
				"User_UseLessIntegral"=>'User_UseLessIntegral+'.$Integral,
	 		);
			
	  global $DB1;
	  $condition = "where Users_ID='".$UsersID."' and User_ID=".$User_ID;
	  $Flag = $DB1->Set('user',$Data,$condition,'User_Integral,User_UseLessIntegral');
	
	  return $Flag;
}
	
/**
 *将不可用积分返还到可用积分 
 *@param $UsersID
 *@param $UserID  用户ID
 *@param $Integral 移动积分量 
 */	
function  remove_userless_integral($UsersID,$User_ID,$Integral){
	 
	 $Data = array(
	 			"User_Integral"=>'User_Integral+'.$Integral,
				"User_UseLessIntegral"=>'User_UseLessIntegral-'.$Integral,
	 		);
			
	  global $DB1;
	  $condition = "where Users_ID='".$UsersID."' and User_ID=".$User_ID;
	  $Flag = $DB1->Set('user',$Data,$condition,'User_Integral,User_UseLessIntegral');
	
	  return $Flag;
}	

 
/**
 *根据昵称关键词获取用户id串
 */ 
function find_userids_by_nickname($Users_ID,$NickName){
	global $DB1;
	$condition = "where Users_ID ='".$Users_ID."' and User_NickName like '%".$NickName."%'";
	$rsIds = $DB1->get('user','User_ID',$condition);
	$rsIDList = $DB1->toArray($rsIds);
	
	if(!empty($rsIDList)){
		$id_array = array();
		foreach($rsIDList as $key=>$item){
			$id_array[] = $item['User_ID'];	   
		}
		
		$result  =implode(',',$id_array);
		
	}else{
		$result = false;
	}
	
	return $result;
	
}
 
/**
 *根据产品名获取产品id串
 */ 
function find_productids_by_Name($Users_ID,$Name){
	
	global $DB1;
	$condition = "where Users_ID ='".$Users_ID."' and Products_Name like '%".$Name."%'";
	
	$rsIds = $DB1->get('shop_products','Products_ID',$condition);
	$rsIDList = $DB1->toArray($rsIds);
	
	if(!empty($rsIDList)){
		$id_array = array();
		foreach($rsIDList as $key=>$item){
			$id_array[] = $item['Products_ID'];	   
		}
		
		$result  =implode(',',$id_array);
	}else{
		$result = false;
	}
	
	
	return $result;
	

}

/**
 * 创建以逗号分隔值字符串
 * @param  Array  $val_list 值列表 
 * @param  String $ids     以逗号分隔的id字符串
 * @return String $html    以逗号分隔的值字符串
 */
function build_comma_html($val_list,$ids){
	$html = '';
	if(strlen($ids) >0 ){
		
		$id_list = explode(',',$ids);
		$html_list = array();
		foreach($id_list as $k=>$id){
			$html_list [] = $val_list[$id];
		}
		
		$html = implode(',',$html_list);
	}
	
	return $html;
}


/**
 *若值为空，则输出默认值
 *@param $val 需要输出的值
 *@pram  $default 默认值
 *@return $result 需要输出的最终值
 */
 
function empty_default($val,$default){
	$result = !empty($val)?$val:$default;
	return $result;
}


/**
 *获取地区列表
 */
function get_regison_list(){
	
	global $DB1;
	$rsAreas = $DB1->get('area','*','where area_deep = 1');
	$province_all_array = $DB1->toArray($rsAreas);
	foreach ($province_all_array as $a) {
     
            if ($a['area_deep'] == 1 && $a['area_region'])
                $region[$a['area_region']][] = $a['area_id'];
     
	}
	
	return $region;
}

/**
 * 获取所有区域信息
 * @param  int     $deep 区域深度
 * @return Array   $arr 区域数组
 */
function get_all_area($deep){
	
    global $DB1;
	$rsAreas = $DB1->get('area','*','where area_deep <='.$deep);
	
	$area_all_array = $DB1->toArray($rsAreas);
	
	
	foreach ($area_all_array as $a) {
            $data['name'][$a['area_id']] = $a['area_name'];
            $data['parent'][$a['area_id']] = $a['area_parent_id'];
            $data['children'][$a['area_parent_id']][] = $a['area_id'];

            if ($a['area_deep'] == 1 && $a['area_region'])
                $data['region'][$a['area_region']][] = $a['area_id'];
     }
	 
	$arr = array();
	
    foreach ($data['children'] as $k => $v) {
          foreach ($v as $vv) {
               $arr[$k][] = array($vv, $data['name'][$vv]);
          }
		  
	}
	
	return $arr;
			
}


/**
 *默认地址被删除，设置另一个地址为默认
 */
function set_anoter_default($UsersID,$User_ID){
	
	global $DB1;
	$condition = "where Users_ID='".$UsersID."' and User_ID='".$User_ID."'";
    $rsAddress = $DB1->GetRs("user_address","*",$condition);
	if($rsAddress){
		$Address_ID = 	$rsAddress['Address_ID'];	
		$condition = "where Users_ID='".$UsersID."' and User_ID='".$User_ID."' and Address_ID=".$Address_ID;
	
		$DB1->Set("user_address",array('Address_Is_Default'=>1),$condition);
	}
}



/**
 *确定商品所在购物车cartkey
 */
function get_cart_key($UsersID,$virtual,$needcart){
	if($virtual==1){
		$cart_key = $UsersID."Virtual";
	}else{
		if($needcart==1){
			$cart_key = $UsersID."CartList";
		}else{
			$cart_key = $UsersID."BuyList";
		}
	}
	
	return $cart_key;
}


/**
 *cloud确定商品所在购物车cartkey
 */
function get_cart_key_cloud($UsersID,$virtual,$needcart){
	if($virtual==1){
		$cart_key = $UsersID."CloudVirtual";
	}else{
		if($needcart==1){
			$cart_key = $UsersID."CloudCart";
		}else{
			$cart_key = $UsersID."CloudBuy";
		}
	}
	return $cart_key;
}
/*
   生成云购码 
   CountNum @ 生成个数
   len 	    @ 生成长度
   sid	    @ 商品ID
*/
function content_get_go_codes($CountNum = null, $len = null, $sid = null){
	global $DB;
	$table = 'cloud_shopcodes';
	
	$num = ceil($CountNum/$len);
	$code_i = $CountNum;
	if($num == 1) {
		$codes = array();
		for($i = 1; $i <= $CountNum; $i++) {
			$codes[$i] = 10000000 + $i;
		}
		shuffle($codes);
		$codes = serialize($codes);
		$query = $DB->query("INSERT INTO `$table` (`s_id`, `s_cid`, `s_len`, `s_codes`,`s_codes_tmp`) VALUES ('$sid', '1','$CountNum','$codes','$codes')");
		unset($codes);
		return $query;
	}
	$query_1 = true;
	$sql1 = '';
	$sql1 = "INSERT INTO `$table` (`s_id`, `s_cid`, `s_len`, `s_codes`,`s_codes_tmp`) VALUES";
	for($k = 1; $k < $num; $k++) {
		$codes = array();
		for($i = 1; $i <= $len; $i++) {
			$codes[$i] = 10000000 + $code_i;
			$code_i--;
		}
		shuffle($codes);
		$codes = serialize($codes);
		$sql1 .= " ('$sid', '$k','$len','$codes','$codes'),";
		unset($codes);
	}
	
	$sql1 = substr($sql1, 0, strlen($sql1)-1).';';
	$query_1 = $DB->query($sql1);
	$CountNum = $CountNum - (($num-1) * $len);
	$codes = array();	
	for($i = 1; $i <= $CountNum; $i++) {
		$codes[$i] = 10000000 + $code_i;	
		$code_i--;
	}
	shuffle($codes);
	$codes = serialize($codes);
	$query_2 = $DB->query("INSERT INTO `$table` (`s_id`, `s_cid`,`s_len`, `s_codes`,`s_codes_tmp`) VALUES ('$sid', '$num','$CountNum','$codes','$codes')");
	unset($codes);
	return $query_1 && $query_2;
}
//购买一个云码动作处理
function get_cloud_code($CartList = array()) {
	global $DB;
	$codes = $shopcodes = array();
	$ProductsIDS = implode(',', array_keys($CartList));
	$IDS = '';
	$DB->Get('cloud_shopcodes','id,s_id,s_codes_tmp','where s_id in ('.$ProductsIDS.') and s_codes_tmp<>"" order by s_cid desc' );
	while($r = $DB->fetch_assoc()){
		$shopcodes[$r['s_id']][] = $r;
	}
	
	$sql = "UPDATE cloud_shopcodes SET s_codes_tmp = CASE id";
	foreach($CartList as $key => $value){
		$codes[$key] = array();
		foreach($value as $val){
			$for_num = count($shopcodes[$key]);//总循环次数
			$code_sub = $val['Qty'];//剩余量
			
			for($j=0;$j<$for_num; $j++){
				if($code_sub<=0){
					break 2;
				}else{
					$s_codes = unserialize($shopcodes[$key][$j]['s_codes_tmp']);
					if(count($s_codes)>=$code_sub){
						$arr_temp = array_slice($s_codes, 0, $code_sub);
						$codes_tmp_sub = count($s_codes)==$code_sub ? '' : serialize(array_slice($s_codes, $code_sub, (count($s_codes)-$code_sub)));
						$sql .= ' WHEN '.$shopcodes[$key][$j]['id'].' THEN "'.$codes_tmp_sub.'"';
						$IDS .= $shopcodes[$key][$j]['id'].',';
						$code_sub = 0;//取完
						$codes[$key] = array_merge($codes[$key], $arr_temp);
						break 2;
					}else{
						$arr_temp = $s_codes;
						$codes[$key] = array_merge($codes[$key], $arr_temp);
						$code_sub = $code_sub - count($s_codes);
						$sql .= ' WHEN '.$shopcodes[$key][$j]['id'].' THEN ""';
						$IDS .= $shopcodes[$key][$j]['id'].',';
					}
				}
			}
		}
	}
	$IDS = trim($IDS, ',');
	$sql .= ' END WHERE id IN ('.$IDS.')';
	$DB->query($sql);
	return $codes;
}


/**
 * 检查是否为手机号合法
 * @param string $mobile 手机号
 * @return boolean
 * @author sxf
 */
function is_mobile($mobile) {
	if (empty($mobile)) return false;

	$search ='/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
	if (preg_match($search,$mobile)) {
		return true;
	}

	return false;
}