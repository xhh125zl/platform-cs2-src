<?php
include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class message extends base
{

    //==================================================B2C请求401接口逻辑分割线=====================================================

    /**
     * @param ['Biz_Account' => $BizAccount]
     * @return 返回记录订单信息
     */
    static public function getMsgOrder($data,$page = 1)
    {
        $url = '/message/getmsgorder.html?page=' . $page;
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * @param ['Biz_Account' => $BizAccount]
     * @return 返回记录订单信息
     */
    static public function getMsgDistribute($data,$page = 1)
    {
        $url = '/message/getMsgDistribute.html?page=' . $page;
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * @param ['Biz_Account' => $BizAccount]
     * @return 返回记录订单信息
     */
    static public function getMsgWithdraw($data,$page = 1)
    {
        $url = '/message/getMsgWithdraw.html?page=' . $page;
        $result = self::request($url, 'post', $data);
        return $result;
    }
    
}