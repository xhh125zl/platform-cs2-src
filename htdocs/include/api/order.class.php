<?php
include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class order extends base
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
        $url = '/order/actionOrdersend.html';
        $result = self::request($url, 'post', $data);
    
        return $result;
    }
    
}