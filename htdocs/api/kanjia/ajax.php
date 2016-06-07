<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/userinfo.class.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$action = $_REQUEST['action'];

if($action == 'clear_session'){
 
	$_SESSION = array();
	exit();
}elseif($action == 'addto_cart'){
	//此加入购物车是后台动作，并不在前台显示
	
	$Property = isset($_POST["Property"]) ? $_POST["Property"] : array();
	$rsProducts=$DB->GetRs("shop_products","*","where Users_ID='".$UsersID."' and Products_ID=".$_POST["Product_ID"]);
	$JSON=json_decode($rsProducts['Products_JSON'],true);

	$CartList[$_POST['Product_ID']][]=array(
		"ProductsName"=>$rsProducts["Products_Name"],
		"ImgPath"=>empty($JSON["ImgPath"])?"":$JSON["ImgPath"][0],
		"ProductsPriceX"=>$rsProducts["Products_PriceX"],
		"ProductsPriceY"=>$rsProducts["Products_PriceY"],
		"Cur_Price"=>$_POST['Cur_Price'],
		"Qty"=>1,
		"Property"=>$Property
		);

	$_SESSION[$UsersID."KanjiaCartList"] = json_encode($CartList,JSON_UNESCAPED_UNICODE);

	$Data=array(
		"status"=>1,
	);

	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit();
}elseif($action = "confirm_order"){

	$condition = "where Users_ID = '".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'";
	$condition .= " and Kanjia_ID=".$_POST["KanjiaID"];
	$member_activity = $DB->GetRs('kanjia_member','*',$condition);

	$rsProducts=$DB->GetRs("shop_products","*","where Users_ID='".$UsersID."' and Products_ID=".$_POST["Product_ID"]);
	$JSON=json_decode($rsProducts['Products_JSON'],true);
	$CartList[$_POST['Product_ID']][]=array(
		"ProductsName"=>$rsProducts["Products_Name"],
		"ImgPath"=>empty($JSON["ImgPath"])?"":$JSON["ImgPath"][0],
		"ProductsPriceX"=>$rsProducts["Products_PriceX"],
		"ProductsPriceY"=>$rsProducts["Products_PriceY"],
		"Cur_Price"=>$member_activity['Cur_Price'],
		"Qty"=>1,
		"Property"=>""
	);
	
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

	$Data["Order_Type"]="kanjia";
	$Data["Order_Remark"]=$_POST["Remark"];
	$Data["Order_Shipping"] = json_encode(empty($_POST["Shipping"]["Express"])?array():$_POST["Shipping"],JSON_UNESCAPED_UNICODE);
	
	//购物车
	$rsProduct=$DB->GetRs("shop_products","*","where Users_ID='".$UsersID."' and Products_ID=".$_POST["Product_ID"]);
	$JSON=json_decode($rsProduct['Products_JSON'],true);
	
	$Data["Order_CartList"] = json_encode($CartList,JSON_UNESCAPED_UNICODE);
	$total_price = $_POST["total_price"];
	$Data["Order_TotalAmount"]=$total_price+(empty($_POST["Shipping"]["Price"])?0:$_POST["Shipping"]["Price"]);
	
	$Data["Order_TotalPrice"]=$total_price+(empty($_POST["Shipping"]["Price"])?0:$_POST["Shipping"]["Price"]);
	$Data["Order_CreateTime"]=time();

	$rsConfig=$DB->GetRs("shop_config","CheckOrder","where Users_ID='".$UsersID."'");
	$Data["Order_Status"] = $rsConfig["CheckOrder"] == 1 ? 0 : 1;
	

	$Flag=$DB->Add("user_order",$Data);
	$neworderid = $DB->insert_id();
	
	if($Flag){		

		if($rsConfig["CheckOrder"]==1){
			$url = "/api/".$UsersID."/user/payment/".$neworderid."/";
		}else{
			$url = "/api/".$UsersID."/user/kanjia_order/status/1/";
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
		$weixin_message = new weixin_message($DB,$UsersID,$_SESSION[$UsersID."User_ID"]);
		$contentStr = '您已成功提交微砍价订单，<a href="http://'.$_SERVER["HTTP_HOST"].'/api/'.$UsersID.'/user/kanjia_order/detail/'.$neworderid.'/">查看详情</a>';
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



