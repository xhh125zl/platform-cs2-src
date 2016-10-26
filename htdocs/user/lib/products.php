<?php
require_once "../config.inc.php";
require_once(CMS_ROOT . '/include/api/product.class.php');
require_once(CMS_ROOT . '/include/api/b2cshopconfig.class.php');
require_once(CMS_ROOT . '/include/api/ImplOrder.class.php');

function productsAdd($data){
    global $DB;
    $postdata = $DB->GetRs('shop_products', '*', "where Products_FromId = " . (int)$data['Products_FromID']);
    //注销掉Users_ID,需要根据Users_Account到401去查找对应的Users_ID
    unset($postdata['Users_ID'], $postdata['Products_ID']);
    $postdata['Users_Account'] = $_SESSION['Biz_Account'];
    $postdata['Products_Category'] = ','.(int)$data['firstCate']. ',' . $data['secondCate'] . ',';
    $transfer = ['productData' => $postdata];
    $resArr = product::addTo401($transfer);
    if ($resArr['errorCode'] == 0) {
        $b2cdata = [
            'Products_FromId' => $data['Products_FromID'],
            'DisPerson_Qty' => 1,
        ];
        $ret = product::updatediscount($b2cdata);
    } else {
        $ret['errorCode'] = 2;
    }
    if ($ret['errorCode'] == 0) {
        return true;
    }else{
        return false;
    }
}
//验证是否为数字   默认检查正浮点数  1：非负浮点数  2：检查正整数 3: 检查非负整数
function check_number($value, $type = 0) {
    $number_regular = '';
    if ($type == 0) {
        $number_regular = $value > 0 ;
    } else if ($type == 1) {
        $number_regular = $value >= 0 ;
    } else if ($type == 2) {
        $number_regular = $value > 0 && $value%1 == 0 ;
    } else if ($type == 3) {
        $number_regular = $value >= 0 && $value%1 == 0 ;
    }

    if (is_numeric($value) && $number_regular) {
        return true;
    } else {
        return false;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'addProducts') {     //分销其他商家的商品
    $flag = productsAdd($_GET);
    if ($flag) {
        echo json_encode(['errorCode' => 0, 'msg' => '上架成功']);
    } else {
        echo json_encode(['errorCode' => 101, 'msg' => '上架失败']);
    }
}

//商家添加编辑自营产品
if (isset($_POST['act']) && $_POST['act'] == 'addEditProduct') {
    //数据处理
    $input_productData = $_POST['productData'];

    $input_productData['Products_Name'] = cleanJsCss($input_productData['Products_Name']);  //商品名称

    //封面图片路径处理
    $imsge_path['ImgPath'] = explode(',' ,$input_productData['Products_JSON']);
    $input_productData['Products_JSON'] = json_encode($imsge_path,JSON_UNESCAPED_SLASHES);
    //商品详情处理  图片，内容
    $img_show = '';
    if ($input_productData['Products_JSON1'] != '') {
        $des_img = explode(',' ,$input_productData['Products_JSON1']);
        foreach ($des_img as $k => $v) {
            $img_show .= '<br/><img src="'.$v.'"/>';
        }
    }
    $input_productData['Products_Description'] = cleanJsCss($input_productData['Products_Description']);    //商品详情描述
    $input_productData['Products_Description'] = preg_replace('/\n|\r/', "<br/>", $input_productData['Products_Description']);
    $input_productData['Products_Description'] = htmlspecialchars($input_productData['Products_Description'].$img_show, ENT_QUOTES);
    //分类处理
    $input_productData['Products_Category'] = ','.(int)$input_productData['firstCate']. ',' . (int)$input_productData['secondCate'] . ',';
    //$input_productData['Products_BriefDescription'] = htmlspecialchars($input_productData['Products_BriefDescription'], ENT_QUOTES);  //产品简介
    $input_productData['Shipping_Free_Company'] = 0;    //免运费  0为全部 ，n为指定快递
    //$input_productData['Products_Index'] = 1/9999;    //产品排序
    //$input_productData['Products_Type'] = 0/n;        //产品类型
    $input_productData['Products_SoldOut'] = 0;         //其他属性  不能为空  1: 下架
    //$input_productData['Products_IsVirtual'] = 1;     //订单流程      0,0  1,0  1,1 
    //$input_productData['Products_IsRecieve'] = 1;
    //$input_productData['Products_Parameter'] = '[{"name":"","value":""}]';        //产品参数
    $input_productData['Users_ID'] = $UsersID;
    $input_productData['Products_Status'] = 1;

    //数据验证  原价、现价、产品利润、赠送积分、产品重量、库存
    if (!check_number($input_productData['Products_PriceY']) || !check_number($input_productData['Products_PriceX']) || !check_number($input_productData['Products_Profit']) || !check_number($input_productData['Products_Integration'], 1) || !check_number($input_productData['Products_Weight']) || !check_number($input_productData['Products_Count'], 1)) {
        echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
    }
    //推荐后  检测供货价
    if ($input_productData['is_Tj'] == 1) {
        $rsBiz = b2cshopconfig::getVerifyconfig(['Biz_Account' => $BizAccount]);
        if ($rsBiz['bizData']['is_agree'] !=1 || $rsBiz['bizData']['is_auth'] !=2 || $rsBiz['bizData']['is_biz'] !=1) {
            echo json_encode(array('errorCode' => 1, 'msg' => '您未达到将商品推荐到商城平台的资格。'));die;
        }

        if (!check_number($input_productData['Products_PriceS'])) {
            echo json_encode(array('errorCode' => 1, 'msg' => '填写的数据格式不正确'));die;
        }
        $PriceX = (float)$input_productData['Products_PriceX'];   //现价
        $PriceS = (float)$input_productData['Products_PriceS'];   //供货价
        if (($PriceX < $PriceS) || ($PriceX*0.7 > $PriceS)) {    //供货价为现价的 70% ~ 100%
            echo json_encode(array('errorCode' => 1, 'msg' => '供货价为现价的70% ~ 100%'));die;
        }
    } else {
        unset($input_productData['B2CProducts_Category']);
    }

    //判断是上架商品还是编辑商品
    if (empty($input_productData['Products_ID'])) {     //上架商品
        $input_productData['Products_CreateTime'] = time();

        unset($input_productData['Products_ID']);
        unset($input_productData['firstCate']);
        unset($input_productData['secondCate']);
        unset($input_productData['isSolding']);

        $postdata['Biz_Account'] = $BizAccount;
        $postdata['productData'] = $input_productData;
        $resArr = product::addProductTo401($postdata);
        if ($resArr['errorCode'] == 0) {
            echo json_encode(['errorCode' => 0, 'msg' => '上架成功', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/user/admin.php?act=products'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '上架失败']);
        }
    } elseif (ctype_digit($input_productData['Products_ID']) && $input_productData['Products_ID'] > 0) {        //编辑商品
        //获取旧数据
        $postdata['Biz_Account'] = $BizAccount;
        $postdata['Products_ID'] = $input_productData['Products_ID'];
        $postdata['is_Tj'] = $input_productData['is_Tj'];
        $resArr = product::getProductArr($postdata);
        unset($postdata);
        $old_productData = $resArr['data'];     //产品参数
        unset($old_productData['Category401']);
        unset($old_productData['b2cCategory']);

        //合并数据
        $new_productData = array_merge($old_productData,$input_productData);
        $old_is_Tj = $old_productData['is_Tj'];
        $is_Tj = $new_productData['is_Tj'];
        
        $postdata['productdata'] = $new_productData;
        $resArr = product::editProductTo401($postdata);
        unset($postdata);
        if ($resArr['errorCode'] == 0) {
            //判断推荐的可能，并操作
            if ($is_Tj == 0 && $old_is_Tj == 1 && $new_productData['isSolding'] == 0) {
                //取消推荐
                //判断是否有未完成订单
                $res = ImplOrder::getOrders(['Biz_Account' => $BizAccount, 'Order_Status' => '<> 4']);
                $orderList = [];
                if (isset($res['errorCode']) && $res['errorCode'] == 0) {
                    $orderList = $res['data'];
                } else {
                    echo json_encode(['errorCode' => 1, 'msg' => '获取订单列表失败']);
                    die;
                }
                if (count($orderList) > 0) {
                    foreach ($orderList as $k => $v) {
                        foreach (json_decode($v['Order_CartList'], true) as $key => $val) {
                            $proArr[] = $key;
                            $proArr[] = $val[0]['Products_FromId'];
                        }
                    }
                    $proArr = array_unique($proArr);
                    if (in_array((int)$new_productData['Products_ID'], $proArr)) {
                        echo json_encode(['errorCode' => 1, 'msg' => '当前有客户订单中包含此商品,并且订单状态不是已完成,不允许取消推荐!']);
                        die;
                    }
                }
                //没有未完成的订单，取消推荐
                unset($new_productData['isSolding']);
                $product_id = ['Products_ID' => $new_productData['Products_ID']];
                $b2c_resArr = product::b2cProductDelete($product_id);

            } elseif($is_Tj == 1 && $old_is_Tj == 1) {
                unset($new_productData['isSolding']);
                $postdata['productdata'] = $new_productData;
                $b2c_resArr = product::edit($postdata);
            } elseif ($is_Tj == 1 && $old_is_Tj == 0) {
                //推荐
                unset($new_productData['isSolding']);
                $new_productData['Products_FromId'] = $new_productData['Products_ID'];
                $new_productData['Users_Account'] = $BizAccount;
                $new_productData['Products_CreateTime'] = time();
                $postdata['productdata'] = $new_productData;
                $postdata['productAttr'] = '';
                $b2c_resArr = product::add($postdata);
            }
            if (isset($b2c_resArr)) {
                if ($b2c_resArr['errorCode'] == 0) {
                    echo json_encode(['errorCode' => 0, 'msg' => '编辑成功', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/user/admin.php?act=products'], JSON_UNESCAPED_UNICODE);
                    die;
                } else {
                    //推荐编辑不成功，做数据还原处理
                    $postdata['productdata'] = $old_productData;
                    $rock_resArr = product::editProductTo401($postdata);
                    echo json_encode(['errorCode' => 1, 'msg' => 'b2c编辑失败']);
                    die;
                }
            } else {
                echo json_encode(['errorCode' => 0, 'msg' => '编辑成功', 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/user/admin.php?act=products'], JSON_UNESCAPED_UNICODE);
                die;
            }
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '401编辑失败']);
            die;
        }

    }
    
}