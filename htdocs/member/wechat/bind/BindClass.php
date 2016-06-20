<?php
require_once('Snoopy.class.php');
class Bind{
	private $username; 
	private $password; 
	private $cookie; 
	
	public function bind($username,$password){ 
		$this->username = $username; 
		$this->password = $password; 
		$this->dologin(); 
	} 
	
	private function dologin(){ 
		$header = array( 
			'Accept:application/json, text/javascript, */*; q=0.01', 
			'Accept-Encoding:gzip,deflate,sdch', 
			'Accept-Language:zh-CN,zh;q=0.8', 
			'Connection:keep-alive', 
			'Host:mp.weixin.qq.com', 
			'Origin:https://mp.weixin.qq.com', 
			'Referer:https://mp.weixin.qq.com/', 
		); 
	
		$PostData = array( 
			   "username" => $this->username,
			   "pwd" => md5($this->password),
			   "f" => "json" 
		   ); 
		$useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36"; 
		$url = "https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN"; 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header); 
		curl_setopt($ch, CURLOPT_USERAGENT,$useragent); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $PostData); 
		curl_setopt($ch, CURLOPT_HEADER, 1); 
		curl_setopt($ch, CURLOPT_COOKIE, $this->cookie); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_SSLVERSION, 3); //设定SSL版本
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch); 
		curl_close($ch);
		return $result; 
	} 
} 
?>