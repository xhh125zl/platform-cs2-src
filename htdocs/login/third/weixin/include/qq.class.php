<?php
/**
------------------------------------
set_login_callback_url($url);
get_login_url();	//用js或者php转到这个登录地址，进行扫描二维码
$code = get_code();
get_access_token($code);
$userinfo = get_user_info();
------------------------------------
 */

class Qq {

	private $appid = '';
	private $secret = '';
	private $openid = '';
	private $redirect_url = '';
	PRIVATE $access_token = '';
	private $refresh_token = '';
	private $_URL = 'https://open.weixin.qq.com';
	private $time = 0;

	public function __construct($appid, $secret) {
		$this->appid = $appid;
		$this->secret = $secret;

		$this->time = time();
	}

	public function set_login_callback_url($url) {
		$this->redirect_url = urlencode($url);
	}


	/**
	 * appid	是	应用唯一标识
	 * redirect_uri	是	重定向地址，需要进行UrlEncode
	 * response_type	是	填code
	 * scope	是	应用授权作用域，拥有多个作用域用逗号（,）分隔，网页应用目前仅填写snsapi_login即可
	 * state	否	用于保持请求和回调的状态，授权请求后原样带回给第三方。该参数可用于防止csrf攻击（跨站请求伪造攻击），建议第三方带上该参数，可设置为简单的随机数加session进行校验

	 * @return [type] [description]
	 */
	public function get_login_url() {
		
		$url = $this->_URL . "/connect/qrconnect?appid=" . $this->appid. "&redirect_uri=" . $this->redirect_url . "&response_type=code&scope=snsapi_login&state=" . $this->time . "#wechat_redirect";

		return $url;
	}

	/**
	 * 获取code
	 * @return [type] [description]
	 */
	public function get_code() {
		return isset($_GET['code']) ? $_GET['code'] : '';
	}


	/**
	 * 请求获取 access_token
	 * @return array|false

access_token	接口调用凭证
expires_in		access_token接口调用凭证超时时间，单位（秒）
refresh_token	用户刷新access_token
openid			授权用户唯一标识
scope			用户授权的作用域，使用逗号（,）分隔	 
	 */
	public function request_access_token($code) {
		$url = $this->_URL . "/sns/oauth2/access_token?appid=" . $this->appid . "&secret=" . $this->secret . "&code=" . $code . "&grant_type=authorization_code";

		$ret = $this->httpGet($url);

		if (isset($ret['access_token'])) {
			$this->access_token = $ret['access_token'];
			$this->refresh_token = $ret['refresh_token'];
			$this->openid = $ret['openid'];
			$this->scope = $ret['scope'];

			$_SESSION['last_request_access_token_time'] = $this->time;
			$_SESSION['access_token'] = $this->access_token;
		} else {
			return false;
		}

		return $ret;
	}

	/**
	 * 获取access_token
	 * @return string
	 */
	public function get_access_token($code = '') {
		if (! isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
			$this->request_access_token($code);
		} else {
			$this->access_token = $_SESSION['access_token'];

			//access_token过期,这里为一个小时刷新一次access_token
			if ( ($_SESSION['last_quest_access_token_time'] - $this->time) > 86400) {
				return $this->refresh_token();
			} else {
				$ret = [
					'access_token' => $this->access_token,
					'expires_in' => $this->expires_in,
					'refresh_token' => $this->refresh_token,
					'openid' => $this->openid,
					'scope' => $this->scope,
				];
			
				return $ret;
			}

			
		}

		return $this->access_token;
	}

	/**
	 * 刷新access_token有效期
	 * @return array
access_token	接口调用凭证
expires_in	access_token接口调用凭证超时时间，单位（秒）
refresh_token	用户刷新access_token
openid	授权用户唯一标识
scope	用户授权的作用域，使用逗号（,）分隔	 
	 */
	public function refresh_token() {
		$url = $this->_URL . "/sns/oauth2/refresh_token?appid=" . $this->appid . "&grant_type=refresh_token&refresh_token=" . $this->refresh_token;

		$ret = $this->httpGet($url);

		if (isset($ret['access_token'])) {
			$this->access_token = $ret['access_token'];
			$this->refresh_token = $ret['refresh_token'];
			$this->openid = $ret['openid'];
			$this->scope = $ret['scope'];

			$_SESSION['last_request_access_token_time'] = $this->time;
			$_SESSION['access_token'] = $this->access_token;	
		} else {
			return false;
		}

		return $ret;
	}

	/**
	 * 获取用户信息
	 * @return array
	 *
openid		普通用户的标识，对当前开发者帐号唯一
nickname	普通用户昵称
sex			普通用户性别，1为男性，2为女性
province	普通用户个人资料填写的省份
city		普通用户个人资料填写的城市
country		国家，如中国为CN
headimgurl		用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空
privilege	用户特权信息，json数组，如微信沃卡用户为（chinaunicom）
unionid		用户统一标识。针对一个微信开放平台帐号下的应用，同一用户的unionid是唯一的。
	 */
	public function get_user_info() {

		$get_user_info_url = $this->_URL . '/sns/userinfo?access_token=' . $this->access_token . '&openid=' . $this->openid . '&lang=zh_CN';

		$ret = $this->httpGet($url);

		return $ret;
	}

	/**
	 * 获取指定url内容
	 * @param  string $url
	 * @return array
	 */
	private function httpGet($url) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$get_token_url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$res = curl_exec($ch);
		curl_close($ch);

		$json_arr = json_decode($res, true);

		return $json_arr;
	}
}


?>