<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class count extends base
{

    /**
     * 获取数据统计
     * @param array $data ['Biz_Account']
     * @return array [count:{'totalAmount' => 累计收入, 'monthAmount' => 月收入, 'dayAmount' => 今日开店收入, 'dayAllAmount' => 今日交易额, 'allMoney' => 本月交易额, 'orderCount' => 本月订单}}]
     */
    static function countIncome($data)
    {
    	$url = '/count/count.html';
    	$result = self::request($url, 'post', $data);

    	return $result;
    }

    /**
     * 获取数据统计
     * @param array $data ['Biz_Account']
     * @return array [count:{'userToday' => 今日会员, 'disToday' => 今日分销商, 'shareToday' => 今日代销人数}}]
     */
    static function countPeople($data)
    {
        $url = '/count/getpersoncount.html';
        $result = self::request($url, 'post', $data);

        return $result;
    }

}