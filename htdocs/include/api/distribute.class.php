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

   
}