	<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

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

}