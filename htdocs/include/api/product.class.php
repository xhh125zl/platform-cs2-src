<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class product extends base
{

	/**
	 * 发布产品
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function add($data)
    {
    	$url = '/product/add.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

    /**
     * 编辑产品
     * @param  array $data [description]
     * @return array       [description]
     */
    static public function edit($data)
    {
    	$url = '/product/mod.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

    /**
     * 删除产品
     * @param  array $productId [description]
     * @return array            [description]
     */
    static public function delete($productId)
    {
    	$url = '/product/del.html';
    	$result = self::request($url, 'post', $productId);

    	return $result;	
    }

    /**
     * 获取产品详情信息
     * @param  int $product_id [description]
     * @return array             [description]
     */
    static public function detail($product_id)
    {
        $url = '/product/detail/' . $product_id . '.html';

        $result = self::request($url, 'post');

       return $result;
    }

    /**
     * 搜索产品信息
     * @param  array $condition [description]
     * @param  string [<description>]
     * @return array            [description]
     */
    static public function search($condition, $data = [])
    {
        $url = '/product/list.html';

        if (is_array($condition) && count($condition) > 0) {
            $url .= "?" . http_build_query($condition);
        }

        $result = self::request($url, 'post', $data);

        return $result;
    }

    /**
     * 发布分销
     * @param  array $data [description]
     * @return array       [description]
     */
    static public function pulldistribute($data)
    {
        $url = '/product/pulldistribute.html';

        $result = self::request($url, 'post', $data);

       return $result;
    }

    /**
     * 更新产品销量
     * @param  array $data [description] ['Products_FromId' => 3, DisPerson_Qty' => -1]
     * @return array       [description]
     */
    static public function updatesales($data)
    {
        $url = '/product/updatesales.html';

        $result = self::request($url, 'post', $data);

       return $result;
    }
    
    /**
     * 更新付款后的库存
     * @param  array $data [description] ['Products_FromId' => 3, DisPerson_Qty' => -1]
     * @return array       [description]
     */
    static public function updatecount($data)
    {
        $url = '/product/updatecount.html';
    
        $result = self::request($url, 'post', $data);
    
        return $result;
    }
    
    /**
     * 更新产品分销人数
     * @param  array $data [description] ['Products_FromId' => 3, DisPerson_Qty' => -1]
     * @return array       [description]
     */
    static public function updatediscount($data)
    {
        $url = '/product/updatediscount.html';

        $result = self::request($url, 'post', $data);

       return $result;
    }
}