<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/base.class.php';

class count extends base
{

    /**
     * 获取数据统计
     * @param array $data ['Biz_Account']
     * @return array [count:{'totalAmount' => 累计收入, 'monthAmount' => 月收入, 'dayAmount' => 今日开店收入, 'allMoney' => 本月交易额, 'orderCount' => 本月订单}}]
     */
    static function countIncome($data)
    {
    	$url = '/count/count.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }



}