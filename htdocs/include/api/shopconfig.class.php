<?php
include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class shopconfig extends base
{
    /**
     * 获取店铺配置
     * @param array $data ['Biz_Account' => $Biz_Account]
     * @return array
     */
    static public function getConfig($data)
    {
        $url = '/shopconfig/getshopconfigfrom401.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }
    
}