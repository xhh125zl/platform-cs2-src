<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/base.class.php';

class producttype extends base
{

	/**
	 * 发布产品类型
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function add($data)
    {
    	$url = '/producttype/add.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

    /**
     * 编辑产品类型
     * @param  array $data [description]
     * @return array       [description]
     */
    static public function edit($data)
    {
    	$url = '/producttype/mod.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

    /**
     * 删除产品类型
     * @param  array $producttypeId [description]
     * @return array            [description]
     */
    static public function delete($producttypeId)
    {
    	$url = '/producttype/del.html';
    	$result = self::request($url, 'post', $producttypeId);

    	return $result;	
    }

    /**
     * 获取产品类型详情信息
     * @param  int $producttype_id [description]
     * @return array             [description]
     */
    static public function detail($producttype_id)
    {
        $url = '/producttype/detail/' . $product_id . '.html';

        $result = self::request($url, 'post');

       return $result;
    }

}