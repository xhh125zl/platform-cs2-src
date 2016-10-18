<?php
include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class b2cshopconfig extends base
{
	/**
	 * 获取设置
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function getConfig($data)
    {
        $url = '/shopconfig/bizconfig.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }

    /**
	 * 获取商家认证信息
	 * @param array $data [description]
     * @return json [<description>]
	 */
    static public function getVerifyconfig($data)
    {
        $url = '/shopconfig/getverifyconfig.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }
}