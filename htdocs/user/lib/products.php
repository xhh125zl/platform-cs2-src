<?php
require_once "../config.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/product.class.php';
function productsAdd($data){
    $data['Users_ID'] = htmlspecialchars(trim($data['Users_ID']));
    $data['User_ID'] = (int)$data['User_ID'];
    if ($data['Users_ID'] != $_SESSION['Users_ID'] || $data['User_ID'] != $_SESSION[$_SESSION['Users_ID'] . 'User_ID']) {
        return false;
    }
    $data['state'] = 1;
    $data['istop'] = 0;
    $data['Products_FromID'] = (int)$data['Products_FromID'];
    $data['Cate_ID'] = (int)$data['Cate_ID'];

    $data['dateline'] = time();
    global $DB;
    $flag = $DB->Add('shop_dist_product_db', $data);
    if ($flag) {
        $b2cdata = [
            'Products_FromId' => $data['Products_FromID'],
            'DisPerson_Qty' => 1,
        ];
        $ret = product::updatediscount($b2cdata);
    }
    if ($flag && $ret['errorCode'] == 0) {
        return true;
    }else{
        return false;
    }
}

if ($_GET['action'] == 'addProducts') {
    if (productsAdd($_POST)) {
        echo json_encode(['errorCode' => 0, 'msg' => '上架成功']);
    } else {
        echo json_encode(['errorCode' => 101, 'msg' => '上架失败']);
    }
}