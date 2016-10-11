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
    
    /**
     * 修改店铺配置信息
     * @param array $data 可选参数:configData(shop_config表里的字段),usersData(Users表里的字段),addressData(收货地址表里的字段),请自行判断哪个表的字段.如把表的字段放到其他的变量数组里会导致请求失败
     * @return array 
     */
    static public function updatecolumn($data)
    {
        $url = '/shopconfig/updatecolumn.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }
    
    /**
     * 获取店铺二维码(401网站上的链接)
     * @param string $UsersID
     * @return string
     */
    static public function getQrcode($UsersID)
    {
        $url = self::getApiUrl() . '/qrcode/get401shopqrcode.html?usersid=' . $UsersID;

        return $url;

    }
	
	/**
     * 获取商家认证记录(数据在401)
     * @param
     * @return string
     */
	static public function getBizapply($data=[])
    {
        $url = '/bizapply/getbizapply.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }
 
	/**
     * 获取商家认证记录(数据在401)
     * @param
     * @return string
     */
	static public function updateBizapply($data=[])
    {
        $url = '/bizapply/updatebizapply.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }
	

	
	
}