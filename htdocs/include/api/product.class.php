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
     * @param  array $data ['Products_ID' => $Products_ID, 'BizAccount' => $BizAccount]
     * @return array            [description]
     */
    static public function delete($data)
    {
        $url = '/product/delgoodsfrom401.html';
        $result = self::request($url, 'post', $data);

    	return $result;	
    }

    /**
     * 获取产品分类详情信息
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
    static public function search($pageno = 1, $condition, $data = [])
    {
        $url = '/product/list.html?page=' . intval($pageno);

        if (is_array($condition) && count($condition) > 0) {
            $url .= "&" . http_build_query($condition);
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

    /**
     * b2c编辑商品时     推荐--->不推荐  （推荐的商品没有未完成的订单）
     * 删除b2c平台的推荐产品
     * @param  array $productId [description]
     * @return array            [description]
     */
    static public function b2cProductDelete($productId)
    {
        $url = '/product/del.html';
        $result = self::request($url, 'post', $productId);

        return $result; 
    }




    //==================================================401接口逻辑分割线================================================

    /**
     * B2C获取401对应Users已分销的产品
     * @param array $data   ['Biz_Account' => 'test01']
     * @return array
     */
    static public function getIsDistributeArr($data)
    {
        $url = '/product/getdistributegoods.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * B2C添加代销产品到401数据库
     * @param array $data 二维数组
     * @return json
     */
    static public function addTo401($data)
    {
        $url = "/product/adddistributegoods.html";
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * 获取商家所有的商品
     * @param int $pageno 页码
     * @param array $data
     */
    static public function getProducts($pageno = 1, $data)
    {
        $url = '/product/getgoods.html?page=' . intval($pageno);

        $result = self::request($url, 'post', $data);

        return $result;
    }

    /**
     * B2C添加产品到401数据库
     * @param array $data 二维数组
     * @return json
     */
    static public function addProductTo401($data)
    {
        $url = "/product/addgoodsto401.html";
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * B2C编辑产品从401数据库获取数据
     * @param array $data 二维数组
     * @return json
     */
    static public function getProductArr($data)
    {
        $url = "/product/detail401.html";
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * B2C编辑产品到401数据库
     * @param array $data 二维数组
     * @return json
     */
    static public function editProductTo401($data)
    {
        $url = "/product/mod401.html";
        $result = self::request($url, 'post', $data);
        return $result;
    }
}