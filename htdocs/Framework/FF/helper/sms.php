<?php
function convert($str, $from = 'utf-8', $to = 'gb2312') {
	if(!$str) return '';
	$from = strtolower($from);
	$to = strtolower($to);
	if($from == $to) return $str;
	$from = str_replace('gbk', 'gb2312', $from);
	$to = str_replace('gbk', 'gb2312', $to);
	$from = str_replace('utf8', 'utf-8', $from);
	$to = str_replace('utf8', 'utf-8', $to);
	if($from == $to) return $str;
	$tmp = array();
	if(function_exists('iconv')) {
		if(is_array($str)) {
			foreach($str as $key => $val) {
				$tmp[$key] = iconv($from, $to."//IGNORE", $val);
			}
			return $tmp;
		} else {
			return iconv($from, $to."//IGNORE", $str);
		}
	} else if(function_exists('mb_convert_encoding')) {
		if(is_array($str)) {
			foreach($str as $key => $val) {
				$tmp[$key] = mb_convert_encoding($val, $to, $from);
			}
			return $tmp;
		} else {
			return mb_convert_encoding($str, $to, $from);
		}	
	} else {
		return dconvert($str, $to, $from);
	}
}

function dconvert($str, $from = 'utf-8', $to = 'gb2312') {
	$from = str_replace('utf-8', 'utf8', $from);
	$to = str_replace('utf-8', 'utf8', $to);
	$tmp = file(SITE_PATH . '/Framework/Ext/gb-unicode.table');
	if(!$tmp) return $str;
	$table = array();
	while(list($key, $value) = each($tmp)) {
		if($from == 'utf8') {
			$table[hexdec(substr($value, 7, 6))] = substr($value, 0, 6);
		} else {
			$table[hexdec(substr($value, 0, 6))] = substr($value, 7 , 6);
		}
	}
	unset($tmp);
	$dstr = '';
	if($from == 'utf8') {
		$len = strlen($str);
		$i = 0;
		while($i < $len) {
			$c = ord(substr( $str, $i++, 1 ));
			switch($c >> 4) {
				case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
					$dstr .= substr( $str, $i-1, 1);
				break;
				case 12: case 13:
					$char2 = ord( substr( $str, $i++, 1));
					$char3 = $table[(($c & 0x1F) << 6) | ($char2 & 0x3F)];
					$dstr .= dhex2bin(dechex(  $char3 + 0x8080));
				break;
				case 14:
					$char2 = ord( substr( $str, $i++, 1 ) );
					$char3 = ord( substr( $str, $i++, 1 ) );
					$char4 = $table[(($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | (($char3 & 0x3F) << 0)];
					$dstr .= dhex2bin(dechex($char4 + 0x8080));
				break;
			}
		}
	} else {
		while($str) {
			if(ord(substr($str, 0, 1)) > 127) {
				$utf8 = dch2utf8(hexdec($table[hexdec(bin2hex(substr($str,0,2)))-0x8080]));
				$dutf8 = strlen($utf8);
				for($i = 0; $i < $dutf8; $i += 3) {
					$dstr .= chr(substr($utf8, $i,3));
				}
				$str = substr($str, 2, strlen($str));
			} else {
				$dstr .= substr($str, 0, 1);
				$str = substr($str, 1, strlen($str));
			}
		}
	}
	unset($table);
	return $dstr;
}

function dhex2bin($hexdata) {
	$bindata = '';
	$dhexdata = strlen($hexdata);
	for($i = 0; $i < $dhexdata; $i += 2) {
		$bindata .= chr(hexdec(substr($hexdata, $i, 2)));
	}
	return $bindata;
}

function dch2utf8($c) {
	$str = '';
	if ($c < 0x80) {
		$str .= $c;
	} else if ($c < 0x800) {
		$str .= (0xC0 | $c>>6);
		$str .= (0x80 | $c & 0x3F);
	} else if ($c < 0x10000) {
		$str .= (0xE0 | $c>>12);
		$str .= (0x80 | $c>>6 & 0x3F);
		$str .= (0x80 | $c & 0x3F);
	} else if ($c < 0x200000) {
		$str .= (0xF0 | $c>>18);
		$str .= (0x80 | $c>>12 & 0x3F);
		$str .= (0x80 | $c>>6 & 0x3F);
		$str .= (0x80 | $c & 0x3F);
	}
	return $str;
}

function strip_sms($message) {
	$item = model('setting')->field('sms_sign')->where(array('id'=>1))->find();
	$message = strip_tags($message);
	$message = preg_replace('/&([a-z]{1,});/', '', $message);
	$message = str_replace(' ', '', $message);
	if($item['sms_sign']) $message = $message . $item['sms_sign'];
	return $message;
}

function word_count($string) {
	$string = convert($string, 'utf-8', 'gbk');
	$length = strlen($string);
	$count = 0;
	for($i = 0; $i < $length; $i++) {
		$t = ord($string[$i]);
		if($t > 127) $i++;
		$count++;
	}
	return $count;
}

function send_sms_curl($url){
	//初始化curl
	$ch = curl_init();
	//设置超时
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;	
}

function send_sms($mobile, $message, $usersid = '') {
	$flag = 1;
	$users_model = model('users');
	if($usersid){
		$rsUsers = $users_model->field('Users_Sms')->where(array('Users_ID'=>$usersid))->find();
		if($rsUsers['Users_Sms'] <= 0){
			$flag = 0;
		}
	}
	if($flag==1){
		$mes = $message;
		$message = strip_sms($message);
		$item = model('setting')->field('sms_account,sms_pass')->where(array('id'=>1))->find();
		if(!$item['sms_account'] || !$item['sms_pass'] || !$mobile) return false;
		$word = word_count($message);
		$message = convert($message, 'UTF-8', 'UTF-8');
		$code = '';
		$recode = 0; 
		$smsapi = "api.smsbao.com";
		$charset = "utf8";
		$user = $item['sms_account'];
		$pass = md5($item['sms_pass']);
		$sendurl = "http://".$smsapi."/sms?u={$user}&p={$pass}&m={$mobile}&c=".urlencode($message);
		//$res = 0;
		$res = send_sms_curl($sendurl);
		$statusStr = array(
			"0" => "短信发送成功",
			"-1" => "参数不全",
			"-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
			"30" => "密码错误",
			"40" => "账号不存在",
			"41" => "余额不足",
			"42" => "帐户已过期",
			"43" => "IP地址限制",
			"50" => "内容含有敏感词"
		);
		
		if($res==0) $recode=1;
		$code = $statusStr[$res];
		$Data = array(
			"mobile"=>$mobile,
			"message"=>$mes,
			"word"=>$word,
			"sendtime"=>time(),
			"code"=>$code,
			"usersid"=>$usersid
		);
		model('sms')->insert($Data);
		if($usersid && $res == 0){
			model('users')->where(array('Users_ID'=>$usersid))->update(array('Users_Sms'=>($rsUsers['Users_Sms']-1)));
		}
		return $recode;
	}else{
		return false;
	}
}
?>