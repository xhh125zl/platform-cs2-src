<?php
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/Myuser.php';

//获取401对应的UsersID的所有会员
function getMyUsers($bizAccount, $page = 1, $tel = 0, $pageSize = 10)
{
    $transfer = ['Biz_Account' => $bizAccount, 'pageSize' => $pageSize];
    if (strlen($tel) > 5) {
        $transfer = array_merge($transfer, ['tel' => $tel]);
    }
    $res = Myuser::getMyUsers($transfer, $page);
    return $res;
}


//获取401会员详情
function getUserDetail($bizAccount, $User_ID)
{
    $transfer = ['Biz_Account' => $bizAccount, 'User_ID' => $User_ID];
    $res = Myuser::getMyUsers($transfer);
    return $res;
}


if ($_GET['act'] == 'user_list') {
    $bizAccount = $_SESSION['Biz_Account'];
    if (isset($_GET['p']) && $_GET['p'] > 0) {
        $page = (int)$_GET['p'];
    } else {
        $page = 1;
    }
    if (isset($_POST['tel'])) {
        $User_Mobile = (int)$_POST['tel'];
    } else {
        $User_Mobile = 0;
    }
    $userList = getMyUsers($bizAccount, $page, $User_Mobile);
} elseif ($_GET['act'] == 'user_detail') {
    if (!isset($_GET['User_ID'])) {
        exit('缺少必要的参数User_ID');
    } else {
        $bizAccount = $_SESSION['Biz_Account'];
        $User_ID = intval($_GET['User_ID']);
        $userDetail = getUserDetail($bizAccount, $User_ID);
        if (count($userDetail['data']) > 0) {
            $rsUser = $userDetail['data'][0];
        } else {
            exit("无此会员,请确认!");
        }
    }
}