<?php  
//网站后台微商城ajax 操作
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');

$action = $_REQUEST['action'];
if($action == 'get_attr'){
	$UsersID = $_POST["UsersID"];
	$TypeID = intval($_POST["TypeID"]);
	$ProductsID = intval($_POST["ProductsID"]);
	//$FinanceType = $_POST["FinanceType"];
	$r = $DB->GetRs("shop_product_type","*","where Users_ID='".$UsersID."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Type_ID=".$TypeID);
	if(!$r){
		$Data = array(
			"status"=>0,
			"content"=>'',
			"msg"=>"非法提交"
		);
	}else{
		if($ProductsID>0){
			$r = $DB->GetRs("shop_products","*","where Users_ID='".$UsersID."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Products_ID=".$ProductsID);
			if(!$r){
				$Data = array(
					"status"=>0,
					"content"=>'',
					"msg"=>"非法提交"
				);
			}else{
				$html = build_attr_html($TypeID,$ProductsID);
				$Data = array(
					"status"=>1,
					"content"=>$html,
					"msg"=>"获取产品属性成功"
				);
			}
		}else{
			$html = build_attr_html($TypeID, $ProductsID);
			$Data = array(
				"status"=>1,
				"content"=>$html,
				"msg"=>"获取产品属性成功"
			);
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);	
}

