<?php
/**
 * 加载配置文件 支持格式转换 仅支持一级配置
 * @param string $file 配置文件名
 * @param string $parse 配置解析方法 有些格式需要用户自己解析
 * @return array
 */
function load_config($file){
    $ext = pathinfo($file,PATHINFO_EXTENSION);
    switch($ext){
        case 'php':
            return include $file;
        case 'ini':
            return parse_ini_file($file);
        case 'xml': 
            return (array)simplexml_load_file($file);
        case 'json':
            return json_decode(file_get_contents($file), true);
    }
}
function array_map_recursive($filter, $data) {
    $result = array();
    foreach ($data as $key => $val) {
        $result[$key] = is_array($val)
         ? array_map_recursive($filter, $val)
         : call_user_func($filter, $val);
    }
    return $result;
}


/**
 * 优化的require_once
 * @param string $filename 文件地址
 * @return boolean
 */
function require_cache($filename) {
    static $_importFiles = array();
    if (!isset($_importFiles[$filename])) {
        if (file_exists($filename)) {
            require $filename;
            $_importFiles[$filename] = true;
        } else {
            $_importFiles[$filename] = false;
        }
    }
    return $_importFiles[$filename];
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}
function url($url = '', $vars = '', $suffix = false, $domain = false) {
	if(isset($_GET['UsersID'])) {
		$UsersID = $_GET['UsersID'];
	}else {
		$users_info = model('pc_setting')->field('Users_ID')->where(array('site_url'=>$_SERVER['HTTP_HOST']))->find();
		if($users_info) {
			$UsersID = $users_info['Users_ID'];
		}
	}
	
	if(isset($UsersID)) {
		if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
			parse_str($vars, $vars);
		}else if(!is_array($vars)) {
			$vars = array();
		}
		$vars['UsersID'] = $UsersID;
	}
	if(isset($_GET['OwnerID'])) {
		if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
			parse_str($vars, $vars);
		}else if(!is_array($vars)) {
			$vars = array();
		}
		$vars['OwnerID'] = $_GET['OwnerID'];
	}
	return _url($url, $vars, $suffix, $domain);
}
/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl() {
    if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
        return true;
    }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
        return true;
    }
    return false;
}

/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time=0, $msg='') {
    //多行URL地址支持
    $url        = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}


/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
    if (is_object($mix)) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root='qq544731308', $item='item', $attr='', $id='id', $encoding='utf-8') {
    if(is_array($attr)){
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr   = trim($attr);
    $attr   = empty($attr) ? '' : " {$attr}";
    $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml   .= "<{$root}{$attr}>";
    $xml   .= data_to_xml($data, $item, $id);
    $xml   .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item='item', $id='id') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if(is_numeric($key)){
            $id && $attr = " {$id}=\"{$key}\"";
            $key  = $item;
        }
        $xml    .=  "<{$key}{$attr}>";
        $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml    .=  "</{$key}>";
    }
    return $xml;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
 * @return mixed
 */
function get_client_ip($type = 0,$adv = false) {
    $type =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code) {
    static $_status = array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
    );
    if(isset($_status[$code])) {
        header('HTTP/1.1 '.$code.' '.$_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:'.$code.' '.$_status[$code]);
    }
}

// 不区分大小写的in_array实现
function in_array_case($value,$array){
    return in_array(strtolower($value),array_map('strtolower',$array));
}

