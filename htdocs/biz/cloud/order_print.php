<?php
require_once (CMS_ROOT . '/include/library/smarty.php');

// 设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";

$template_dir = CMS_ROOT . '/biz/cloud/html';
$smarty->template_dir = $template_dir;

$OrderID = empty($_REQUEST['OrderID']) ? 0 : $_REQUEST['OrderID'];

$order = $DB->GetRs("user_order", "*", "WHERE Users_ID='{$UsersID}' AND Order_ID='{$OrderID}");
$User_ID = $order['User_ID'];
$order['Order_SN'] = date("Ymd", $order['Order_CreateTime']) . '-' . $order['Order_ID'];
$order['Order_CreateTime'] = date("Y-m-d H:i:s", $order['Order_CreateTime']);
$order['Order_Shipping'] = json_decode(htmlspecialchars_decode($order['Order_Shipping']), TRUE);
$order['Order_ShippingID'] = ! empty($order['Order_ShippingID']) ? $order['Order_ShippingID'] : '未发货';
// 订单商品信息处理
$order['Order_CartList'] = json_decode(htmlspecialchars_decode($order['Order_CartList']), TRUE);
$order['Order_PaymentMethod'] = ! $order['Order_PaymentMethod'] ? $order['Order_PaymentMethod'] : '未支付';
// 计算商品总价
$product_total = 0;
foreach ($order['Order_CartList'] as $Product_ID => $Products) {
    foreach ($Products as $key => $item) {
        $property = '';
        
        if ($item['Property'] != '') {
            
            foreach ($item['Property'] as $Attr_ID => $Attr) {
                $property .= $Attr['Name'] . ':' . $Attr['Value'];
            }
        } else {
            $property = '无属性';
        }
        
        $product_total += $item['ProductsPriceX'] * $item['Qty'];
        $item['Property'] = $property;
        $order['Order_CartList'][$Product_ID][$key] = $item;
    }
}
if (is_numeric($order['Address_Province'])) {
    $area_json = read_file(CMS_ROOT . '/data/area.js');
    $area_array = json_decode($area_json, TRUE);
    $province_list = $area_array[0];
    $Province = '';
    if (! empty($order['Address_Province'])) {
        $Province = $province_list[$order['Address_Province']];
    }
    $City = '';
    if (! empty($order['Address_City'])) {
        $City = $area_array['0,' . $order['Address_Province']][$order['Address_City']];
    }
    
    $Area = '';
    if (! empty($rsOrder['Address_Area'])) {
        $Area = $area_array['0,' . $order['Address_Province'] . ',' . $order['Address_City']][$order['Address_Area']];
    }
    $order['Address_Province'] = $Province;
    $order['Address_City'] = $City;
    $order['Address_Area'] = $Area;
}
// 购货人信息
$user = $DB->GetRs("user", "*", "WHERE Users_ID='{$UsersID}' AND User_ID='{$User_ID}");

// 赋值
$smarty->assign("order", $order);
$smarty->assign("user", $user);
$smarty->assign('product_total', $product_total);
$smarty->display('order_print.html');