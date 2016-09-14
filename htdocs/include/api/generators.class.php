<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class generators extends base
{

	/**
	 * 从发号器获取订单号
	 * @param array $data [description]
     * @return string [<description>]
	 */
    static public function orderid()
    {
    	$url = '/order/createorderid.html';
    	$result = self::request($url, 'post');

    	return $result;
    }

    /**
     * 获取大订单号(一个大订单号对应多个子订单号)
     * @return string [description]
     */
    static public function bigorderid()
    {
        $url = '/order/createbigorderid.html';
        $result = self::request($url, 'post');

        return $result;
    }
    
}