<?php

class userinfo
{
	var $code;
	var $openid;
	var $parameters;
	var $appid;
	var $appsecret;
	var $DB;
	var $UsersID;

	function __construct() 
	{
		//设置curl超时时间
		ini_set("display_errors","On");
		error_reporting(E_ALL); 
		$this->curl_timeout = 30;
	}
	
	function formatBizQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	function createOauthUrlForCodeweb($redirectUrl)
	{
		$urlObj["appid"] = $this->appid;
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_userinfo";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	function createOauthUrlForOpenid($code)
	{
		$urlObj["appid"] = $this->appid;
		$urlObj["secret"] = $this->appsecret;
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = $this->formatBizQueryParaMap($urlObj, false);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}

	function getOpenid($code){
		$url = $this->createOauthUrlForOpenid($code);
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$res = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($res,true);
		
		if(!empty($data['openid'])){
			$_SESSION[$this->UsersID."OpenID"] = $data['openid'];
		}else{
			$_SESSION[$this->UsersID."OpenID"] = '';
		}
		if(!empty($data['access_token'])){
			$_SESSION[$this->UsersID."access_token_web"] = $data['access_token'];
		}else{
			$_SESSION[$this->UsersID."access_token_web"] = '';
		}		
		if(!empty($data['refresh_token'])){
			$_SESSION[$this->UsersID."refresh_token"] = $data['refresh_token'];
		}else{
			$_SESSION[$this->UsersID."refresh_token"] = '';
		}
	}
	
	function refresh_token(){
		if(!empty($_SESSION[$this->UsersID."refresh_token"])){
			$urlObj["appid"] = $this->appid;
			$urlObj["refresh_token"] = $_SESSION[$this->UsersID."refresh_token"];
			$urlObj["grant_type"] = "refresh_token";
			$bizString = $this->formatBizQueryParaMap($urlObj, false);
			$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?".$bizString;
			$ch = curl_init();
			//设置超时
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$res = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($res,true);
			if(!empty($data['access_token'])){
				$_SESSION[$this->UsersID."access_token_web"] = $data['access_token'];
			}else{
				$_SESSION[$this->UsersID."access_token_web"] = '';
			}
			if(!empty($data['openid'])){
				$_SESSION[$this->UsersID."OpenID"] = $data['openid'];
			}else{
				$_SESSION[$this->UsersID."OpenID"] = '';
			}
		}else{
			$_SESSION[$this->UsersID."access_token_web"] = '';
		}		
	}
	
	function getuserinfo($code){
		if(empty($_SESSION[$this->UsersID."access_token_web"]) || empty($_SESSION[$this->UsersID."OpenID"])){
			if(empty($_SESSION[$this->UsersID."refresh_token"])){
				$this->getOpenid($code);
			}else{
				$this->refresh_token();
			}
		}
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$_SESSION[$this->UsersID."access_token_web"]."&openid=".$_SESSION[$this->UsersID."OpenID"]."&lang=zh_CN";
        //初始化curl
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
		curl_close($ch);
		$encoding = mb_detect_encoding($res, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		$res = mb_convert_encoding($res, 'utf-8', $encoding);
		$data = json_decode($res,true);
		return $data;
	}
	
	function loginbyopenid(){
		$isuser = $this->DB->GetRs("user","*","where User_OpenID='".$_SESSION[$this->UsersID."OpenID"]."' and Users_ID='".$this->UsersID."'");
		if($isuser){//OpenID exist,login
			$_SESSION[$this->UsersID."User_ID"]=$isuser["User_ID"];
			$_SESSION[$this->UsersID."User_Name"]=$isuser["User_Name"];
			$_SESSION[$this->UsersID."User_Mobile"]=$isuser["User_Mobile"];
			return true;
		}else{
			$_SESSION[$this->UsersID."User_ID"]='';
			$_SESSION[$this->UsersID."User_Name"]='';
			$_SESSION[$this->UsersID."User_Mobile"]='';
			return false;
		}
	}
	
	function registerbyopenid($ownerid=0,$rootid,$code){
		$uinfo = $this->getuserinfo($code);
		
		$item = $this->DB->GetRs("user_config","ExpireTime","where Users_ID='".$this->UsersID."'");
		$expiretime = $item["ExpireTime"];
		
		if(empty($uinfo["errcode"])){
			$Data = array();
			if($uinfo["nickname"]){
				$Data["User_NickName"] = $this->removeEmoji1($uinfo["nickname"]);
			}
			if($uinfo["sex"]){
				$Data["User_Gender"] = $uinfo["sex"]==1 ? "男" : "女";
			}
			if($uinfo["province"]){
				$Data["User_Province"] = $uinfo["province"];
			}
			if($uinfo["city"]){
				$Data["User_City"] = $uinfo["city"];
			}
			if($uinfo["headimgurl"]){
				$Data["User_HeadImg"] = $uinfo["headimgurl"];
			}
			$maxUser=$this->DB->GetRs("user","User_No","where Users_ID='".$this->UsersID."' order by User_No desc");
			if(empty($maxUser["User_No"])){
				$User_No="600001";
			}else{
				$User_No=$maxUser["User_No"]+1;
			}
			if($ownerid){
				$Data["Owner_ID"] = $ownerid;
			    $Data["Root_ID"] = $rootid;
			}
			
			
			$Data["User_No"] = $User_No;
			$Data["User_Profile"] = 0;
			$Data["User_OpenID"] = $_SESSION[$this->UsersID."OpenID"];
			$Data["User_Password"] = md5("123456");
			$Data["User_PayPassword"] = md5("123456");
			$Data["User_From"] = 0;
			$Data["User_CreateTime"] = time();
			$Data["User_Status"] = 1;
			$Data["User_Remarks"] = "";
			$Data["User_ExpireTime"] = $expiretime==0 ? 0 : ( time() + $expiretime*86400 );
			$Data["Users_ID"] = $this->UsersID;
			
			$Flag=$this->DB->Add("user",$Data);	
			
			if($Flag){
				$_SESSION[$this->UsersID."User_ID"] = $this->DB->insert_id();
				require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/message.func.php');
				user_create_tem($this->UsersID,$this->DB->insert_id(),"http://".$_SERVER["HTTP_HOST"]."/api/".$this->UsersID."/user/");
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//共享地址
	public function GetEditAddressParameters()
	{	
		if(empty($_SESSION[$this->UsersID."access_token_web"])){
			$this->refresh_token();
		}
		if(!empty($_SESSION[$this->UsersID."access_token_web"])){
			$data = array();
			$data["appid"] = $this->appid;
			$data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$time = time();
			$data["timestamp"] = "$time";
			$data["noncestr"] = "wybaoshare";
			$data["accesstoken"] = $_SESSION[$this->UsersID."access_token_web"];
			ksort($data);
			$params = $this->ToUrlParams($data);
			$addrSign = sha1($params);
			
			$afterData = array(
				"addrSign" => $addrSign,
				"signType" => "sha1",
				"scope" => "jsapi_address",
				"appId" => $this->appid,
				"timeStamp" => $data["timestamp"],
				"nonceStr" => $data["noncestr"]
			);
			$parameters = json_encode($afterData);
			return $parameters;
		}else{
			return '';
		}
	}
	
	public function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}
	
	public function removeEmoji1($text) {
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
}
?>
