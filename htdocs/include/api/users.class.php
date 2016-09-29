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
}