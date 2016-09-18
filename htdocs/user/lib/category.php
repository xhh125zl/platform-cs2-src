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
    $postdata['Biz_Account'] = $_SESSION['Biz_Account'];
    $postdata['Category_Name'] = htmlspecialchars(trim($data['Category_Name']));
    if (isset($data['firstCateID']) && $data['firstCateID'] > 0) {
        $postdata['Category_ParentID'] = $data['firstCateID'];
    } else {
        $postdata['Category_ParentID'] = 0;
    }
    $postdata['Category_Img'] = '';
    $postdata['Category_IndexShow'] = 1;
    $postdata['Category_ListTypeID'] = 0;
    $postdata['Category_Index'] = 1;
    $transfer = ['cateData' => $postdata, 'Biz_Account' => $_SESSION['Biz_Account']];
    $res = product_category::addCateTo401($transfer);
    return $res;
}

//删除分类
function delCate($cid)
{
    $transfer = ['Biz_Account' => $_SESSION['Biz_Account'], 'Category_ID' => $cid];
    $res = product_category::delCateFrom401($transfer);
    return $res;
}

//更新分类
function updateCate($datapost){
    $postdata['Category_ID'] = intval($datapost['Cate_ID']);
    $postdata['Category_Name'] = htmlspecialchars(trim($datapost['Category_Name']));
    $transfer = ['Biz_Account' => $_SESSION['Biz_Account'], 'cateData' => $postdata];
    $res = product_category::editCateFrom401($transfer);
    return $res;
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
    $post = $_POST;
    $post['Category_Name'] = preg_replace("/[<.*>]/", "", $_POST['Category_Name']);
    if (strlen(htmlspecialchars(trim($post['Category_Name']))) == 0) {
        echo json_encode(['errorCode' => 2, 'msg' => '分类名称不能为空!']);
        exit;
    }
    $flag = addCate($post);
    echo json_encode(['errorCode' => $flag['errorCode'], 'msg' => $flag['msg']]);
}
//删除分类
if ($_GET['action'] == 'delCate' && isset($_POST['Cate_ID'])) {
    $flag = delCate((int)$_POST['Cate_ID']);
    echo json_encode(['errorCode' => $flag['errorCode'], 'msg' => $flag['msg']]);
}
//更新分类
if ($_GET['action'] == 'updateCate') {
    $post = $_POST;
    $post['Category_Name'] = preg_replace("/[<.*>]/", "", $_POST['Category_Name']);
    if (strlen(htmlspecialchars(trim($post['Category_Name']))) == 0) {
        echo json_encode(['errorCode' => 2, 'msg' => '分类名称不能为空!']);
        exit;
    }
    $flag = updateCate($post);
    echo json_encode(['errorCode' => $flag['errorCode'], 'msg' => $flag['msg']]);
}