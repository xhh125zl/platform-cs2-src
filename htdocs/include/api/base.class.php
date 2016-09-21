<?php
/**
 * 接口基类
 * base.class.php
 */

class base
{
	static protected $apiUrl = 'http://if.wzw.com/api/';
	static protected $apiVersion = 'v1';
	
	public function __construct()
	{
	}

	static public function request($url, $type = 'post', $data = [])
	{
		$url = self::$apiUrl . self::$apiVersion . $url;
 		$result = curlInterFace($url, $type, $data);

 		return $result;
	}

}