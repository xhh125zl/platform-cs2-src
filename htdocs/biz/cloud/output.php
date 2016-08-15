<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/update/common.php');
require_once (CMS_ROOT . '/include/library/outputExcel.php');

$type = $_REQUEST['type'];

if ($type == 'product_gross_info') {
    
    $table = 'cloud_products';
    $fields = '*';
    $condition = "WHERE Users_ID='" . $UsersID . "'";
    $resource = $DB->get($table, $fields, $condition);
    
    $data = $DB->toArray($resource);
    // 处理数据,获取分类信息
    $category_list = getCategoryList();
    
    foreach ($data as $key => $item) {
        
        if ($item['Products_Category'] == '0') {
            $item['Products_Category'] = '未指定';
        } else {
            if (isset($category_list[$item['Products_Category']])) {
                $item['Products_Category'] = $category_list[$item['Products_Category']];
            } else {
                $item['Products_Category'] = '已删除';
            }
        }
        
        // 处理产品属性
        $JSON = json_decode($item['Products_JSON'], TRUE);
        $property = '';
        if (isset($JSON['Property'])) {
            
            foreach ($JSON['Property'] as $k => $value) {
                $property .= $k . ':';
                
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $property .= $v;
                    }
                } else {
                    $property .= $value;
                }
            }
        }
        $item['Products_Property'] = $property;
        $data[$key] = $item;
    }
    
    $outputExcel = new OutputExcel();
    $outputExcel->product_gross_info($data);
} elseif ($type == 'order_detail_list') {
    
    $condition = "WHERE Users_ID='{$UsersID}' AND Order_Type='cloud'";
    
    if (! empty($_GET["Keyword"])) {
        $condition .= " AND Order_CartList LIKE '%" . $_GET["Keyword"] . "%'";
    }
    if (isset($_GET["Status"])) {
        if ($_GET["Status"] != '') {
            $condition .= " AND Order_Status=" . $_GET["Status"];
        }
    }
    
    if (! empty($_GET["AccTime_S"])) {
        $condition .= " AND Order_CreateTime>=" . strtotime($_GET["AccTime_S"]);
    }
    if (! empty($_GET["AccTime_E"])) {
        $condition .= " AND Order_CreateTime<=" . strtotime($_GET["AccTime_E"]);
    }
    
    $beginTime = ! empty($_GET["AccTime_S"]) ? $_GET["AccTime_S"] : '未指定';
    $endTime = ! empty($_GET["AccTime_E"]) ? $_GET["AccTime_E"] : '未指定';
    
    $resource = $DB->get("user_order", "*", $condition);
    $list = $DB->toArray($resource);
    
    foreach ($list as $key => $item) {
        if (is_numeric($item['Address_Province'])) {
            $area_json = read_file(CMS_ROOT . '/data/area.js');
            $area_array = json_decode($area_json, TRUE);
            $province_list = $area_array[0];
            $Province = '';
            if (! empty($item['Address_Province'])) {
                $Province = $province_list[$item['Address_Province']] . ',';
            }
            $City = '';
            if (! empty($item['Address_City'])) {
                $City = $area_array['0,' . $item['Address_Province']][$item['Address_City']] . ',';
            }
            
            $Area = '';
            if (! empty($item['Address_Area'])) {
                $Area = $area_array['0,' . $item['Address_Province'] . ',' . $item['Address_City']][$item['Address_Area']];
            }
        } else {
            $Province = $item['Address_Province'];
            $City = $item['Address_City'];
            $Area = $item['Address_Area'];
        }
        
        $list[$key]['Order_CartList'] = json_decode(htmlspecialchars_decode($item['Order_CartList']), TRUE);
        $list[$key]['receiver_address'] = $Province . $City . $Area . $item['Address_Detailed'];
    }
    
    $outputExcel = new OutputExcel();
    $outputExcel->order_detail_list($beginTime, $endTime, $list);
}

/* 获取产品分类列表 */
function getCategoryList()
{
    global $DB;
    $table = 'cloud_category';
    $fields = 'Category_ID,Category_Name';
    $condition = '';
    $resource = $DB->get($table, $fields, $condition);
    $list = $DB->toArray();
    
    foreach ($list as $key => $item) {
        $dropdown[$item['Category_ID']] = $item['Category_Name'];
    }
    
    return $dropdown;
}