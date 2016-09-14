<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class pay extends base
{

	  /**@method createOrderSN
   * @deprecated 获取并生成订单号
   * @return 返回订单号
   */
  static public function updatecolumn($data)
  {
      $url = '/order/updatecolumn.html';
      $result = self::request($url, 'post',$data);
      return $result;
  }
  
   /**@method createOrderSN
   * @deprecated 获取支付列表
   * @return 支付列表队列
   */
  static public function getlist()
  {
      $url = '/pay/list.html';
      $result = self::request($url, 'post');
      return $result;
  }
}