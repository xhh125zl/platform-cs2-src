<?php
/**
 * 产品属性（add/edit/delete)
 * attribute.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class attribute extends base
{

	/**
	 * 添加产品属性
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function add($data)
    {
    	$url = '/attribute/add.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

    /**
     * 编辑产品属性
     * @param  array $data [description]
     * @return array       [description]
     */
    static public function edit($data)
    {
    	$url = '/attribute/mod.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

    /**
     * 删除产品属性
     * @param  array $attribute_id [description]
     * @return array            [description]
     */
    static public function delete($attribute_id)
    {
    	$url = '/attribute/del.html';
    	$result = self::request($url, 'post');

    	return $result;	
    }

    /**
     * 获取产品属性详情信息
     * @param  int $product_id [description]
     * @return array             [description]
     */
    static public function detail($product_id)
    {
        $url = '/attribute/detail/' . $product_id . '.html';

        $result = self::request($url, 'post');

       return $result;
    }

}