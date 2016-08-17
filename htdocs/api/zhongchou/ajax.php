<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo 'error';
	exit;
}
$type=empty($_REQUEST["type"])?"":$_REQUEST["type"];
if($type=="check"){
	if(isset($_POST["itemid"])){
		$itemid=$_POST["itemid"];
	}else{
		echo 'error';
		exit;
	}
	if(isset($_POST["prizeid"])){
		$prizeid=$_POST["prizeid"];
	}else{
		echo 'error';
		exit;
	}
	$item = $DB->GetRs("zhongchou_project","*","where usersid='".$UsersID."' and itemid=$itemid");
	if(!$item){
		$Data=array(
			"status"=>0,
			"msg"=>"项目不存在"
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		if($item["fromtime"]>time()){
			$Data=array(
				"status"=>0,
				"msg"=>"项目未开始"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		if($item["totime"]<time()){
			$Data=array(
				"status"=>0,
				"msg"=>"项目已结束"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		$prize = $DB->GetRs("zhongchou_prize","*","where usersid='".$UsersID."' and projectid=$itemid and prizeid=$prizeid and money=".$_POST["money"]);
		if(!$prize){
			$Data=array(
				"status"=>0,
				"msg"=>"该项目与支持金额不匹配"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
	$AddressID=empty($_POST['AddressID'])?0:$_POST['AddressID'];
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
		$rsAddress=$DB->GetRs("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Address_ID='".$AddressID."'");
		$Data=array(
			"Address_Name"=>$rsAddress['Address_Name'],
			"Address_Mobile"=>$rsAddress["Address_Mobile"],
			"Address_Province"=>$rsAddress["Address_Province"],
			"Address_City"=>$rsAddress["Address_City"],
			"Address_Area"=>$rsAddress["Address_Area"],
			"Address_Detailed"=>$rsAddress["Address_Detailed"],
			"Users_ID"=>$UsersID,
			"User_ID"=>$_SESSION[$UsersID."User_ID"]
		);
	}
	$Data["Order_Type"]="zhongchou_".$itemid;
	$Data["Order_Remark"]=$_POST["Remark"];
	$Data["Order_Shipping"]="";
	$Data["Order_CartList"]=$prize["title"];
	$Data["Order_TotalPrice"] = $Data["Order_TotalAmount"] = $_POST["money"];
	$Data["Order_CreateTime"]=time();
	$Data["Order_Status"]=0;
	$Data["Order_PaymentMethod"] = "微支付";
	$Data["Biz_ID"] = $item['Biz_ID'];
	$Flag=$DB->Add("user_order",$Data);
	$neworderid = $DB->insert_id();
	if($Flag){		
		$url="/api/".$UsersID."/zhongchou/pay/".$neworderid."/";
		$Data=array(
			"status"=>1,
			"url"=>$url
		);
	}else{
		$Data=array(
			"status"=>0,
			"msg"=>"服务器繁忙，请稍候再试"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}elseif($type=="checkpay"){
	if(isset($_POST["itemid"])){
		$itemid=$_POST["itemid"];
	}else{
		echo 'error';
		exit;
	}
	$item = $DB->GetRs("zhongchou_project","*","where usersid='".$UsersID."' and itemid=$itemid");
	if(!$item){
		$Data=array(
			"status"=>0,
			"msg"=>"项目不存在"
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		if($item["fromtime"]>time()){
			$Data=array(
				"status"=>0,
				"msg"=>"项目未开始"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		if($item["totime"]<time()){
			$Data=array(
				"status"=>0,
				"msg"=>"项目已结束"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
	$money = empty($_POST["money"]) ? 0 : floatval($_POST["money"]);
	if($money<=0){
		$Data=array(
			"status"=>0,
			"msg"=>"支持金额必须大于零"
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	$Data["Users_ID"]=$UsersID;
	$Data["User_ID"]=$_SESSION[$UsersID."User_ID"];
	$Data["Order_Type"]="zhongchou_".$itemid;
	$Data["Order_TotalPrice"] = $Data["Order_TotalAmount"] = $money;
	$Data["Order_CreateTime"]=time();
	$Data["Order_Status"]=0;
	$Data["Order_PaymentMethod"] = "微支付";
	$Data["Biz_ID"] = $item['Biz_ID'];
	$Flag=$DB->Add("user_order",$Data);
	
	$neworderid = $DB->insert_id();
	if($Flag){		
		$url="/api/".$UsersID."/zhongchou/pay/".$neworderid."/";
		$Data=array(
			"status"=>1,
			"url"=>$url
		);
	}else{
		$Data=array(
			"status"=>0,
			"msg"=>"服务器繁忙，请稍候再试"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}
?>