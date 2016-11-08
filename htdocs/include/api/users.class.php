	<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/base.class.php';

class users extends base
{

	/**
	 * 发布产品
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function addUsers($data)
    {
    	$url = '/users/addusers.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

	/**
	 * 添加biz_apply表记录
	 * @param array $data  ['Users_ID' => $Users_ID, Biz_ID => $Biz_ID]
	 * return json
	 */
	static public function addBizApply($data)
    {
    	$url = '/users/addbizapply.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

	/**
	 * 根据商家账号从401获取商家对应的UsersID
	 * @param string $cccount
	 * @return string
	 */
	static public function findUsersIDByAccount($account)
	{
	    $url = '/users/findusersidbyaccount.html';
		$data['Biz_Account'] = $account;
    	$result = self::request($url, 'post', $data);

		return ($result['errorCode'] == '') ? $result['data'] : '';

	}

	/**
	 * B2C忘记密码
	 * @param array $data  ['Biz_Account' => $Biz_Account, Biz_PassWord => $Biz_PassWord]
	 * return json
	 */
	static public function changePass($data)
	{
		$url = '/users/changeuserspasswd.html';
		$result = self::request($url, 'post', $data);

		return $result;
	}


	/*
	 * 通过app的微信登录查询401对应的openid是否存在商家记录
	 */
	static public function getBizByOpenid($data)
	{
		$url = '/biz/getbizbyopenid.html';
		$result = self::request($url, 'post', $data);

		return $result;
	}
  
  static public function getyijiid($data)
  {
      $url = '/users/getyijiid.html';
      $result = self::request($url, 'post', $data);
      return $result;
  }
  
  static public function getRuleUser($data)
  {
      $url = '/users/getruleuser.html';
      $result = self::request($url, 'post', $data);
      return $result;
  }
  
  static public function getUser($data)
  {
      $url = '/users/getuser.html';
      $result = self::request($url, 'post', $data);
      return $result;
  }
  
  //根据Yiji_UserID获取会员信息
  static public function Getruleuserbyyijiid($data)
  {
      $url = '/users/getruleuserbyyijiid.html';
      $result = self::request($url, 'post', $data);
      return $result;
  }
  
  //更新易极付支付账号的状态
  static public function Updateyijistatus($data)
  {
      $url = '/users/updateyijistatus.html';
      $result = self::request($url, 'post', $data);
      return $result;
  }

  static public function addYijiBind($data)
  {
      $url = '/users/addyijibind.html';
      $result = self::request($url, 'post', $data);
      return $result;
  }
  
}