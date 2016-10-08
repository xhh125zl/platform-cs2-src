<?php
require_once "../config.inc.php";
require_once CMS_ROOT . '/include/api/distribute.class.php';

if (isset($_POST['action']) && $_POST['action'] == 'disable') {
    $transfer = ['Biz_Account' => $BizAccount, 'Account_ID' => $_POST['Account_ID'], 'status' => 0];
    $res = distribute::updatestatus($transfer);
    if ($res['errorCode'] == 0) {
        echo json_encode(['errorCode' => 0, 'msg' => '更新成功']);
    } else {
        echo json_encode(['errorCode' => 1, 'msg' => '更新失败']);
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'enable') {
    $transfer = ['Biz_Account' => $BizAccount, 'Account_ID' => $_POST['Account_ID'], 'status' => 1];
    $res = distribute::updatestatus($transfer);
    if ($res['errorCode'] == 0) {
        echo json_encode(['errorCode' => 0, 'msg' => '更新成功']);
    } else {
        echo json_encode(['errorCode' => 1, 'msg' => '更新失败']);
    }
} else {
    echo json_encode(['errorCode' => 2, 'msg' => '数据错误']);
}