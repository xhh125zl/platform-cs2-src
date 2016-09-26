<?php
/**
 * 接口基类
 * base.class.php
 */

class base
{
	static public $apiUrl = APIURL;
	static public $apiVersion = APIVER;
	
	public function __construct()
	{
	}

	static public function request($url, $type = 'post', $data = [])
	{
		$url = self::$apiUrl . self::$apiVersion . $url;
 		$result = curlInterFace($url, $type, $data);

 		return $result;
	}

	static public function getApiUrl()
	{
		return self::$apiUrl . self::$apiVersion;
	}
}