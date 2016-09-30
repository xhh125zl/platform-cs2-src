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
}