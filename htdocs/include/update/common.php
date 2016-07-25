<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/lib_pintuan.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/lib_products.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/distribute.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/flow.php');


define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);
define('IS_AJAX',       ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')? true : false));
define('IN_UPDATE' ,1);
$request_uri    =  $_SERVER['REQUEST_URI'];
$UsersID        =  "";
$rsConfig       =  [];
$BizID          =  0;
$IsStore        =  0;

$SortType = [ '按发布时间','按销量','按价格','手动' ];
$ActiveType = [ '拼团','云购','众筹','微砍价','微促销' ];

if(stripos($request_uri, "api")){   //对于前台的初始化
    
    !isset($_GET["UsersID"]) && die("缺少必要的参数");
    $UsersID = $_GET["UsersID"];
    $rsConfig = shop_config($UsersID);
    $share_flag = 0;
    $signature = '';
    empty($rsConfig) && die("商城没有配置");
    $UserID = isset($_SESSION[$UsersID . 'User_ID'])?$_SESSION[$UsersID . 'User_ID']:0;
    if(!$UserID) header("Location: /api/{$UsersID}/user/");
    $BizID = isset($_GET['BizID']) && $_GET['BizID']?$_GET['BizID']:0;
    // 分销相关设置
    $dis_config = dis_config($UsersID);
    if (empty($dis_config)) {
        $dis_config = [];
    }
    // 合并参数
    $rsConfig = array_merge($rsConfig, $dis_config);
    $is_login = 1;
    $owner = get_owner($rsConfig, $UsersID);
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php');
    
}else if(stripos($request_uri, "member")){      //对于商城后台的初始化
    !($_SESSION && $_SESSION['Users_ID']) && header("Location: /member/login.php");
    $UsersID   =   $_SESSION['Users_ID'];
    
}else if(stripos($request_uri, "biz")){         //对于商家后台的初始化
    basename($_SERVER['PHP_SELF'])=='common.php' && header('Location:http://'.$_SERVER['HTTP_HOST']);
    if(empty($_SESSION["BIZ_ID"])){
        header("location:/biz/login.php");
    }
    $BizID  =   $_SESSION["BIZ_ID"];
    $rsBiz = $DB->GetRs("biz","*","where Biz_ID=".$BizID);
    
    if(!$rsBiz){
        echo '<script language="javascript">alert("此商家不存在！");history.back();</script>';
        exit();
    }
    $UsersID = $rsBiz['Users_ID'];
    $rsGroup = $DB->GetRs("biz_group","Group_Name,Group_IsStore","where Group_ID=".$rsBiz["Group_ID"]);
    if($rsGroup){
        $IsStore = $rsGroup["Group_IsStore"];
    }
}