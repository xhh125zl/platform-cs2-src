<?php
include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class b2cshopconfig extends base
{
    static public function getConfig($data)
    {
        $url = '/shopconfig/bizconfig.html';

        $result = self::request($url, 'post', $data);

        return $result;
    }
}