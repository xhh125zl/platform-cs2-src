<?php
class weixin_material{
	var $db;
	var $usersid;
	var $access_token;
	var $curl_timeout;

	function __construct($DB,$usersid){
		$this->db = $DB;
		$this->usersid = $usersid;
		$this->curl_timeout = 30;
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_token.class.php');
		$weixin_token = new weixin_token($DB,$usersid);
		$this->access_token = $weixin_token->get_access_token();		
	}
	
	private function curl_post($url,$postdata){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $res = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($res,true);
		return $data;
	}
	
	public function upload_files($type,$postdata){
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=" .$this->access_token. "&type=".$type;
		return $this->curl_post($url,$postdata);
	}
}
?>
