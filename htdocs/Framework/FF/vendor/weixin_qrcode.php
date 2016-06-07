<?php
namespace vendor;
class weixin_qrcode{
	var $usersid;
	var $access_token;
	var $curl_timeout;

	function __construct($usersid){
		$this->usersid = $usersid;
		$this->curl_timeout = 30;
		require_once(__DIR__ . '/weixin_token.php');
		$weixin_token = new weixin_token($usersid);
		$this->access_token = $weixin_token->get_access_token();		
	}
	
	private function curl_post($url, $postdata){
		$postdata = json_encode($postdata);
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
	
	private function get_ticket($sceneid){
		$ticket = '';
		if($this->access_token){
			$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
			$postdata = array(
				'action_name'=>'QR_LIMIT_STR_SCENE',
				'action_info'=>array(
					'scene'=> array(
						'scene_str'=>$sceneid
					)
				)
			);
			$data = $this->curl_post($url,$postdata);
			if(!empty($data["errcode"])){
				$ticket = '';
			}else{
				$ticket = $data["ticket"];
			}
		}
		
		return $ticket;
		
	}
	
	private function downloadImageFromWeiXin($url, $filename){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$file = curl_exec($ch);
		curl_close($ch);
		$flag = true;
        $write = @fopen ( $filename, "w" );
        if ($write == false) {
            $flag = false;
        }
        if (fwrite ( $write, $file ) == false) {
            $flag = false;
        }
        if (fclose ( $write ) == false) {
            $flag = false;
        }
		return $flag;
	}

	public function get_qrcode($sceneid){
		$file_path = SITE_PATH . '/data/temp/' . $sceneid . '.jpg';
		$flag = false;
		if(!is_file($file_path)){
			$ticket = $this->get_ticket($sceneid);
			if($ticket){
				$img_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($ticket);
		    	$flag = $this->downloadImageFromWeiXin($img_url, $file_path);
			}
		}else{
			$flag = true;
		}
		return $flag ? SITE_URL . '/data/temp/' . $sceneid . '.jpg' : '';
	}
}
?>
