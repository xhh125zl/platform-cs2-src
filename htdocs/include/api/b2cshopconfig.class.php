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


    //注册商家
    static public function addBiz($data)
    {
        $url = '/users/addbiz.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }


    //微信登录,如果商家绑定了已经注册过的手机号,则更新对应的openid和头像到数据库
    static public function updateWxLogin($data)
    {
        $url = '/users/updatewxlogin.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }
}