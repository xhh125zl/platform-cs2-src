<?php
/**
 * 产品类（add/edit/delete)
 * product.class.php
 */

include_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/base.class.php';

class distribute extends base
{


    //==================================================401接口逻辑分割线================================================

    /**
     * B2C获取401对应的Users_ID的分销商信息
     * @param array $data   ['Biz_Account' => 'test01']
     * @return array
     */
    static public function getDistribute($data, $pageno = 1)
    {
        $url = '/distribute/getdistribute.html?page=' . intval($pageno);
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * B2C更新401对应的Account_ID的状态(禁用和启用操作)
     * @param array $data   ['Biz_Account' => 'test01', 'Account_ID' => 41, 'status' => 0]
     * @return array
     */
    static public function updatestatus($data)
    {
        $url = '/distribute/updatestatus.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }
    
    /**
     * B2C批量获取商家的yiji_balance接口
     * @param array $data   userids(此参数对应一个一位数组,键值为User_ID,如[1,2,3,4,5])

     * @return array
     */
    static public function getyijibalancebyuserid($data)
    {
        $url = '/distribute/getyijibalancebyuserid.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }
    
    /**
     * B2C更新401对应的Account_ID的状态(禁用和启用操作)
     * @param array $data   counters(此参数为一个一位数组,对应getyijibalancebyuserid接口返回的数组,请原样传回到这个接口)

     * @return array
     */
    static public function updateyijibalance($data)
    {
        $url = '/distribute/updateyijibalance.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * 获取可提现余额
     * @param array $data   ['Biz_Account' => 'test01']
     * @return array
     */
    static public function getcash($data)
    {
        $url = '/distribute/getcash.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }

   
}