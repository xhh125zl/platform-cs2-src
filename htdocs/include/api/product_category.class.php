<?php
/**
 * 产品分类（add/edit/delete)
 * product_category.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class product_category extends base
{
	/**
	 * 获取所有商品分类
	 * @return array [description]
	 */
	static public function get_all_category()
	{
		$url = '/category/list.html';
    	$result = self::request($url, 'post');

    	return $result;
	}



}