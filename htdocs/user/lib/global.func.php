<?php
/**
 * 获取商家认证状态
 * @param int $auth_status
 * @return string
 */
function get_auth_statusText($statusValue) {
    if ($statusValue == 0) {
        $auth_status = '未认证';
    } elseif ($statusValue == 1) {
        $auth_status = '审核中';
    } elseif ($statusValue == 2) {
        $auth_status = '已认证';
    } elseif ($statusValue == -1) {
        $auth_status = '驳回';
    }

    return $auth_status;
}

?>