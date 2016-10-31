<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/base.class.php';

class ImplOrder extends base
{
  /**
   * @method createOrderSN
   * @deprecated 获取并生成订单号
   * @return 返回订单号
   */
  static public function createOrderSN()
  {
      $url = '/order/createorderid.html';
      $result = self::request($url, 'post');
      return $result;
  }

	/**
	 * 添加订单
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function add($data)
    {
    	$url = '/order/add.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }
    
    /**
	 * 更新订单状态
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function updateorderstatus($data)
    {
    	$url = '/order/updateorderstatus.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }
    
    /**
     * @method actionOrdersend
     * @deprecated 发货
     * @param array $data [description]
     * @return json [<description>]
     */
    static public function actionOrdersend($data)
    {
        $url = '/order/ordersend.html';
        $result = self::request($url, 'post', $data);
    
        return $result;
    }




    //==================================================B2C请求401接口逻辑分割线=====================================================

    /**
     * @param ['Users_ID' => $Users_ID]
     * @return 返回订单信息
     */
    static public function getOrders($data,$page = 1)
    {
        $url = '/order/getorders.html?page=' . $page;
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * @method actionOrdersend401
     * @deprecated 发货
     * @param array $data [description]
     * @return json [<description>]
     */
    static public function actionOrdersend401($data)
    {
        $url = '/order/ordersend401.html';
        $result = self::request($url, 'post', $data);
    
        return $result;
    }
    
}