function PriceFormatForList($price) {
    if ($price >= 10000) {
       return number_format(floor($price/100)/100,2,'.','').'万';
    } else {
     return '￥'.$price;
    }
}
if(!function_exists('getFloatValue')) {
	function getFloatValue($f, $len) {
	  $tmpInt = intval($f);

	  $tmpDecimal = $f - $tmpInt;
	  $str = $tmpDecimal;
	  $subStr = strstr($str, '.');
	  if($tmpDecimal != 0) {	
		 if(strlen($subStr) < $len + 1) {
			$repeatCount = $len + 1 - strlen($subStr);
			$str = $str . '' . str_repeat('0', $repeatCount);
		}
		$result = $tmpInt . '' . substr($str, 1, 1 + $len);
	  }else {
		$result = $tmpInt . '.' . str_repeat('0', $len);
	  }
	  return $result;
	}
}
if ( ! function_exists('read_file')) {
	function read_file($file) {
		if ( ! file_exists($file)) {
			return FALSE;
		}

		if (function_exists('file_get_contents')) {
			return file_get_contents($file);
		}

		if ( ! $fp = @fopen($file, FOPEN_READ)) {
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0) {
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}
	
}
if ( ! function_exists('build_pre_order_no'))
{
	function build_pre_order_no() {
		/* 选择一个随机的方案 */
		mt_srand((double) microtime() * 1000000);
		return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}
if ( ! function_exists('build_withdraw_sn'))
{	
	/**
	* 得到提现流水号
	* @return  string
	*/
	function build_withdraw_sn() {
		/* 选择一个随机的方案 */
		//mt_srand((double) microtime() * 1000000);

		return 'WD' . date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
	}

}
if(!function_exists('get_dropdown_collection')){
	//生成collection dropdown数组
	function get_dropdown_collection($data, $id_field, $value_field = ''){
	    $drop_down = array();
	
		foreach($data as $key => $item){
			if(strlen($value_field) > 0 ){
				$drop_down[$item[$id_field]] = $item[$value_field];
			}else{
				$drop_down[$item[$id_field]] = $item;
			}
		}
		return $drop_down;	
	}
}
if (!function_exists('getmonth')) {//获取前后月的第一天
	function getmonth($type=0){
		$month = date('n');
		$year = date('Y');
		if($type==0){//上个月一号
			if($month==1){
				$month = 12;
				$year = $year - 1;
			}else{
				$month = $month - 1;
			}
		}else{//下个月一号
			if($month==12){
				$month = 1;
				$year = $year + 1;
			}else{
				$month = $month + 1;
			}
		}
		return strtotime($year.'-'.$month.'-01');
	}
}
if(!function_exists('getSystemYearArr')) {
	/**
	 * 获得系统年份数组
	 */
	function getSystemYearArr(){
		$now = date('Y', time());
		$old10 = $now-50;
		for($i=$old10;$i<=$now;$i++){
			$year_arr[$i] = $i;
		}
		return $year_arr;
	}
}
if(!function_exists('getSystemMonthArr')) {
	/**
	 * 获得系统月份数组
	 */
	function getSystemMonthArr(){
		$month_arr = array('1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12');
		return $month_arr;
	}
}
if(!function_exists('getSystemWeekArr')) {
	/**
	 * 获得系统周数组
	 */
	function getSystemWeekArr() {
		$week_arr = array('1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六','7'=>'周日');
		return $week_arr;
	}
}
if ( ! function_exists('round_pad_zero')) {
	/**
	* 浮点数四舍五入补零函数
	* 
	* @param float $num
	*        	待处理的浮点数
	* @param int $precision
	*        	小数点后需要保留的位数
	* @return float $result 处理后的浮点数
	*/
	function round_pad_zero($num, $precision) {
		if ($precision < 1) {  
				return round($num, $precision);  
			}  
		
			$r_num = round($num, $precision);  
			$num_arr = explode('.', "$r_num");  
			if (count($num_arr) == 1) {  
				return "$r_num" . '.' . str_repeat('0', $precision);  
			}  
			$point_str = "$num_arr[1]";  
			if (strlen($point_str) < $precision) {  
				$point_str = str_pad($point_str, $precision, '0');  
			}  
			return $num_arr[0] . '.' . $point_str;  
	}  
}
include(__DIR__ . '/goods.php');
include(__DIR__ . '/flow.php');
include(__DIR__ . '/shipping.php');
include(__DIR__ . '/distribute.php');
include(__DIR__ . '/virtual.php');
include(__DIR__ . '/sms.php');