<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/lib_pintuan.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/lib_products.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/helper/distribute.php');

!isset($_GET["UsersID"])  &&  die("缺少必要的参数");
$UsersID        = $_GET["UsersID"];
$base_url       = base_url();
empty($_SESSION) || header("location:/api/".$UsersID."/pintuan/");

//拼团配置信息
$rsConfig = $DB->GetRs('pintuan_config','*','where Users_ID = "'.$UsersID.'"');
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);


?>