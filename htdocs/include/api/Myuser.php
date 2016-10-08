<?php
include_once $_SERVER['DOCUMENT_ROOT'] . './include/api/base.class.php';

class Myuser extends base
{

    public static function getMyUsers($data, $page = 1)
    {
        $url = "/user/list.html";
        if (isset($page) && $page > 0) {
            $url .= '?page=' . $page;
        }
        $result = self::request($url, 'post', $data);
        return $result;
    }
}