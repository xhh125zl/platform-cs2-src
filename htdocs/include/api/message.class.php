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
        $url = '/message/getmsgdistribute.html?page=' . $page;
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * @param ['Biz_Account' => $BizAccount]
     * @return 返回记录订单信息
     */
    static public function getMsgWithdraw($data,$page = 1)
    {
        $url = '/message/getmsgwithdraw.html?page=' . $page;
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * @param 'id' = $msgid,'transData' = ['msg_status' => '1']
     * @return 返回更新订单记录信息状态
     */
    static public function updateMsgOrder($data)
    {
        $url = '/message/updatemsgorder.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * @param 'id' = $msgid,'transData' = ['msg_status' => '1']
     * @return 返回更新分销记录信息状态
     */
    static public function updateMsgDistribute($data)
    {
        $url = '/message/updatemsgdistribute.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }

    /**
     * @param 'id' = $msgid,'transData' = ['msg_status' => '1']
     * @return 返回更新提现记录信息状态
     */
    static public function updateMsgWithdraw($data)
    {
        $url = '/message/updatemsgwithdraw.html';
        $result = self::request($url, 'post', $data);
        return $result;
    }
    
}