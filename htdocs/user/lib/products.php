<?php
require_once "../config.inc.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/api/product.class.php';
function productsAdd($data){
    global $DB;
    $postdata = $DB->GetRs('shop_products', '*', "where Products_FromId = " . (int)$data['Products_FromID']);
    //注销掉Users_ID,需要根据Users_Account到401去查找对应的Users_ID
    unset($postdata['Users_ID'], $postdata['Products_ID']);
    $postdata['Users_Account'] = $DB->GetRs('biz', '*', "where Biz_ID = " . $postdata['Biz_ID'])['Biz_Account'];
    $postdata['Products_Category'] = ','.(int)$data['firstCate']. ',' . $data['secondCate'] . ',';
    return $postdata;

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
        echo json_encode(['errorCode' => 0, 'msg' => '上架成功', 'data' => productsAdd($_POST)], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['errorCode' => 101, 'msg' => '上架失败']);
    }
}