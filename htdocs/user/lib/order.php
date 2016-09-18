<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";

require_once CMS_ROOT . '/include/api/ImplOrder.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

//获取订单
function getOrders($Biz_Account, $page = 1){
    $transfer = ['Biz_Account' => $Biz_Account, 'pageSize' => 2];
    $res = ImplOrder::getOrders($transfer, $page);
    return $res;
}

$res = getOrders($_SESSION['Biz_Account'], isset($_GET['page']) ? $_GET['page'] : 1)['data'];
foreach ($res as $k => $v) {
    $resArr[$v['Order_Status']][] = $v;
}