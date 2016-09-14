<?php
require_once "../config.inc.php";
require_once CMS_ROOT . '/include/api/product_category.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

//获取分类
function getFirstCate()
{
    $bizAccount = $_SESSION['Biz_Account'];
    $result = product_category::getDev401firstCate($bizAccount);
    return $result['cateData'];
}

//获取二级分类
function getSecondCate($firstCateID){
    $firstCateID = intval($firstCateID);
    if ($firstCateID == 0) {
        return NULL;
    }else{
        $data['Biz_Account'] = $_SESSION['Biz_Account'];
        $data['firstCateID'] = $firstCateID;
        $result = product_category::getDev401SecondCate($data);
        return $result['cateData'];
    }
}

//添加分类
function addCate($data)
{
    global $DB;
    $data['Users_ID'] = $_SESSION['Users_ID'];
    $data['User_ID'] = $_SESSION[$_SESSION['Users_ID'] . 'User_ID'];
    $data['Category_Name'] = htmlspecialchars(trim($data['Category_Name']));
    $exists = $DB->GetRs('shop_dist_category', '*', "where Category_Name = '". $data['Category_Name'] ."'");
    if ($exists) {
        return 2;
    }
    $flag = $DB->Add('shop_dist_category', $data);
    if ($flag) {
        return 1;
    } else {
        return 3;
    }
}

//删除分类
function delCate($cid)
{
    global $DB;
    $cid = intval($cid);
    $cateGoods = $DB->GetAssoc('shop_dist_product_db', '*', "where Cate_ID = " . $cid);
    if (count($cateGoods) > 0 ) {
        return 2;
    }
    $cate = $DB->GetRs('shop_dist_category', '*', "where Category_ID = " . $cid);
    if ($cate) {
        $flag = $DB->Del('shop_dist_category',"Category_ID = " . $cid);
        if ($flag) {
            return 1;
        }else{
            return 3;
        }
    }else{
        return 4;
    }
}

//更新分类
function updateCate($datapost){
    $Cate_ID = intval($datapost['Cate_ID']);
    $data['Category_Name'] = htmlspecialchars(trim($datapost['Category_Name']));
    global $DB;
    $flag = $DB->Set('shop_dist_category',$data,"where Category_ID = " . $Cate_ID);
    if ($flag) {
        return true;
    }else{
        return false;
    }
}
//获取分类
if ($_GET['action'] == 'fCate') {
    echo json_encode(getFirstCate(), JSON_UNESCAPED_UNICODE);
}
if($_GET['action'] == 'sCate' && isset($_GET['fcateID'])) {
    echo json_encode(getSecondCate($_GET['fcateID']));
}
//添加分类
if ($_GET['action'] == 'addCate') {
    $CateName = preg_replace("/[<.*>]/", "", $_POST['Category_Name']);
    if (strlen(htmlspecialchars(trim($CateName))) == 0) {
        echo json_encode(['errorCode' => 2, 'msg' => '分类名称不能为空!']);
        exit;
    }
    $flag = addCate($_POST);
    if ($flag == 1) {
        echo json_encode(['errorCode' => 0, 'msg' => '添加成功']);
    } elseif ($flag == 2) {
        echo json_encode(['errorCode' => 2, 'msg' => '分类名称已存在,添加失败!']);
    }elseif ($flag == 3) {
        echo json_encode(['errorCode' => 3, 'msg' => '添加失败']);
    }
}
//删除分类
if ($_GET['action'] == 'delCate' && isset($_POST['Cate_ID'])) {
    $res = delCate($_POST['Cate_ID']);
    if ($res == 1) {
        echo json_encode(['errorCode' => 0, 'msg' => '删除成功']);
    }elseif ($res == 2){
        echo json_encode(['errorCode' => 1, 'msg' => '分类下有商品,不允许删除!']);
    }elseif ($res == 3) {
        echo json_encode(['errorCode' => 2, 'msg' => '删除失败']);
    }elseif ($res == 4) {
        echo json_encode(['errorCode' => 3, 'msg' => '不存在此分类']);
    }
}
//更新分类
if ($_GET['action'] == 'updateCate') {
    if (updateCate($_POST)) {
        echo json_encode(['errorCode' => 0, 'msg' => '更新成功']);
    }else{
        echo json_encode(['errorCode' => 1, 'msg' => '更新失败']);
    }

}