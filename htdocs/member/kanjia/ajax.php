<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/General_Tree.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');

//指定模版存放目录
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$UsersID = $_SESSION['Users_ID'];

$template_dir = $_SERVER["DOCUMENT_ROOT"].'/member/kanjia/lbi/';
$smarty->template_dir = $template_dir;

$action = $_POST['action'];

//获得产品option list 列表
if($action == 'get_product'){
   
   $cate_id = $_POST['cate_id'];
   $keyword = $_POST['keyword'];
   
   $condition = "where Users_ID = '".$UsersID."'";
  
   if(strlen($cate_id)>0){
	    $condition .= " and Products_Category like '%".','.$cate_id.','."%'";   
   }
   
   
   if(strlen($keyword)>0){
   		$condition .= " and Products_Name like '%".$_POST["keyword"]."%'";	
   }
   
   $rsProducts = $DB->Get("shop_products",'Products_ID,Products_Name,Products_PriceX',$condition);
   
   $product_list = $DB->toArray($rsProducts);
   
   $smarty->assign('list',$product_list);
   
   $option_list=  $smarty->fetch('option_list.html');
   
   echo $option_list;
 
	
}