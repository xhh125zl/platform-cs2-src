<?php
/**
 * 产品分类（add/edit/delete)
 * product_category.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class product_category extends base
{
	/**
	 * 获取B2C所有商品分类
	 * @return array [description]
	 */
	static public function get_all_category()
	{
		$url = '/category/list.html';
    	$result = self::request($url, 'post');

    	return $result;
	}





	//=======================================401接口逻辑分割线=================================================

	//B2C获取401的顶级产品分类
	static public function getDev401firstCate($bizAccount)
	{
		$url = '/category/getfirstcate.html';
		$result = self::request($url, 'post', ['Biz_Account' => $bizAccount]);
		return $result;
	}

	//B2C获取401的二级产品分类
	static public function getDev401SecondCate($data)
	{
		$url = '/category/getsecondcate.html';
		$result = self::request($url, 'post', $data);
		return $result;
	}
}