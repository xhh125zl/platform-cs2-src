	<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/base.class.php';

class user extends base
{

	/**
	 * 获取会员列表或会员详情
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function getUser($data)
    {
    	$url = '/user/list.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }


}