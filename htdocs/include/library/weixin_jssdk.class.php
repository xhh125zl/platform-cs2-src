<?php
class weixin_jssdk{
	var $db;
	var $usersid;
	var $access_token;
	var $curl_timeout;
	var $noncestr;
	var $timestamp;

	function __construct($DB,$usersid){
		$this->db = $DB;
		$this->usersid = $usersid;
		$this->curl_timeout = 30;
		$this->noncestr = 'wybaoshare';
		$this->timestamp = time();
		$this->access_token = '';
	}
	
	private function curl_get($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$res = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($res,true);
		return $data;
	}
	
	private function jssdk_get_ticket(){
		$get = 1;
		$ticket = "";
		
		$item = $this->db->GetRs("users_access_token","jssdk_ticket,jssdk_expires_in","where usersid='".$this->usersid."' order by jssdk_expires_in desc");
		if($item){
			$diff = intval($item["jssdk_expires_in"]) - 300;
			if($item["jssdk_ticket"] && $diff>=time()){
				$get = 0;
				$ticket = $item["jssdk_ticket"];
			}
		}
		
		if($get==1){
			require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_token.class.php');
			$weixin_token = new weixin_token($this->db,$this->usersid);
			$this->access_token = $weixin_token->get_access_token();
			if($this->access_token){
				$data = $this->curl_get("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$this->access_token."&type=jsapi");
				
				if(!empty($data["ticket"])){
					$ticket = $data["ticket"];
					$Data = array(
						"jssdk_ticket"=>$data["ticket"],
						"jssdk_expires_in"=>time()+$data["expires_in"]
					);
					$this->db->Set("users_access_token",$Data,"where usersid='".$this->usersid."'");
				}
			}
		}
		
		return $ticket;
	}
	
	public function jssdk_get_signature(){//获取自定义分享配置信息
		$users = $this->db->GetRs("users","Users_WechatAppId,Users_WechatAppSecret","where Users_ID='".$this->usersid."'");
		if(!$users["Users_WechatAppId"] || !$users["Users_WechatAppSecret"]){
			return "";
		}
		$ticket = $this->jssdk_get_ticket();		
		if($ticket==""){
			return "";
		}else{			
			$tmpArr = array(
				"jsapi_ticket"=>$ticket,
				"noncestr"=>$this->noncestr,
				"timestamp"=>$this->timestamp,
				"url"=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
			);
			
			ksort($tmpArr);
			$html = "";
			foreach($tmpArr as $k=>$v){
				$html = $html."&".$k."=".$v;
			}
			$s = substr($html,1);
			$tmpArr["signature"] = sha1($s);
			$tmpArr["appId"] = $users["Users_WechatAppId"];
			return $tmpArr;
		}    
	}
}
?>