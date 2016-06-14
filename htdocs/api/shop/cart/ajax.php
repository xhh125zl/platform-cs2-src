<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/Framework/Ext/virtual.func.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/Framework/Ext/sms.func.php');

ini_set("display_errors","On");
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo 'error';
	exit;
}

$action = empty($_REQUEST["action"]) ? "" : $_REQUEST["action"];

if(empty($action)){//加入购物车
	//参数判断
	if(isset($_POST["ProductsID"])){
		$ProductsID = $_POST["ProductsID"];
	}else{
		$Data = array(
			"status"=>0,
			"msg"=>'非法提交'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	$rsProducts = $DB->GetRs("shop_products","*","where Users_ID='".$UsersID."' and Products_ID=".$ProductsID." and Products_SoldOut=0 and Products_Status=1");
	if(!$rsProducts){
		$Data = array(
			"status"=>0,
			"msg"=>'产品已下架'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($rsProducts["Products_Count"] > 0 && $rsProducts["Products_Count"] < $_POST["Qty"]){
		$Data = array(
			"status" => 0,
			"msg" => '产品库存不足，最多能购买' . $rsProducts["Products_Count"] . '件'
		);
		echo json_encode($Data, JSON_UNESCAPED_UNICODE);
		exit;
	}
	$BizID = $rsProducts["Biz_ID"];
	$cur_price = $rsProducts["Products_PriceX"];
	/*如果此产品包含属性*/
	$Property = array();
	if(strlen($_POST['spec_list']) > 0){
		$attr_id = explode(',', $_POST['spec_list']);
		$cur_price = get_final_price($cur_price, $attr_id, $_POST["ProductsID"], $UsersID);		
		$Property = get_posterty_desc($_POST['spec_list'], $UsersID, $_POST["ProductsID"]);
	}
	
	$JSON = json_decode($rsProducts['Products_JSON'], true);//产品图片
	$OwnerID = !empty($_POST['OwnerID']) ? $_POST['OwnerID'] : 0;//店主	
	$cart_key = $UsersID.$_POST['cart_key'];
	$flag = true;
	if($cart_key == $UsersID.'CartList'){
		if(!empty($_SESSION[$cart_key])){
			$CartList = json_decode($_SESSION[$cart_key], true);
			if(!empty($CartList[$BizID][$ProductsID])){
				foreach($CartList[$BizID][$ProductsID] as $k => $v){
					$spec_list = isset($_POST['spec_list']) ? $_POST['spec_list'] : ''; 
					$array = array_diff(explode(',',$spec_list),explode(',',$v["spec_list"]));//计算数组的差集
					if(empty($array)){
						$CartList[$BizID][$ProductsID][$k]["Qty"] += $_POST["Qty"];
						$flag = false;
						break;
					}
				}
			}
		}
	}else{
		$_SESSION[$cart_key] = '';
	}
	//更新购物车
	if($flag){
		$CartList[$BizID][$ProductsID][] = array(
			"ProductsName" => $rsProducts["Products_Name"],
			"ImgPath" => empty($JSON["ImgPath"]) ? "" : $JSON["ImgPath"][0],
			"ProductsPriceX" => $cur_price,
			"ProductsPriceY" => $rsProducts["Products_PriceY"],
			"ProductsWeight" => $rsProducts["Products_Weight"],
			"Products_Shipping" => $rsProducts["Products_Shipping"],
			"Products_Business" => $rsProducts["Products_Business"],
			"Shipping_Free_Company" => $rsProducts["Shipping_Free_Company"],
			"IsShippingFree" => $rsProducts["Products_IsShippingFree"],
			"OwnerID" => $OwnerID,
			"ProductsIsShipping" => $rsProducts["Products_IsShippingFree"],
			"Qty" => $_POST["Qty"],
			"spec_list" => isset($_POST['spec_list']) ? $_POST['spec_list'] : '',
			"Property" => $Property,
			"nobi_ratio" => $rsProducts["nobi_ratio"],
			"platForm_Income_Reward" => $rsProducts["platForm_Income_Reward"],
			"area_Proxy_Reward" => $rsProducts["area_Proxy_Reward"],
			"sha_Reward" => $rsProducts["sha_Reward"]
		);
	}
	
	$_SESSION[$cart_key] = json_encode($CartList, JSON_UNESCAPED_UNICODE);
	
	$qty = 0;
	
	foreach($CartList as $bizid => $bizcart){
		foreach($bizcart as $key => $value){
			foreach($value as $v){
				$qty += $v["Qty"];
			}
		}
	}
	
	$Data=array(
		"status" => 1,
		"qty" => $qty
	);
	
	echo json_encode($Data, JSON_UNESCAPED_UNICODE);exit;
	
}elseif($action == "cart_del"){//从实物购物车中删除产品
	$BizID = $_POST["BizID"];
	$ProductsID = $_POST["ProductsID"];
	$CartID = $_POST["CartID"];
	$Data = array();
	$Data["status"] = 1;
	$CartList=json_decode($_SESSION[$UsersID."CartList"],true);
	unset($CartList[$BizID][$ProductsID][$CartID]);
	if(count($CartList[$BizID][$ProductsID])==0){//购物车中不存在该产品的存储，释放
		unset($CartList[$BizID][$ProductsID]);
	}
	if(count($CartList[$BizID])==0){//购物车中不存在该商家的存储，释放
		unset($CartList[$BizID]);
		$Data["status"] = 2;
	}
	
	if(count($CartList)==0){//购物车中已无商品，释放
		$_SESSION[$UsersID."CartList"] = '';
		$Data["status"] = 3;
		$Data["total"] = 0;
	}else{
		$_SESSION[$UsersID."CartList"]=json_encode($CartList,JSON_UNESCAPED_UNICODE);
		$total=0;
		foreach($CartList as $bizid=>$bizcart){
			foreach($bizcart as $productsid=>$products){
				foreach($products as $carid=>$cart){
					$total+=$cart["Qty"]*$cart["ProductsPriceX"];
				}				
			}
		}
		$Data["total"] = $total;
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}elseif($action == "cart_update"){//更新实物购物车
	$BizID = $_POST["BizID"];
	$ProductsID = $_POST["ProductsID"];
	$CartID = $_POST["CartID"];
	$Type = $_POST["Type"];
	$Qty = $_POST["Qty"];
	if(empty($_SESSION[$UsersID.'CartList'])){
		$Data=array(
			"status"=>0,
			"msg"=>"购物车空的，赶快去逛逛吧！"
		);
	    echo json_encode($Data, JSON_UNESCAPED_UNICODE);exit;
	}
	$CartList = $_SESSION[$UsersID."CartList"] ? json_decode($_SESSION[$UsersID."CartList"],true) : array();
	$total = 0;
	$rsProducts=$DB->GetRs("shop_products","Products_Count","where Users_ID='".$UsersID."' and Products_ID=".$ProductsID);
	if(empty($CartList[$BizID][$ProductsID][$CartID]) || !$rsProducts){
		foreach($CartList as $bizid=>$bizcart){
			foreach($bizcart as $productsid=>$products){
				foreach($products as $carid=>$cart){
					$total+=$cart["Qty"]*$cart["ProductsPriceX"];
				}				
			}
		}
		echo json_encode(array("status"=>0,"msg"=>"该商品不存在","total"=>$total),JSON_UNESCAPED_UNICODE);
		exit;
	}
	$Data = array();
	switch($Type){
		case 'qty_sub'://减少
			if($Qty<=1){
				$Data["status"] = 1;
				$Data["qty"] = 1;
				$Data["msg"] = '最小购买数量为1！';
				$CartList[$BizID][$ProductsID][$CartID]["Qty"] = 1;
			}else{
				$CartList[$BizID][$ProductsID][$CartID]["Qty"] = $Qty-1;
				$Data["status"] = 2;
				$Data["qty"] = $Qty-1;
				$Data["msg"] = '';
			}
		break;
		case 'qty_add'://增加
			if($Qty>=$rsProducts["Products_Count"] && $rsProducts["Products_Count"]>0){
				$Data["status"] = 1;
				$Data["qty"] = $rsProducts["Products_Count"];
				$Data["msg"] = '产品库存不足，最多能购买'.$rsProducts["Products_Count"].'件';
				$CartList[$BizID][$ProductsID][$CartID]["Qty"] = $rsProducts["Products_Count"];
			}else{
				$CartList[$BizID][$ProductsID][$CartID]["Qty"] = $Qty+1;
				$Data["status"] = 2;
				$Data["qty"] = $Qty+1;
				$Data["msg"] = '';
			}
		break;
		case 'change':
			if($Qty<1){
				$Data["status"] = 1;
				$Data["qty"] = 1;
				$Data["msg"] = '最小购买数量为1！';
				$CartList[$BizID][$ProductsID][$CartID]["Qty"] = 1;
			}elseif($Qty>$rsProducts["Products_Count"] && $rsProducts["Products_Count"]>0){
				$Data["status"] = 1;
				$Data["qty"] = $rsProducts["Products_Count"];
				$Data["msg"] = '产品库存不足，最多能购买'.$rsProducts["Products_Count"].'件';
				$CartList[$BizID][$ProductsID][$CartID]["Qty"] = $rsProducts["Products_Count"];
			}else{
				$CartList[$BizID][$ProductsID][$CartID]["Qty"] = $Qty;
				$Data["status"] = 2;
				$Data["qty"] = $Qty;
				$Data["msg"] = '';
			}
		break;
	}
	
	foreach($CartList as $bizid=>$bizcart){
		foreach($bizcart as $productsid=>$products){
			foreach($products as $carid=>$cart){
				$total+=$cart["Qty"]*$cart["ProductsPriceX"];
			}				
		}
	}
	
	$Data["total"] = $total;
	$_SESSION[$UsersID."CartList"] = json_encode($CartList,JSON_UNESCAPED_UNICODE);
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}elseif($action=="favourite"){//收藏商品
	//检测用户是否登陆
	if(empty($_SESSION[$UsersID."User_ID"])){
		$_SESSION[$UsersID."HTTP_REFERER"]='http://'.$_SERVER['HTTP_HOST']."/api/".$UsersID."/shop/products/".$_POST['productId'].'/?wxref=mp.weixin.qq.com';
		$url = 'http://'.$_SERVER['HTTP_HOST']."/api/".$UsersID."/user/login/";
		/*返回值*/
		$Data=array(
			"status"=>0,
			"info"=>"您还为登陆，请登陆！",
			"url"=>$url
		);
		
	}else{

		$insertInfo = array('User_ID'=>$_SESSION[$UsersID.'User_ID'],
							'Products_ID'=>$_POST['productId'],
							'IS_Attention'=>1);
		
		$Result=$DB->Add("user_favourite_products",$insertInfo);
		
			$Data=array(
			"status"=>1,
			"info"=>"收藏成功！",
			
			);
	}

	echo json_encode($Data,JSON_UNESCAPED_UNICODE);	
	
}elseif($action == "cancel_favourite"){//删除收藏
	//检测用户是否登陆
	if(empty($_SESSION[$UsersID."User_ID"])){
		$_SESSION[$UsersID."HTTP_REFERER"]='http://'.$_SERVER['HTTP_HOST']."/api/".$UsersID."/shop/products/".$_POST['productId'].'/?wxref=mp.weixin.qq.com';
		$url = 'http://'.$_SERVER['HTTP_HOST']."/api/".$UsersID."/user/login/";
		/*返回值*/
		$Data=array(
			"status"=>0,
			"info"=>"您还为登陆，请登陆！",
			"url"=>$url
		);
		
	}else{

		$insertInfo = array('User_ID'=>$_SESSION[$UsersID.'User_ID'],
							'Products_ID'=>$_POST['productId'],
							'IS_Attention'=>1);
		
		$DB->Del("user_favourite_products","User_ID='".$_SESSION[$UsersID."User_ID"]."' and Products_ID=".$_POST['productId']);
		
		$Data=array(
			"status"=>1,
			"info"=>"取消收藏成功！",	
			);
	}

	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}elseif($action=="cart_check"){//实物购物车检测
	$CartList=json_decode($_SESSION[$UsersID."CartList"],true);
	if(count($CartList)>0){
		$Data=array(
			"status"=>1,
			"msg"=>""
		);
	}else{
		$Data=array(
			"status"=>0,
			"msg"=>"购物车空的，赶快去逛逛吧！"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	
}elseif($action == "checkout"){ //订单提交（所有类型订单提交都用这个方法）
	$cart_key = $UsersID . $_POST['cart_key'];
	if(empty($_SESSION[$cart_key]) || $_SESSION[$cart_key]=='null' || $_SESSION[$cart_key]==null){
		$tip = array(
			"status" => 0,
			"msg" => "购物车空的，赶快去逛逛吧！"
		);
	    echo json_encode($tip, JSON_UNESCAPED_UNICODE);exit;
	}
	$Data = array (
		"Users_ID" => $UsersID,
		"User_ID" => $_SESSION [$UsersID . "User_ID"] 
	);
	
	if ($cart_key == $UsersID . 'CartList' || $cart_key == $UsersID . 'DirectBuy') {
		
		$AddressID = empty ( $_POST ['AddressID'] ) ? 0 : $_POST ['AddressID'];
		$Need_Shipping = $_POST ['Need_Shipping'];
		if ($Need_Shipping == 1) {
			if (! empty ( $_POST ['AddressID'] )) {
				$rsAddress = $DB->GetRs ( "user_address", "*", "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION [$UsersID . "User_ID"] . "' and Address_ID='" . $AddressID . "'" );
				
				$Data ["Address_Name"] = $rsAddress ['Address_Name'];
				$Data ["Address_Mobile"] = $rsAddress ["Address_Mobile"];
				$Data ["Address_Province"] = $rsAddress ["Address_Province"];
				$Data ["Address_City"] = $rsAddress ["Address_City"];
				$Data ["Address_Area"] = $rsAddress ["Address_Area"];
				$Data ["Address_Detailed"] = $rsAddress ["Address_Detailed"];
			}
		}
	} else {
		$Data ["Order_IsVirtual"] = 1;
		$Data ["Order_IsRecieve"] = $_POST ["recieve"];
		$Data ["Address_Mobile"] = $_POST ["Mobile"];
	}
	
	
	$Data ["Order_Type"] = "shop";
	$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
	//分销相关设置
	$dis_config = dis_config($UsersID);
	//合并参数
	$rsConfig = array_merge($rsConfig,$dis_config);
	$owner = get_owner($rsConfig, $UsersID);
	$Data['Owner_ID'] = $owner['id'];

	//是否加入分销记录
	//$ds_account = get_dsaccount_by_id($DB,$UsersID,$_SESSION[$UsersID."User_ID"]);
	$is_distribute = TRUE;
	$error = false;
	
	$OrderCart = $_SESSION [$cart_key];
	
	$CartList = json_decode($OrderCart,true);
	$CartList = get_filter_cart_list($DB, $CartList);
	//获取每个供货商的运费默认配置
    $Biz_ID_String = implode(',',array_keys($CartList));
	$condition = "where Users_ID  = '".$UsersID."' and Biz_ID in (".$Biz_ID_String.")";
	$rsBizConfigs = $DB1->get('biz','Biz_ID,Biz_Name,Shipping,Default_Shipping,Default_Business',$condition);
	$Biz_Config_List = $DB1->toArray($rsBizConfigs);
 
	$Biz_Config_Dropdown = array();

	foreach($Biz_Config_List as $key=>$item){
		$Biz_Config_Dropdown[$item['Biz_ID']] = $item;
	}
	//var_dump($cart_key);exit;
	//生成订单
	$orderids = array();
	$pre_total = 0;
	$pre_sn = build_pre_order_no();
	$pre_sn = 'PRE'.$pre_sn;
	Dis_Record::observe(new DisRecordObserver());//佣金记录初始化
 
	foreach($CartList as $Biz_ID => $BizCart){
		$Data["Biz_ID"] = $Biz_ID;
		$Data ["Order_Remark"] = $_POST ["Remark"][$Biz_ID];		
		//整理快递信息
		if ($cart_key == $UsersID . 'CartList' || $cart_key == $UsersID . 'DirectBuy'){
			$biz_company_dropdown = get_front_shiping_company_dropdown($UsersID,$Biz_Config_Dropdown[$Biz_ID]);
	    	if(count($biz_company_dropdown) >0 ){
				$express = !empty($biz_company_dropdown[$_POST['Shiping_ID_'.$Biz_ID]])?$biz_company_dropdown[$_POST['Shiping_ID_'.$Biz_ID]]:'';
				$price = !empty($_POST['Biz_Shipping_Fee'][$Biz_ID])?$_POST['Biz_Shipping_Fee'][$Biz_ID]:0;
				$shipping = array('Express'=>$express,'Price'=>$price);
			}else{
			   $shipping = array(); 
			}
			
		}else{
			 $shipping = array('Express'=>'','Price'=>0); 
		}
		
		$total_info = array();
		$total_info = get_order_total_info($UsersID, $CartList, $rsConfig, array(), 0);
		$Data["Order_Shipping"] = json_encode($shipping,JSON_UNESCAPED_UNICODE);	
		$Data["Order_CartList"]= json_encode($BizCart,JSON_UNESCAPED_UNICODE);
		$total_price = 0;
		foreach($BizCart as $Product_ID => $Product_List){
			foreach($Product_List as $k => $v){
					$total_price += $v["Qty"] * $v["ProductsPriceX"];
					
			}
		}
		
		$Data["Order_TotalAmount"] = $total_info["total"] + $total_info["total_shipping_fee"];
		$Data["Order_NeedInvoice"] = isset($_POST['Order_NeedInvoice'][$Biz_ID])?$_POST['Order_NeedInvoice'][$Biz_ID]:0;
		$Data["Order_InvoiceInfo"] = isset($_POST['Order_NeedInvoice'][$Biz_ID])?$_POST['Order_InvoiceInfo'][$Biz_ID]:"";
	    //优惠券使用判断
		
	    if(isset($_POST["CouponID"][$Biz_ID])){//优惠券使用判断
		
			$CouponID = $_POST["CouponID"][$Biz_ID];
			$r = $DB->GetRs("user_coupon_record","*","where Coupon_ID=".$CouponID." and Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Coupon_UseArea=1 and (Coupon_UsedTimes=-1 or Coupon_UsedTimes>0) and Coupon_StartTime<=".time()." and Coupon_EndTime>=".time()." and Coupon_Condition<=".$total_price." and Biz_ID=".$Biz_ID);
			if($r){
				$Data["Coupon_ID"] = $CouponID;
				if($r["Coupon_UseType"]==0 && $r["Coupon_Discount"]>0 && $r["Coupon_Discount"]<1){
					$cash = $total_price * $r["Coupon_Discount"];
					$cash = number_format($cash, 2, '.', '');
					$Data["Coupon_Discount"] = $r["Coupon_Discount"];
				}
				if($r['Coupon_UseType'] == 1 && $r['Coupon_Cash'] > 0){
					$cash = $r['Coupon_Cash'];
				}
				$Data["Coupon_Cash"] = $cash;
				//$total_price = $total_price - $cash;
				$Logs_Price = $cash;
				
				if($r['Coupon_UsedTimes'] >= 1){
					$DB->Set("user_coupon_record","Coupon_UsedTimes=Coupon_UsedTimes-1","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Coupon_ID=".$_POST['CouponID'][$Biz_ID]);
				}
				$item = $DB->GetRs("user","User_Name","where User_ID=".$_SESSION[$UsersID."User_ID"]);
				$Data1 = array(
					'Users_ID'=>$UsersID,
					'User_ID'=>$_SESSION[$UsersID."User_ID"],
					'User_Name'=>$item["User_Name"],
					'Coupon_Subject'=>"优惠券编号：".$CouponID,
					'Logs_Price'=>$Logs_Price,
					'Coupon_UsedTimes'=>$r['Coupon_UsedTimes']>=1?$r['Coupon_UsedTimes']-1:$r['Coupon_UsedTimes'],
					'Logs_CreateTime'=>time(),
					"Biz_ID"=>$Biz_ID,
				
					'Operator_UserName' => "微商城购买商品"
				);
				$DB->Add("user_coupon_logs", $Data1);
			}else{
				$Data["Coupon_ID"] = 0;
				$Data["Coupon_Discount"] = 0;
				$Data["Coupon_Cash"] = 0;
			}
		}
		
	
		$Data["Order_TotalPrice"] = $total_price+(empty($_POST["Biz_Shipping_Fee"][$Biz_ID])?0:$_POST["Biz_Shipping_Fee"][$Biz_ID]);
		$pre_total += $Data["Order_TotalPrice"];
		$Data["Order_CreateTime"] = time();
		$Data["Order_Status"] = $rsConfig["CheckOrder"] == 1 ? 1 : 0;
		$Flag = $DB->Add("user_order", $Data);
		
		if($Flag){
			$neworderid = $DB->insert_id();
			$orderids[] = $neworderid;
			//更新销售记录
			foreach($BizCart as $kk=>$vv){
				$qty = 0;
				foreach($vv as $k=>$v){
					$qty += $v['Qty'];
					//加入分销记录
					if($v['OwnerID']>0){
						
						//add_distribute_record($UsersID,$DB,$v['OwnerID'],$v['ProductsPriceX'],$kk,$v['Qty'],$neworderid,$k);
						add_distribute_record($UsersID,$v['OwnerID'],$v['ProductsPriceX'],$kk,$v['Qty'],$neworderid,$v["ProductsProfit"],$k);
					}
				}
				//$condition ="where Users_ID='".$UsersID."' and Products_ID=".$kk;
				//$DB->set('shop_products','Products_Sales=Products_Sales+'.$qty.',Products_Count=Products_Count-'.$qty,$condition);
			}
		}else{
			$error = true;
		}
	}

	$_SESSION[$cart_key] = '';
	if($error){
		$Data=array(
			"status"=>0
		);
	}else{
		if($rsConfig["CheckOrder"]==1){
			$Data = array(
				"usersid"=>$UsersID,
				"userid"=>$_SESSION[$UsersID."User_ID"],
				"pre_sn"=>$pre_sn,
				"orderids"=>implode(",",$orderids),
				"total"=>$pre_total,
				"createtime"=>time(),
				"status"=>1
			);
			$flag = $DB->Add("user_pre_order",$Data);
			if($flag){
				$url = "/api/".$UsersID."/shop/cart/payment/".$pre_sn."/";
				$Data = array(
					"url" => $url,
					"status" => 1
				);
			}else{
				$Data = array(
					"msg" => "订单提交失败",
					"status" => 0
				);
			}
		}else{
			$url = "/api/".$UsersID."/shop/member/status/0/";
			$Data=array(
				"url" => $url,
				"status"=>1
			);
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);exit;
	
}elseif($action=="payment"){
	$OrderID = empty($_POST['OrderID']) ? 0 : $_POST['OrderID'];
	$orderids = '';
	if(strpos($OrderID,"PRE") !== false){
		$pre_order = $DB->GetRs("user_pre_order","*","where pre_sn='".$OrderID."' and usersid='".$UsersID."' and userid=".$_SESSION [$UsersID . 'User_ID']);
		$orderids = $pre_order["orderids"];
		$order_status = $pre_order["status"];
		$order_total = $pre_order["total"];
	}else{
		$orderids = $OrderID;
		$rsOrder = $DB->GetRs ( "user_order", "*", "where Users_ID='" . $UsersID . "' and Order_ID='" . $OrderID . "'" );
		$order_status = $rsOrder["Order_Status"];
		$order_total = $rsOrder["Order_TotalPrice"];
	}

	$rsUser = $DB->GetRs("user","User_Money,User_PayPassword,Is_Distribute,User_Name,User_NickName,Owner_Id,User_Integral","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
	
	$PaymentMethod = array(
		"微支付" => "1",
		"支付宝" => "2",
		"线下支付" => "3",
		"易宝支付" => "4",
	);

	if($_POST['PaymentMethod'] == "线下支付" || $order_total <= 0){
		$Data = array(
			"Order_PaymentMethod"=>$_POST['PaymentMethod'],
			"Order_PaymentInfo"=>$_POST["PaymentInfo"],
			//"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"],
			"Order_Status"=>1
		);
		
		$Status = 1;
		$flag = $DB->Set ( "user_order", $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION [$UsersID . "User_ID"] . "' and Order_ID in(".$orderids.")");
		$url = "/api/" . $UsersID . "/shop/member/status/" . $Status . "/";
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
		
	}elseif($_POST['PaymentMethod'] == "余额支付" && $rsUser["User_Money"] >= $order_total){//余额支付
		//增加资金流水
		if($order_status != 1){
			$Data=array(
				"status"=>0,
				"msg"=>'该订单状态不是待付款状态，不能付款'
			);
		}elseif(!$_POST["PayPassword"]){
			$Data = array(
				"status"=>0,
				"msg"=>'请输入支付密码'
			);
			
		}elseif(md5($_POST["PayPassword"]) != $rsUser["User_PayPassword"]){
			$Data = array(
				"status"=>0,
				"msg"=>'支付密码输入错误'
			);
		}else{
			$Data = array(
				'Users_ID' => $UsersID,
				'User_ID' => $_SESSION [$UsersID . 'User_ID'],
				'Type' => 0,
				'Amount' => $order_total,
				'Total' => $rsUser ['User_Money'] - $order_total,
				'Note' => "商城购买支出 -" . $order_total . " (订单号:" . $orderids . ")",
				'CreateTime' => time () 		
			);
			$Flag = $DB->Add('user_money_record', $Data);
			//更新用户余额
			$Data = array(				
				'User_Money'=>$rsUser['User_Money'] - $order_total				
			);
			$Flag = $DB->Set('user',$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
			
			$Data = array(
				"Order_PaymentMethod"=>$_POST['PaymentMethod'],
				"Order_PaymentInfo"=>"",
				"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"]	
			);
			
			$DB->Set ('user_order', $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION [$UsersID . "User_ID"] . "' and Order_ID in(".$orderids.")");
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
			$pay_order = new pay_order($DB, $OrderID);
			$Data = $pay_order->make_pay();
		}			
	} else {//在线支付
		$Data=array(
			"Order_PaymentMethod"=>$_POST['PaymentMethod'],
			"Order_PaymentInfo"=>"",
			"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"]
		);
		$Flag = $DB->Set ( "user_order", $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION [$UsersID . "User_ID"] . "' and Order_ID in(".$orderids.")");
		$url = "/api/" . $UsersID . "/shop/cart/pay/" . $OrderID . "/" . $PaymentMethod [$_POST ['PaymentMethod']] . "/";
		
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
}elseif($action == 'distribute_product'){
	
	//检测用户是否登陆
	if(empty($_SESSION[$UsersID."User_ID"])){
		$_SESSION[$UsersID."HTTP_REFERER"]='http://'.$_SERVER['HTTP_HOST']."/api/".$UsersID."/shop/products/".$_POST['productid'].'/?wxref=mp.weixin.qq.com';
		$url = 'http://'.$_SERVER['HTTP_HOST']."/api/".$UsersID."/user/login/";
		/*返回值*/
		$response=array(
			"status"=>0,
			"info"=>"您还为登陆，请登陆！",
			"url"=>$url
		);
		
	}else{
		
		$condition = "where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'";
		$rsUser = $DB->getRs("user","Is_Distribute",$condition);
		
		$response = array(
				"status"=>1,
				"Is_Distribute"=>$rsUser['Is_Distribute'],
			);	

	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);	
}else if($action == 'price'){

	$Products_ID = $_REQUEST["ProductsID"];
	$response    = array('err_msg' => '', 'result' => '', 'qty' => 1);
    $attr_id    = isset($_REQUEST['attr']) ? $_REQUEST['attr']: array();
    $qty     = (isset($_REQUEST['qty'])) ? intval($_REQUEST['qty']) : 1;
	$no_attr_price = $_REQUEST['no_attr_price'];
	$UsersID = $_REQUEST['UsersID'];
	
    if ($Products_ID == 0)
    {
        $response['msg'] = '无产品ID';
        $response['status']  = 0;
    }
    else
    {
        if ($qty == 0)
        {
            $response['qty'] = $qty = 1;
        }
        else
        {
            $response['qty'] = $qty;
        }
		
	
        $shop_price  = get_final_price($no_attr_price,$attr_id,$Products_ID,$UsersID);
        //查看用户是否登陆
		//获取登录用户的用户级别及其是否对应优惠价
		if(!empty($_SESSION[$UsersID.'User_ID'])){ 	
			$rsUser = $DB->GetRs("user","User_Level","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
			
			if($rsUser['User_Level'] >0 ){
				$rsUserConfig = $DB->GetRs("User_Config","UserLevel","where Users_ID='".$UsersID."'");
				$discount_list = json_decode($rsUserConfig["UserLevel"],TRUE);
				$discount =  $discount_list[$rsUser['User_Level']]['Discount'];
				$discount_price = $shop_price*(1-$discount/100);
				$discount_price = round($discount_price,2);
				$shop_price = $discount_price;
			}
		
		}
		$response['result'] = $shop_price;
    }
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);	
}else if($action == 'diyong'){
	
	//记录此订单消耗多少积分
	$Data = array('Integral_Consumption'=>$_POST['Integral_Consumption'],
				   'Integral_Money'=>$_POST['Integral_Money'],
				   'Order_TotalPrice'=>'Order_TotalPrice-'.$_POST['Integral_Money']);
	$condition = "where Users_ID = '".$UsersID."' and Order_ID=".$_POST['Order_ID'];
	
	mysql_query('start transaction');
	$Flag_a = $DB->Set('user_order',$Data,$condition,'Order_TotalPrice');
	//将积分移入不可用积分
	$Flag_b = add_userless_integral($UsersID,$_SESSION[$UsersID.'User_ID'],$_POST['Integral_Consumption']);
			  	
	
	if($Flag_a&&$Flag_b){
		mysql_query('commit');
		$response = array('status'=>1,'msg'=>'抵用消耗记录到订单成功');
	}else{
		mysql_query("ROLLBACK");
	    $response = array('status'=>0,'msg'=>'抵用消耗记录到订单失败');
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);	
	
}elseif($action == 'checkout_update'){ //提交订单页面跟新购物车（所有类型）
	$cart_key = $UsersID.$_POST['cart_key'];
	$k = explode("_", $_POST["_CartID"]);
    $BizID = array_shift($k);
	$ProductsID = array_shift($k);
	$CartID = array_shift($k);
	if(empty($_SESSION[$cart_key])){
		$Data=array(
			"status"=>0,
			"msg"=>"购物车空的，赶快去逛逛吧！"
		);
	    echo json_encode($Data, JSON_UNESCAPED_UNICODE);exit;
	}
	$CartList = json_decode($_SESSION[$cart_key], true);
	$CartList[$BizID][$ProductsID][$CartID]["Qty"] = $_POST["_Qty"];
	$_SESSION[$cart_key] = json_encode($CartList, JSON_UNESCAPED_UNICODE);//重新生成购物车end
	

	//计算所需运费
	$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
	$Shipping_IDs = isset($_POST['Shipping_ID']) ? organize_shipping_id($_POST['Shipping_ID']) : array();
	$City_Code = isset($_POST['City_Code']) ? $_POST['City_Code'] : 0;
	if($cart_key == $UsersID . 'Virtual'){
		$total_info['total'] = $total_info['total_shipping_fee'] = 0;
		foreach($CartList as $Biz_ID => $Biz_Cart){
			foreach ($Biz_Cart as $Products_ID=>$Products_List) {
				foreach ($Products_List as $k=>$v) {
					$total_info['total'] += $v["Qty"] * $v["ProductsPriceX"];
				}
			}
		}
	}else{
		$total_info = get_order_total_info($UsersID, $CartList, $rsConfig, $Shipping_IDs, $City_Code);
	}//运费计算结束
	
	
	//积分计算
	if(!empty($rsConfig['Integral_Convert'])){
		$integral = ($total_info['total'] + $total_info['total_shipping_fee']) / abs($rsConfig['Integral_Convert']);
	}else{
		$integral = 0;
	}
	
	$reduce = !empty($total_info['reduce']) ? $total_info['reduce'] : 0;
	
	/*获取可用优惠券信息*/
	//获取优惠券
	
	$price = $CartList[$BizID][$ProductsID][$CartID]["ProductsPriceX"];
	$Data = array(
		"status" => 1,
		"Sub_Total" => $price * $_POST["_Qty"],
		"Sub_Qty" => $_POST["_Qty"],
		"price" => $price,
		"biz_shipping_name" => isset($total_info [$BizID] ['Shipping_Name']) ? $total_info [$BizID] ['Shipping_Name'] : '',
		"biz_shipping_fee" => isset($total_info [$BizID] ['total_shipping_fee']) ? $total_info [$BizID] ['total_shipping_fee'] : 0,
		"total_shipping_fee" => $total_info ['total_shipping_fee'],
		"total" => $total_info['total'],
		"man_flag" => 0,
		"reduce" => $reduce,
		"integral" => $integral,
	);
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);exit;
}elseif($action == 'change_shipping_method'){
	
	$rsConfig = $DB->GetRs ( "shop_config", "*", "where Users_ID='" . $UsersID . "'" );
	$Shipping_IDS = isset ( $_POST ['Shipping_ID'] ) ? organize_shipping_id($_POST ['Shipping_ID']) : array ();
	$City_Code = $_POST ['City_Code'];
	$Biz_ID = isset($_POST ['Biz_ID'])? $_POST ['Biz_ID']:0;
	$cart_key = $UsersID.$_POST['cart_key'];
	$CartList = json_decode ( $_SESSION [$cart_key], true );
	
	$total_info = get_order_total_info ( $UsersID, $CartList, $rsConfig, $Shipping_IDS, $City_Code );
	
	
	$Data = array();
	if($Biz_ID != 0){
		$Data["biz_shipping_name"] = $total_info[$Biz_ID]['Shipping_Name'];
		$Data["biz_shipping_fee"] = $total_info[$Biz_ID]['total_shipping_fee'];
	}
	$Data["status"] = 1;
	$Data["total_shipping_fee"] = $total_info['total_shipping_fee'];
	$Data["total"] = $total_info ['total'] ;
	$Data["stauts"] = 1;
	$Data["total_shipping_fee"] = $total_info['total_shipping_fee'];
	
	echo json_encode ( $Data, JSON_UNESCAPED_UNICODE );
}

function get_final_price($cur_price,$attr_id,$ProductID,$UsersID){
	
	global $DB1;
	
    $_SESSION[$UsersID.'attr_id'] = $attr_id;
	
	$attr_id_str = implode(',',$attr_id);
	
	$condition = "where  Products_ID =".$ProductID;
	$condition .= " And Product_Attr_ID in (".$attr_id_str.")";
   
	$rsProductAttrs = $DB1->Get("shop_products_attr","*",$condition);
	$product_attrs = $DB1->toArray($rsProductAttrs); 
    
	if(!empty($product_attrs)){
		foreach($product_attrs as $key=>$item){
			$cur_price += $item['Attr_Price']; 
		}
	}
	
	return $cur_price;
	
}
function organize_shipping_id($shipping_id) {
	$Shipping_IDS = array ();
	foreach ( $shipping_id as $key => $item ) {
		$Shipping_IDS [$item ['Biz_ID']] = $item ['Shipping_ID'];
	}
	
	return $Shipping_IDS;
}
function build_pre_order_no(){
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}
?>