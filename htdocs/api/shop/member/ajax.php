<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/backup.class.php');

$base_url = base_url();

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo 'error';
	exit;
}
$backup = new backup($DB,$UsersID);
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
	$OrderID = $_POST["OrderID"];
	$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID='".$OrderID."'");
	if(!$rsOrder){
		$Data=array(
			"status"=>0,
			"msg"=>'订单不存在'
		);
	}elseif($rsOrder["Order_Status"]<>3 && $rsOrder["Order_Status"]<>2){
		$Data=array(
			"status"=>0,
			"msg"=>'只有“已付款或已发货”状态下的订单才可申请退款'
		);
	}else{
		$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
		if(empty($CartList[$_POST["ProductsID"]][$_POST["KEY"]])){
			$Data=array(
				"status"=>0,
				"msg"=>'退款的商品不存在'
			);
		}else{
			$item = $CartList[$_POST["ProductsID"]][$_POST["KEY"]];
			if($item["Qty"] < $_POST["Qty"]){
				$Data=array(
					"status"=>0,
					"msg"=>'退款的退款数量大于商品总数量'
				);
			}else{
				$backup->add_backup($rsOrder, $_POST["ProductsID"], $_POST["KEY"], $_POST["Qty"], $_POST["Reason"], $_POST["Account"]);
				
				$Data=array(
					"status"=>1,
					"url"=>$base_url.'api/'.$UsersID.'/shop/member/backup/status/0/'
				);
			}
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}elseif($action == "backup_send"){
	$BackID = $_POST["BackID"];
	$rsBack=$DB->GetRs("user_back_order","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Back_ID='".$BackID."'");
	if(!$rsBack){
		$Data=array(
			"status"=>0,
			"msg"=>'退款单不存在'
		);
	}elseif($rsBack["Back_Status"]<>1){
		$Data=array(
			"status"=>0,
			"msg"=>'只有“卖家同意状态下的退货单才可发货操作”'
		);
	}else{
		$backup->update_backup("buyer_send", $BackID, $_POST["shipping"].'||%$%'.$_POST["shippingID"]);
		$Data = array(
			"status"=>1,
			"url"=>"/api/".$UsersID."/shop/member/backup/status/0/"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);	
	
}elseif($action=="commit"){
	$OrderID = $_POST["OrderID"];
	$rsConfig = $DB->GetRs("shop_config","Commit_Check","where Users_ID='".$UsersID."'");
	$rsOrder=$DB->GetRs("user_order","*","where Order_ID=".$OrderID." and User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Order_Status=4");
	if(!$rsOrder){
		$Data=array(
			"status"=>2,
			"msg"=>"无此订单"
		);
		
	}else{
		if($rsOrder["Is_Commit"]==1){
			$Data=array(
				"status"=>4,
				"msg"=>"此订单已评论过，不可重复评论"
			);
		}else{
			$Data1=array(
				"Is_Commit"=>1
			);
			
			$DB->Set("user_order",$Data1,"where Order_ID=".$OrderID);
			$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
			foreach($CartList as $key=>$v){
				$Data=array(
					"MID"=>$rsOrder["Order_Type"],
					"Order_ID"=>$OrderID,
					"Biz_ID"=>$rsOrder["Biz_ID"],
					"Product_ID"=>$key,
					"Score"=>$_POST["Score"],
					"Note"=>$_POST["Note"],
					"Status"=>$rsConfig["Commit_Check"]==1 ? 1 : 0,
					"Users_ID"=>$UsersID,
					"User_ID"=>$_SESSION[$UsersID."User_ID"],
					"CreateTime"=>time()
				);
				$DB->Add("user_order_commit",$Data);
			}
			
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
				"url"=>$base_url.'api/'.$UsersID.'/shop/member/backup/status/2/'
	);
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);

}elseif($action == 'confirm_receive'){
		$rsOrder = $DB->GetRs("user_order","Order_Status","where Order_ID=".$_POST['Order_ID']);
		if(!$rsOrder){
			$response = array(
				"status"=>0,
				"msg"=>'该订单不存在'
			);
			echo json_encode($response,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if($rsOrder["Order_Status"]<>3){
			$response = array(
				"status"=>0,
				"msg"=>'只有在‘已发货’状态下才可确认收货'
			);
			echo json_encode($response,JSON_UNESCAPED_UNICODE);
			exit;
		}
		Order::observe(new OrderObserver());
		$Order_ID = $_POST['Order_ID'];
		$order = Order::find($Order_ID);
		$Flag = $order->confirmReceive();
	
		if($Flag)
		{
			$response = array(
				"status"=>1,
				"url"=>$base_url.'api/'.$UsersID.'/shop/member/status/4/'
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