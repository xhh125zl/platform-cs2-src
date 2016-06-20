<?php  
//网站后台微商城ajax 操作
ini_set("display_errors","On");


require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');

//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = $_SERVER["DOCUMENT_ROOT"].'/member/shop/html';
$smarty->template_dir = $template_dir;

$UsersID = $_SESSION['Users_ID'];

$action = $_REQUEST['action'];
if($action == 'mod_bankcard'){
	$User_ID = $_POST["UserID"];

	$condition = "where Users_ID='".$UsersID."' and User_ID= '".$User_ID."'";
	$data = array("Bank_Card"=>$_POST['Bank_Card']);

	$flag = $DB->set('shop_distribute_account',$data,$condition);

	if($flag){
		$response = array("status"=>1);
	}else{
		$response = array("status"=>0);
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);

}else if($action == 'reject_withdraw'){
	$Record_ID = $_POST['Record_ID'];

	//获取此次提现记录内容
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Record_ID='".$Record_ID."'";
	$rsRecord = $DB->getRs("shop_distribute_account_record","Record_Money,User_ID",$condition);
	
	//将此次提现状态改为驳回
	$data = array("Record_Status"=>2,"Record_Description"=>$_POST['Reject_Reason']);
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Record_ID='".$Record_ID."'";
	$Flag = $DB->set("shop_distribute_account_record",$data,$condition);
		
	//将钱退回
	$money = $rsRecord['Record_Money'];
	$condition = "where User_ID=".$rsRecord['User_ID']." and Users_ID='".$_SESSION["Users_ID"]."'";
	$SET = $DB->set('shop_distribute_account',"balance=balance+$money",$condition);

	if($Flag&$SET){
		$response = array("status"=>1);
	}else{
		$response = array("status"=>0);
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);

}else if($action == 'get_attr'){
	
	$UsersID = $_POST["UsersID"];
	$TypeID = intval($_POST["TypeID"]);
	$ProductsID = intval($_POST["ProductsID"]);
			
	$html = build_attr_html($TypeID,$ProductsID);
	
	$Data = array(
		"status"=>1,
		"content"=>$html,
		"msg"=>"获取产品属性成功"
	);
	
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}else if($action == 'clear_dis_level'){
	
	/*$default = array(1=>array('name'=>'','Saleroom'=>'',''=>'','Bonus'=>''),
					 2=>array('name'=>'','Saleroom'=>'',''=>'','Bonus'=>''),
					 3=>array('name'=>'','Saleroom'=>'',''=>'','Bonus'=>''),
					 4=>array('name'=>'','Saleroom'=>'',''=>'','Bonus'=>''));
    */					 
																   
	$data = array("Pro_Title_Level"=>"");
	$condition =  "where Users_ID='".$_SESSION["Users_ID"]."'";

	$Flag = $DB->set('shop_distribute_config',$data,$condition);
	
	if($Flag){
		$response =  array('status'=>1,'msg'=>'分销级别信息清空成功,无需再点提交');
		
	}else{
		
		$response =  array('status'=>0,'msg'=>'分销级别信息清空失败');
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
}else if($action == 'get_attr_group'){
	
	$group_list = get_attr_groups($_GET['Type_ID'],$_SESSION["Users_ID"]);
	
	if(count($group_list) >0 ){
		 $response['status'] = 1;
		 $response['content'] =  $group_list;
	}else{
		$response['status'] = 0;
		$response['msg'] = '此产品类型下无属性组';
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	
		
}else if($action == 'get_withdraw_edit_form'){
	
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Method_ID= '".$_GET['Method_ID']."'";
	$rsMethod = $DB->getRs('shop_withdraw_method','*',$condition);
	
	$smarty->assign('Method',$rsMethod);	
	$content = $smarty->fetch('withdraw_method_edit_form.html');
	$response = array("status"=>1,'content'=>$content);

	echo json_encode($response,JSON_UNESCAPED_UNICODE);
}else if($action == 'get_shipping_company_edit_form'){
	
    //获取所要编辑快递公司信息	
	$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Shipping_ID= '".$_GET['Shipping_ID']."'";
	$rsShipping = $DB->getRs('shop_shipping_company','*',$condition);
	
	$Business_Name_List = array('express'=>'快递','common'=>'平邮');
	
	
	$Business_List = array();
	$Business_Checked_List = explode(',',$rsShipping['Shipping_Business']);
	
	foreach($Business_Name_List as  $key=>$item){
		$Business_List[$key]['Name'] = $item;	
		if(in_array($key,$Business_Checked_List)){
			$Business_List[$key]['Checked'] = 1;
		}else{
		   $Business_List[$key]['Checked'] = 0;
		}
	}


	$smarty->assign('Shipping',$rsShipping);	
	$smarty->assign('Business_List',$Business_List);
	$content = $smarty->fetch('shipping_company_edit_form.html');
	$response = array("status"=>1,'content'=>$content);

	echo json_encode($response,JSON_UNESCAPED_UNICODE);

}else if($action == 'get_deliver_content'){
	
   $shipping_company =  $_GET['Shipping_ID'];	
   $shipping_by_method = $_GET['By_Method'];
   
   $content = build_shipping_section_html($smarty,$_SESSION['Users_ID'],$shipping_company,$shipping_by_method);
   $response = array('status'=>1,'content'=>$content,'msg'=>'获取信息成功');
   echo json_encode($response,JSON_UNESCAPED_UNICODE);

}


