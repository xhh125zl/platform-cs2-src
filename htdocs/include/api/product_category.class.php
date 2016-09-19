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

	//B2C添加分类到401数据库
	static public function addCateTo401($data)
	{
		$url = "/category/addcateto401.html";
		$result = self::request($url, 'post', $data);
		return $result;
	}

	//B2C编辑401分类名称
	static public function editCateFrom401($data)
	{
		$url = "/category/editcatefrom401.html";
		$result = self::request($url, 'post', $data);
		return $result;
	}

	//B2C删除401分类名称
	static public function delCateFrom401($data)
	{
		$url = "/category/delcatefrom401.html";
		$result = self::request($url, 'post', $data);
		return $result;
	}
}