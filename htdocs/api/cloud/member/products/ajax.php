<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/Framework/Ext/sms.func.php');

if(isset($_GET['UsersID'])){
	$UsersID=$_GET['UsersID'];
}else{
	echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
}

if(isset($_SESSION[$UsersID."User_ID"])){
	$UserID=$_SESSION[$UsersID."User_ID"];
	$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];
	$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
	$UserLevel=json_decode($rsConfig['UserLevel'],true);
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$UserID);
	$RecordType=array("获得积分"=>2,"使用积分"=>3);
	
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	
	if($action == 'change_shipping_method'){
		$DetailID = $_POST['DetailID'];
		$rsDetail = $DB->GetRs("cloud_products_detail","*","where Users_ID='".$UsersID."' and Cloud_Detail_ID=".$DetailID);
        $rsProducts = $DB->GetRs("cloud_products","*","where Users_ID='".$UsersID."' and Products_ID=".$rsDetail['Products_ID']);
		$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
		$Shipping_ID = $_POST['Shipping_ID'];
		$City_Code = $_POST['City_Code'];
		$Business = 'express';
		$rsProducts['weight'] = $rsProducts['Products_Weight'];
		$rsProducts['qty'] = 1;
		$rsProducts['money'] = 0;
		$total_shipping_fee = get_shipping_fee($UsersID,$Shipping_ID,$Business,$City_Code,$rsConfig,$rsProducts);
		
		$Data = array(
		   "status"=>1,
		   "total_shipping_fee"=>$total_shipping_fee,
		);
	}elseif($action=='shipping_change'){
		$rsDetail = $DB->GetRs("cloud_products_detail","*","where Users_ID='".$UsersID."' and Cloud_Detail_ID=".$_POST['DetailID']);
		$rsProducts = $DB->GetRs("cloud_products","*","where Users_ID='".$UsersID."' and Products_ID=".$rsDetail['Products_ID']);
		if(!$rsDetail){
			$Data = array(
				"status"=>0,
				"msg"=>"你要领取的商品不存在",
				"url"=>'/api/'.$UsersID.'/cloud/member/products/no/'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		$item = $DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Detail_ID=".$_POST['DetailID']." and Orders_Status<>4");
		if($item){
			$Data = array(
				"status"=>0,
				"msg"=>"您已领取过此商品，请勿重复领取",
				"url"=>'/api/'.$UsersID.'/cloud/member/products/'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
			
		$rsAddress = $DB->GetRs("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$UserID."' and Address_ID='".$_POST['AddressID']."'");
		$Data=array(
			"Users_ID"=>$UsersID,
			"User_ID"=>$UserID,
			"Detail_ID"=>$_POST['DetailID'],
			"Orders_CreateTime"=>time(),
			"Orders_IsShipping"=>1,
			"Address_Name"=>$rsAddress['Address_Name'],
			"Address_Mobile"=>$rsAddress["Address_Mobile"],
			"Address_Province"=>$rsAddress["Address_Province"],
			"Address_City"=>$rsAddress["Address_City"],
			"Address_Area"=>$rsAddress["Address_Area"],
			"Address_Detailed"=>$rsAddress["Address_Detailed"],
			"Orders_Shipping"=>json_encode(array("Express"=>$_POST["Order_Shipping"]["Express"],"Price"=>$_POST["total_price"]),JSON_UNESCAPED_UNICODE),
		);
			if($_POST["total_price"]>0 && $rsProducts['Products_IsShippingFree']==0){
				$Data["Orders_TotalPrice"] = $_POST["total_price"];
				$Data["Orders_Status"] = 0;
			}else{
				$Data["Orders_Status"] = 1;
			}
			$Flag = $DB->Add('shipping_orders', $Data);
			if($Flag){
				if($_POST["total_price"]>0 && $rsProducts['Products_IsShippingFree']==0){
					$url = '/api/'.$UsersID.'/cloud/member/products/payment/'.$DB->insert_id()."/";
				}else{
					$url = '/api/'.$UsersID.'/cloud/member/products/';
				}
			}
			
		if($Flag){
			$Data = array(
				"status"=>1,
				"url"=>$url
			);
		}else{
			$Data = array(
				"status"=>0,
				"msg"=>"系统发生错误"
			);
		}
	}elseif($action=='payment'){
		$OrderID = empty($_POST['OrderID'])?0:$_POST['OrderID'];
		$rsOrder = $DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and Orders_ID='".$OrderID."'");
		$isshipping = $rsOrder["Orders_IsShipping"];
		
		$PaymentMethod = array(
			"微支付"=>"1",
			"支付宝"=>"2",
			"线下支付"=>"3",
			"易宝支付"=>"4",
		);
	
		if($_POST['PaymentMethod']=="线下支付"){
			$Data=array(
				"Orders_PaymentMethod"=>$_POST['PaymentMethod'],
				"Orders_PaymentInfo"=>$_POST["PaymentInfo"],
				"Orders_Status"=>0
			);
			
			$Status=1;
			$Flag=$Flag&&$DB->Set("shipping_orders",$Data,"where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID=".$OrderID);
			$url="/api/".$UsersID."/cloud/member/products/";
			if($Flag){
				$Data=array(
					"status"=>1,
					"url"=>$url
				);
			}else{
				$Data=array(
					"status"=>0,
					"msg"=>'线下支付提交失败'
				);
			}
		}elseif($_POST['PaymentMethod']=="余额支付" && $rsUser["User_Money"]>=$rsOrder["Orders_TotalPrice"]){//余额支付
			//增加资金流水
			if($rsOrder["Orders_Status"] != 0){
				$Data=array(
					"status"=>0,
					"msg"=>'该订单状态不是待付款状态，不能付款'
				);
			}elseif(!$_POST["PayPassword"]){
				$Data=array(
					"status"=>0,
					"msg"=>'请输入支付密码'
				);
				
			}elseif(md5($_POST["PayPassword"])!=$rsUser["User_PayPassword"]){
				$Data=array(
					"status"=>0,
					"msg"=>'支付密码输入错误'
				);
			}else{
				$Data=array(
					'Users_ID'=>$UsersID,
					'User_ID'=>$_SESSION[$UsersID.'User_ID'],				
					'Type'=>0,
					'Amount'=>$rsOrder["Orders_TotalPrice"],
					'Total'=>$rsUser['User_Money']-$rsOrder["Orders_TotalPrice"],
					'Note'=>"云购商品运费支出 -".$rsOrder["Orders_TotalPrice"]." (订单号:".$OrderID.")",
					'CreateTime'=>time()		
				);
				$Flag=$Flag&&$DB->Add('user_money_record',$Data);
				//更新用户余额
				$Data=array(				
					'User_Money'=>$rsUser['User_Money']-$rsOrder["Orders_TotalPrice"]	
				);
				$Flag=$Flag&&$DB->Set('user',$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
				
				$Data=array(
					"Orders_PaymentMethod"=>$_POST['PaymentMethod'],
					"Orders_PaymentInfo"=>"",
					"Orders_Status"=>1
				);
				
				$Flag=$Flag&&$DB->Set('shipping_orders',$Data,"where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID=".$OrderID);
				$url="/api/".$UsersID."/cloud/member/products/";
				if($Flag){
					$Data=array(
						"status"=>1,
						"url"=>$url
					);
				}else{
					$Data=array(
						"status"=>0,
						"msg"=>'支付失败'
					);
				}
				
				//require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
				
				//$pay_order = new pay_order($DB,$OrderID);
				//$Data = $pay_order->pay_orders();
			}			
		}else{//在线支付
			$Data=array(
				"Orders_PaymentMethod"=>$_POST['PaymentMethod'],
				"Orders_PaymentInfo"=>""
			);
			$Flag=$DB->Set("shipping_orders",$Data,"where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID=".$OrderID);
			
			$url="/api/".$UsersID."/cloud/member/products/pay/".$OrderID."/".$PaymentMethod[$_POST['PaymentMethod']]."/";
			
			if($Flag){
				$Data=array(
					"status"=>1,
					"url"=>$url
				);
			}else{
				$Data=array(
					"status"=>0,
					"msg"=>'在线支付出现错误'
				);
			}		
		}
		
	}elseif($action == 'concel'){
		$ordersid = $_POST["ordersid"];
		$rsOrder = $DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and Orders_ID='".$ordersid."'");
		if($rsOrder["Orders_Status"]==0 && $rsOrder["Orders_IsShipping"]==1){
			$Flag = $DB->Set("shipping_orders","Orders_Status=4","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID=".$ordersid);
			if($Flag){
				$Data = array(
					"status"=>1,
					"url"=>"/api/".$UsersID."/cloud/member/products/"
				);
			}
		}
	}elseif($action=='recieve'){
		$ordersid = $_POST["ordersid"];
		$rsOrder = $DB->GetRs("shipping_orders","*","where Users_ID='".$UsersID."' and Orders_ID='".$ordersid."'");
		if($rsOrder["Orders_Status"]==2 && $rsOrder["Orders_IsShipping"]==1){
			$Flag = $DB->Set("shipping_orders","Orders_Status=3,Orders_FinishTime=".time(),"where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Orders_ID=".$ordersid);
			if($Flag){
				$Data = array(
					"status"=>1,
					"url"=>"/api/".$UsersID."/cloud/member/products/"
				);
			}
		}
	}
	
	if($Flag){
		mysql_query("commit");
	}else{
		mysql_query("roolback");
	}
}
echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
?>