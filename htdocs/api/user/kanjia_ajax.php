<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Framework/Conn.php');

$UsersID=$_GET['UsersID'];
$UserID=$_SESSION[$UsersID."User_ID"];
$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];

if($action == 'payment'){
	$OrderID=empty($_POST['OrderID'])?0:$_POST['OrderID'];
	$rsOrder=$DB->GetRs("user_order","*","where Users_ID='".$UsersID."' and Order_ID='".$OrderID."'");
	$rsUser = $DB->GetRs("user","User_Money,User_PayPassword","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
	$PaymentMethod = array(
		"微支付"=>"1",
		"支付宝"=>"2",
		"线下支付"=>"3"
	);

	if($_POST['PaymentMethod']=="线下支付" || $rsOrder["Order_TotalPrice"]<=0){
		$Data=array(
			"Order_PaymentMethod"=>$_POST['PaymentMethod'],
			"Order_PaymentInfo"=>$_POST["PaymentInfo"],
			"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"],
			"Order_Status"=>1
		);

		$Status=1;
		
		
		$Flag=$DB->Set("user_order",$Data,"where  Order_ID=".$OrderID);
		
		$url=empty($_POST['DefautlPaymentMethod'])?"/api/".$UsersID."/user/kanjia_order/status/".$Status."/":"/api/".$UsersID."/user/kanjia_order/detail/".$_POST['OrderID']."/";
		
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
	}elseif($_POST['PaymentMethod']=="余额支付" && $rsUser["User_Money"]>=$rsOrder["Order_TotalPrice"]){//余额支付
		//增加资金流水
		if(!$_POST["PayPassword"]){
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
				'Amount'=>$rsOrder["Order_TotalPrice"],
				'Total'=>$rsUser['User_Money']-$rsOrder["Order_TotalPrice"],
				'Note'=>"商城购买支出 -".$rsOrder["Order_TotalPrice"]." (订单号:".$OrderID.")",
				'CreateTime'=>time()		
			);
			$Flag=$DB->Add('user_money_record',$Data);
			//更新用户余额
			$Data=array(				
				'User_Money'=>$rsUser['User_Money']-$rsOrder["Order_TotalPrice"]				
			);
			$Flag=$DB->Set('user',$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
			$Data=array(
				"Order_PaymentMethod"=>$_POST['PaymentMethod'],
				"Order_PaymentInfo"=>"",
				"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"],
				'Order_Status'=>2			
			);
			$Flag=$DB->Set('user_order',$Data,"where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID=".$OrderID);
			$url="/api/".$UsersID."/user/kanjia_order/detail/".$_POST['OrderID']."/";
			if($Flag){
				$Data=array(
					"status"=>1,
					"url"=>$url
				);
			}else{
				$Data=array(
					"status"=>0,
					"msg"=>'余额支付失败'
				);
			}
		}			
	}else{//在线支付
		$Data=array(
			"Order_PaymentMethod"=>$_POST['PaymentMethod'],
			"Order_PaymentInfo"=>"",
			"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"]
		);

		$Flag=$DB->Set("user_order",$Data,"where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Order_ID=".$OrderID);
		
		$url="/api/".$UsersID."/user/pay/".$OrderID."/".$PaymentMethod[$_POST['PaymentMethod']]."/";
		
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
	
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit();
}elseif($action == 'commit'){

	$OrderID = $_POST["OrderID"];
	$rsConfig = $DB->GetRs("shop_config","Commit_Check","where Users_ID='".$UsersID."'");
	$rsOrder=$DB->GetRs("user_order","*","where Order_ID=".$OrderID." and User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Order_Status=3");
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
			
			$DB->Set("user_order",$Data1,"where Order_ID=".$OrderID);
			$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
			foreach($CartList as $key=>$v){
				$Data=array(
					"MID"=>$rsOrder["Order_Type"],
					"Order_ID"=>$OrderID,
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
	exit();
}