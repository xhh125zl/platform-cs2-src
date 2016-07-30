<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/userinfo.class.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');
$base_url = base_url();

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo 'error';
	exit;
}

$action=empty($_REQUEST["action"])?"":$_REQUEST["action"];
if($action=="address"){
	if(empty($_POST['AddressID'])){
		//增加
		$Data=array(
			"Address_Name"=>$_POST['Name'],
			"Address_Mobile"=>$_POST["Mobile"],
			"Address_Province"=>$_POST["Province"],
			"Address_City"=>$_POST["City"],
			"Address_Area"=>$_POST["Area"],
			"Address_Detailed"=>$_POST["Detailed"],
			"Users_ID"=>$UsersID,
			"User_ID"=>$_SESSION[$UsersID."User_ID"]
		);
		$Flag=$DB->Add("user_address",$Data);
	
	}else{
		//修改
		$Data=array(
			"Address_Name"=>$_POST['Name'],
			"Address_Mobile"=>$_POST["Mobile"],
			"Address_Province"=>$_POST["Province"],
			"Address_City"=>$_POST["City"],
			"Address_Area"=>$_POST["Area"],
			"Address_Detailed"=>$_POST["Detailed"]
		);
		$Flag=$DB->Set("user_address",$Data,"where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Address_ID=".$_POST['AddressID']);
	}
	
	if($Flag){
		$Data=array(
			"status"=>1
		);
	}else{
		$Data=array(
			"status"=>0
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}elseif($action == "apply_backup"){

	$back_list = array();
	foreach($_POST['Products_ID'] as $Products_ID){
		$back_list[$Products_ID] = array(
									'Products_Name'=>$_POST['Products_Name'][$Products_ID],
									'Products_Image'=>$_POST['Products_Image'][$Products_ID],
									'Products_Price'=>$_POST['Products_Price'][$Products_ID],
									'back_num'=>$_POST['backup_num'][$Products_ID],
									'reason'=>$_POST['reason'][$Products_ID]	
									);
	}
	
	$data = array('Users_ID'=>$UsersID,
				  'User_ID'=>$_SESSION[$UsersID.'User_ID'],
				  'Back_SN' => build_order_no(),
				  'Back_Type'=>'cloud',
				  'Back_Json'=>json_encode($back_list,JSON_UNESCAPED_UNICODE),
				  'Back_Status'=>0,
				  'Back_CreateTime'=>time(),
				  'Order_ID'=>$_POST['Order_ID'],);
				  
	//获取店主ID
	//商城配置信息
	$rsConfig = shop_config($UsersID);
	//分销相关设置
	$dis_config = dis_config($UsersID);
	//合并参数
	$rsConfig = array_merge($rsConfig,$dis_config);
	$owner = get_owner($rsConfig,$UsersID);
	$data['Owner_ID'] = $owner['id'];
				  
	$Flag = $DB->add('user_back_order',$data);
	$Order_ID = $_POST['Order_ID'];
	$condition = "where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID=".$Order_ID;
	$data = array('Is_Backup'=>1);
	
	$DB->set("user_order",$data,$condition);
	
	if($Flag){
		$Data=array(
			"status"=>1,
			"url"=>$base_url.'api/'.$UsersID.'/cloud/member/backup/status/0/'
		);
	}else{
		$Data=array(
			"status"=>0
		);
	}
	
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}elseif($action=="commit"){
	$OrderID = $_POST["OrderID"];
	$rsConfig = $DB->GetRs("shop_config","Commit_Check","where Users_ID='".$UsersID."'");
	$rsOrder=$DB->GetRs("shipping_orders","*","where Orders_ID=".$OrderID." and User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Orders_Status=3");
	if(!$rsOrder){
		$Data=array(
			"status"=>2,
			"msg"=>"无此订单"
		);
		
	}else{
		if($rsOrder["Is_Commit"]==1){
			$Data=array(
				"status"=>3,
				"msg"=>"此订单已评论过，不可重复评论"
			);
		}else{
			$Data1=array(
				"Is_Commit"=>1
			);
			
			$DB->Set("shipping_orders",$Data1,"where Orders_ID=".$OrderID);
				$Data=array(					
					"Order_ID"=>$OrderID,					
					"Score"=>$_POST["Score"],
					"Note"=>$_POST["Note"],
					"Status"=>$rsConfig["Commit_Check"]==1 ? 1 : 0,
					"Users_ID"=>$UsersID,
					"User_ID"=>$_SESSION[$UsersID."User_ID"],
					"CreateTime"=>time()
				);
				$DB->Add("shipping_orders_commit",$Data);
			$Data=array(
				"status"=>1
			);
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}elseif($action == 'submit_shipping'){
	
	$Back_ID = $_POST['Back_ID'];
	$data = array("Back_Shipping"=>$_POST['Back_Shipping'],
				  "Back_ShippingID"=>$_POST['Back_ShippingID'],
				  "Back_Status"=>2);
	
	$condition = "where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Back_ID=".$Back_ID;
	
	$DB->set('user_back_order',$data,$condition);			  
	$response = array(
				"status"=>1,
				"url"=>$base_url.'api/'.$UsersID.'/cloud/member/backup/status/2/'
	);
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);

}elseif($action == 'confirm_receive'){
	
		Order::observe(new OrderObserver());
		$Order_ID = $_POST['Order_ID'];
		$order = Order::find($Order_ID);
		$Flag = $order->confirmReceive();
	
		if($Flag)
		{
			$response = array(
				"status"=>1,
				"url"=>$base_url.'api/'.$UsersID.'/cloud/member/backup/status/3/'
			);

			echo json_encode($response,JSON_UNESCAPED_UNICODE);
		}else
		{
			$response = array(
				"status"=>0,
				"msg"=>'确认收货失败'
			);
			echo json_encode($response,JSON_UNESCAPED_UNICODE);
		}
		
		exit();
		
}elseif($action = "confirm_order"){//运费支付2015年11月14日

	$condition = "where Users_ID = '".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'";
	$condition .= " and Cloud_Detail_ID=".$_POST["DetailID"];
	$activity = $DB->GetRs('cloud_products_detail','*',$condition);

	$rsProducts = $DB->GetRs("cloud_products","*","where Users_ID='".$UsersID."' and Products_ID=".$_POST["Products_ID"]);
	$JSON = json_decode($rsProducts['Products_JSON'],true);
	$CartList[$_POST['Products_ID']][] = array(
		"ProductsName" => $rsProducts["Products_Name"],
		"ImgPath" => empty($JSON["ImgPath"]) ? "" : $JSON["ImgPath"][0],
		"ProductsPriceX" => $rsProducts["Products_PriceX"],
		"ProductsPriceY" => $rsProducts["Products_PriceY"],
		"Cur_Price" => 0,
		"Qty" => 1,
		"Property" => ""
	);
	
	$ShopConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
	$is_shipping = $_POST["is_shipping"];
	$total_price = 0;
	
	$Data = array(
		"Users_ID" => $UsersID,
		"User_ID" => $_SESSION[$UsersID."User_ID"]
	);
	
	if($is_shipping == 1){//物流检测
		if(isset($_POST["AddressID"])){
			$AddressID = $_POST["AddressID"];
		}else{
			$Data=array(
				"status"=>0,
				"msg"=>"请填写联系人信息"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		$rsAddress = $DB->GetRs("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Address_ID='".$AddressID."'");
		if(!$rsAddress){
			$Data = array(
				"status" => 0,
				"msg" => "联系人信息无效"
			);
			echo json_encode($Data, JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		$Data = array(
			"Address_Name"=>$rsAddress['Address_Name'],
			"Address_Mobile"=>$rsAddress["Address_Mobile"],
			"Address_Province"=>$rsAddress["Address_Province"],
			"Address_City"=>$rsAddress["Address_City"],
			"Address_Area"=>$rsAddress["Address_Area"],
			"Address_Detailed"=>$rsAddress["Address_Detailed"],
			"Users_ID"=>$UsersID,
			"User_ID"=>$_SESSION[$UsersID."User_ID"]
		);
		//运费计算
		$City_Code = $rsAddress['Address_City'];
		$Shipping_ID = !empty($_POST['Shipping_ID']) ? $_POST['Shipping_ID'] : 0;
		if(!empty($Shipping_ID)){
			$info_array = array(
				"qty"=>1,
				"weight"=>$rsProducts["Products_Weight"],
				"money"=>0
			);
			
			$shipping_company_dropdown = get_front_shiping_company_dropdown($UsersID,$ShopConfig);
			if(empty($shipping_company_dropdown[$Shipping_ID])){
				$Data=array(
					"status"=>0,
					"msg"=>"物流不存在"
				);
				echo json_encode($Data,JSON_UNESCAPED_UNICODE);
				exit;
			}
			$total_shipping_fee = get_shipping_fee($UsersID,$Shipping_ID,"express",$City_Code,$ShopConfig,$info_array);
			$total_price = $total_price + $total_shipping_fee;
			$shipping_temp = array(
				"Express"=>$shipping_company_dropdown[$Shipping_ID],
				"Price"=>$total_shipping_fee
			);
			$Data["Order_Shipping"] = json_encode($shipping_temp,JSON_UNESCAPED_UNICODE);
		}		
	}	

	$Data["Order_Type"]="shipping";
	$Data["Order_Remark"]=$_POST["Remark"];
	
	//购物车
	
	$Data["Order_CartList"] = json_encode($CartList,JSON_UNESCAPED_UNICODE);
	$Data["Order_TotalPrice"] = $Data["Order_TotalAmount"] = $total_price;
	$Data["Order_CreateTime"] = time();

	$Data["Order_Status"] = 1;
	$Data["Order_IsVirtual"] = 1;
	$Data["Order_Isrecieve"] = 1;

	$Flag = $DB->Add("user_order",$Data);
	$neworderid = $DB->insert_id();
	
	if($Flag){
		$url = "/api/".$UsersID."/cloud/cart/payment/".$neworderid."/";
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
		$weixin_message = new weixin_message($DB,$UsersID,$_SESSION[$UsersID."User_ID"]);
		$contentStr = '您已成功提交订单，<a href="http://'.$_SERVER["HTTP_HOST"].'/api/'.$UsersID.'/cloud/member/shipping_order/detail/'.$neworderid.'/">查看详情</a>';
		$weixin_message->sendscorenotice($contentStr);
		
		$Data=array(
			"status"=>1,
			"url"=>$url
		);

	}else{
		$Data=array(
			"status"=>0
		);
	}

	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}

/**
 * 得到新订单号
 * @return  string
 */
function build_order_no()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}


?>