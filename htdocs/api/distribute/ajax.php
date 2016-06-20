<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

$base_url = base_url();

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo 'error';
	exit;
}


$action=empty($_REQUEST["action"])?"":$_REQUEST["action"];

if($action == 'withdraw_appy'){
	//商城配置信息
	$rsConfig = shop_config($UsersID);
	//分销相关设置
	$dis_config = dis_config($UsersID);
	//合并参数
	$rsConfig = array_merge($rsConfig,$dis_config);
	
	$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'";
	$dsAccount = $DB->getRs('distribute_account',"balance",$condition);
	$moneys = $_POST['money'];
	$feifas = is_shuz($moneys);	
	if($feifas != 1){
	if($moneys >$dsAccount['balance']){
		$response = array('status'=>0,'msg'=>'余额不足');
	}else{
		
		$condition = "where User_Method_ID=".$_POST['User_Method_ID']." and Users_ID='".$UsersID."'";
		$UserMethod = $DB->GetRs('distribute_withdraw_methods',"*",$condition);

		//转入余额
		if($UserMethod["Method_Type"]=='yue_income'){
			//增加资金流水
			$amount = $moneys;
			/*$rsUser = $DB->GetRs("user", "User_Money", "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);

			$Data=array(
				'Users_ID'=>$UsersID,
				'User_ID'=>$_SESSION[$UsersID.'User_ID'],				
				'Type'=>1,
				'Amount'=>$amount,
				'Total'=>$rsUser['User_Money']+$amount,
				'Note'=>"佣金提现转入余额 +".$amount,
				'CreateTime'=>time()		
			);

			$Flag=$DB->Add('user_money_record',$Data);
			//更新用户余额
			$Data=array(				
				'User_Money'=>$rsUser['User_Money']+$amount					
			);		
			$Set = $DB->Set("user",$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
			$Flag = $Flag && $Set;
			*/
			$data = array(
				"Users_ID"=>$UsersID,
				"User_ID"=>$_SESSION[$UsersID.'User_ID'],
				"Record_Total"=>$amount,
				"Record_CreateTime"=>time(),
				"Method_Name" => '余额提现',
				//"Record_Status"=>1,
				"Record_Status"=>0,
				"Record_Yue" => $amount,
				"Record_Fee" => 0,
				"Record_Money" => $amount,
			);
			$Add = $DB->add('distribute_withdraw_record',$data);


			//$Flag = $Flag && $Add;
			$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'";
			$Data = array(
				"balance"=>$dsAccount["balance"]-$amount
			);
			$Set = $DB->Set('distribute_account',$Data,$condition);
			//$Flag = $Flag && $Set;
			$response = array('status'=>1,'msg'=>'提现申请成功,请等待审核');
		}else{//提现转帐
			$Flag = true;
			$total_money  = $moneys;
			$fee = 0;
			if($rsConfig["Poundage_Ratio"]>0){
				$fee = $total_money * $rsConfig["Poundage_Ratio"] * 0.01;
			}
			$money  = $total_money * (1 - ($rsConfig['Balance_Ratio']/100));//佣金
			if($money<$fee){
				$response = array('status'=>0,'msg'=>'提现金额中的转帐部分小于手续费');
				echo json_encode($response,JSON_UNESCAPED_UNICODE);
				exit;
			}
			$money = $money - $fee;
			$Ratio_money = $total_money * $rsConfig['Balance_Ratio']/100;//余额
			
			//获取用户提现方式
			
			
			if($UserMethod["Method_Type"]=='wx_hongbao' && ($money<1 || $money>200)){
				$response = array('status'=>0,'msg'=>'提现金额在1-200之间才可使用微信红包提现');
			}elseif($UserMethod["Method_Type"]=='wx_zhuanzhang' && $money<0){
				$response = array('status'=>0,'msg'=>'提现金额必须大于零才可使用微信红包提现');
			}else{
				$Account_Info = $UserMethod['Method_Name'].' '.$UserMethod['Account_Name'].' '.$UserMethod['Account_Val'].' '.$UserMethod['Bank_Position'];

				$data = array(
					"Users_ID"=>$UsersID,
					"User_ID"=>$_SESSION[$UsersID.'User_ID'],
					"Record_Total"=>$total_money,
					"Record_CreateTime"=>time(),
					"Record_Status"=>0,
					"Record_Yue" => $Ratio_money,
					"Record_Fee" => $fee,
					"Record_Money" => $total_money - ($Ratio_money + $fee),
					"Method_Name" => $UserMethod['Method_Name'],
					"Method_Account" => $UserMethod['Account_Name'],
					"Method_No" => $UserMethod['Account_Val'],
					"Method_Bank" => $UserMethod['Bank_Position']
				);
				$Add = $DB->add('distribute_withdraw_record',$data);
				$Flag = $Flag && $Add;
				$recordid = $DB->insert_id();
				$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'";			
				$Set = $DB->set('distribute_account',"balance=balance-$total_money",$condition);
				$Flag = $Flag && $Set;
				if($Ratio_money>0){
					$rsUser = $DB->GetRs("user", "User_Money", "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
					$Ratio_Data = array(
						'Users_ID' => $UsersID,
						'User_ID' => $_SESSION[$UsersID.'User_ID'],
						'Type'=>1,
						'Amount' => $Ratio_money,
						'Total' => $rsUser['User_Money']+$Ratio_money,
						'Note' => "佣金提现 +".$Ratio_money,
						'CreateTime' => time()            
					);
					$Flag = $Flag && $DB->Add('user_money_record', $Ratio_Data);
					$Data = array(                
						'User_Money'=>$rsUser['User_Money']+$Ratio_money                 
					);
					$Flag = $Flag && $DB->Set('user',$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
				}
				if($Flag){
					if($UserMethod["Method_Type"]=='wx_hongbao' || $UserMethod["Method_Type"]=='wx_zhuanzhang'){
						require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
						$pay_order = new pay_order($DB, 0);
						$Data = $pay_order->withdraw($UsersID,$_SESSION[$UsersID.'User_ID'],$recordid,$UserMethod["Method_Type"]);
						if($Data['status']==1){
							if($UserMethod["Method_Type"]=='wx_hongbao'){
								$response = array('status'=>1,'msg'=>'提现成功，请查看微信红包');
							}else{
								$response = array('status'=>1,'msg'=>'提现成功，请查看微信转账');
							}
						}else{
							$response = array('status'=>0,'msg'=>$Data['msg']);
						}
					}else{
						$response = array('status'=>1,'msg'=>'提现申请提交成功,预计2个工作日内申请通过');
					}
				}else{
					$response = array('status'=>0,'msg'=>'发生位置错误，申请提交失败');
				}
			}
		}
	}
	}else{
		$response = array('status'=>0,'msg'=>'非法操作');
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);
}elseif($action == 'removeApplyOrder'){
	$Order_ID = intval($_GET['orderId']);
	$orderstatus = $DB->GetRs('agent_order', 'count(*) as num', ' WHERE `Users_ID`="'.$_GET['UsersID'].'" AND `User_ID` = "' .$_SESSION[$UsersID.'User_ID']. '" AND `Order_ID` = "'.$Order_ID.'"');
	
	if ($orderstatus['num'] = 0) {
		echo json_encode(array('status' => 0, 'msg' => '不存在该信息!'), JSON_UNESCAPED_UNICODE); exit();
	}

	$flag = $DB->Set('agent_order', array('Order_Status' => 3), ' where  `Users_ID`="'.$_GET['UsersID'].'" AND `User_ID` = "' .$_SESSION[$UsersID.'User_ID']. '" AND `Order_ID` = "'.$Order_ID.'"');
	if ($flag) {
		echo json_encode(array('status' => 1, 'msg' => '取消成功!'), JSON_UNESCAPED_UNICODE); exit();
	} else {
		echo json_encode(array('status' => 0, 'msg' => '取消失败!'), JSON_UNESCAPED_UNICODE); exit();
	}

}elseif($action == 'checkCityApply'){

	$ajaxCondition = 'WHERE `status` = 1 ';

	if (!empty($_GET['AreaId'])) 
	{
		$ajaxCondition .= ' AND `area_id`= '.intval($_GET['AreaId']);
	}elseif (!empty($_GET['CityId'])) {
		$ajaxCondition .= ' AND `area_id`= '.intval($_GET['CityId']);
	}elseif (!empty($_GET['ProvinceId'])) {
		$ajaxCondition .= ' AND `area_id`= '.intval($_GET['ProvinceId']);
	}

	$s = $DB->GetRs('distribute_agent_areas', 'count(id) AS num', $ajaxCondition);
	if ($s['num'] > 0) 
	{
		echo json_encode(array('status' => 1, 'msg' => '该区域已被申请'), JSON_UNESCAPED_UNICODE);
	} else {
		echo json_encode(array('status' => 0, 'msg' => '该区域可以申请'), JSON_UNESCAPED_UNICODE);
	}
	exit();

}elseif($action == 'removeShaOrder'){
	$Order_ID = intval($_GET['orderId']);
	$orderstatus = $DB->GetRs('sha_order', 'count(*) as num', ' WHERE `Users_ID`="'.$_GET['UsersID'].'" AND `User_ID` = "' .$_SESSION[$UsersID.'User_ID']. '" AND `Order_ID` = "'.$Order_ID.'"');
	
	if ($orderstatus['num'] = 0) {
		echo json_encode(array('status' => 0, 'msg' => '不存在该信息!'), JSON_UNESCAPED_UNICODE); exit();
	}

	$flag = $DB->Set('sha_order', array('Order_Status' => 3), ' where  `Users_ID`="'.$_GET['UsersID'].'" AND `User_ID` = "' .$_SESSION[$UsersID.'User_ID']. '" AND `Order_ID` = "'.$Order_ID.'"');
	if ($flag) {
		echo json_encode(array('status' => 1, 'msg' => '取消成功!'), JSON_UNESCAPED_UNICODE); exit();
	} else {
		echo json_encode(array('status' => 0, 'msg' => '取消失败!'), JSON_UNESCAPED_UNICODE); exit();
	}
}elseif($action == 'check_exist'){
	$field = $_GET['field'];
	if($field == 'Real_Name'){
		$value = $_GET['real_name'];
	}elseif($field == 'ID_Card'){
		$value = $_GET['idcard'];
	}elseif($field == 'Email'){
		$value = $_GET['email'];
	}
	$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and ".$field."='".$value."'";
	
	$rsUser = $DB->getRs("distribute_account","*",$condition);
	
	if(!empty($rsUser)){
		echo 'false';
	}else{
		echo 'true';
	}
}elseif($action == "add_user_withdraw_method"){

	
	$data = array();
	$data['Users_ID'] = $UsersID;
	$data['User_ID'] = $_SESSION[$UsersID.'User_ID'];
	$data['Method_Name'] = $_POST['Method_Name'];
	$data['Method_Type'] = $_POST['Method_Type'];
	$data['Account_Name'] = $_POST['Account_Name'];
    $data['Account_Val'] = !empty($_POST['Account_Val'])?$_POST['Account_Val']:'';
	$data['Bank_Position'] = !empty($_POST['Bank_Position'])?$_POST['Bank_Position']:'';
	$data['Method_CreateTime'] = time();
	$data['Method_Status'] = 1;		
	
	$Flag = $DB->add('distribute_withdraw_methods',$data);
	
	if($Flag){
		$response = array('status'=>1,'msg'=>'添加新的提现方式成功');
	}else{
		$response = array('status'=>0,'msg'=>'添加新的提现方式失败');
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	
}elseif($action == "delete_user_withdraw_method"){
	$method_id = $_POST['method_id'];
	
	$condition = "User_Method_ID=".$method_id." and Users_ID='".$UsersID."'";
	$Flag = $DB->Del('distribute_withdraw_methods',$condition);
	
	if($Flag){
		$response = array('status'=>1);
	}else{
		$response = array('status'=>0);
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);

}elseif($action == 'store_poster'){
	
 	$img = $_POST['dataUrl'];
 	$owner_id = $_POST['owner_id'];
	$file_path = '/data/poster/'.$UsersID.$owner_id.'.png';
	$web_path = '/data/poster/'.$UsersID.$owner_id.'.png';
	 
	$Flag = generate_postere($img,$UsersID,$owner_id);
	
	if($Flag){
		
		$condition = "Where Users_ID = '".$UsersID."' and User_ID=".$owner_id;	
		$DB->Set('distribute_account',array('Is_Regeposter'=>0),$condition);
		$response = array('status'=>1,'poster_path'=>$web_path);
		
	}else{
		$response = array('status'=>0,'msg'=>'Unable to save the file.');
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	
}elseif($action == 'get_ex_bonus'){
   //检测此账号是否有额外奖金
   $condition = "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID'];
   $rsDistirbuteAccount = $DB->getRs('distribute_account','balance',$condition);
   $rsDistirbuteRecord = $DB->getRs('distribute_account_record','SUM(Nobi_Money) as money',$condition." and Nobi_Money>0 and Record_Status=2 and Nobi_Status=0");
   $rsDistirbuteRecord["money"] = empty($rsDistirbuteRecord["money"]) ? 0 : $rsDistirbuteRecord["money"];
   if($rsDistirbuteRecord["money"] == 0){
   		$response = array('status'=>0,'msg'=>'分销额外奖金为零');
   }else{
   		 		
   		$data = array('Ex_Bonus'=>0,'balance'=>$rsDistirbuteAccount['balance']+$rsDistirbuteRecord["money"]);
   					  
   		$Flag = $DB->Set('distribute_account',$data,$condition);
		$DB->Set('distribute_account_record',array("Nobi_Status"=>1),"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']." and Nobi_Money>0 and Record_Status=2 and Nobi_Status=0");
   		$response = array('status'=>1, 'msg'=>'获取奖金成功');
   }

   echo json_encode($response,JSON_UNESCAPED_UNICODE);

}elseif( $action== 'check_mobile_exist'){
	
	$User_Mobile = $request->input('User_Mobile');
    
	$user = User::multiWhere(array('Users_ID'=>$UsersID,'User_Mobile'=>$User_Mobile))
	             ->get()
				 ->first();
	if(!empty($user)){
		echo 'false';
	}else{
		echo 'true';
	}
	
}elseif($action == 'send_short_msg'){

      //发送短信功能未开启
      if($setting["sms_enabled"]==0){
			$Data = array(
				"status"=> 0,
				"msg"=>"短信发送功能已关闭"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}

		$mobile = trim($request->input('mobile'));
       
		if(!$mobile){
			$Data = array(
				"status"=> 0,
				"msg"=>"请输入手机号码"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}

		
		if(!isset($_SESSION[$UsersID.'mobile_send']) || empty($_SESSION[$UsersID.'mobile_send'])){
			$_SESSION[$UsersID.'mobile_send'] = 0;
		}
		
		
		if(isset($_SESSION[$UsersID.'mobile_time']) && !empty($_SESSION[$UsersID.'mobile_time'])){
			if(time() - $_SESSION[$UsersID.'mobile_time'] < 300){
				$Data = array(
					"status"=> 0,
					"msg"=>"发送短信过快"
				);
				echo json_encode($Data,JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		
		
		
		if($_SESSION[$UsersID.'mobile_send'] > 4){
			$Data = array(
				"status"=> 0,
				"msg"=>"发送短信次数频繁"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}		
	
		$code = randcode(6);		
		
		$message = $SiteName."注册验证码：".$code."。此验证码十分钟有效。";
		require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
	
		$result = send_sms($mobile,$message,$UsersID);
		
		if($result==1){
			$_SESSION[$UsersID.'mobile'] = $mobile;
			$_SESSION[$UsersID.'sms_code'] = $code;
			$_SESSION[$UsersID.'mobile_code'] = md5($mobile.'|'.$code);
			$_SESSION[$UsersID.'mobile_time'] = time();
			$_SESSION[$UsersID.'mobile_send'] = $_SESSION[$UsersID.'mobile_send'] + 1;
			
			$Data = array(
				"status"=> 1,
				"msg"=>"发送成功，验证码四分钟有效。"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 0,
				"msg"=>"发送失败"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
}elseif($action == 'get_level_products'){
	$LevelID = $_POST['LevelID'];
	$rsLevel = $DB->GetRs('distribute_level','*','where Users_ID="'.$UsersID.'" and Level_ID='.$LevelID);
	if(!$rsLevel){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($rsLevel['Level_LimitType']<>2){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	$arr = explode('|',$rsLevel['Level_LimitValue']);
	if($arr[0]==0){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($arr[1])){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别条件未设置商品'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$html = '';
	$DB->Get('shop_products','Products_ID,Products_Name,Products_Json,Products_PriceY,Products_PriceX','where Users_ID="'.$UsersID.'" and Products_ID in('.$arr[1].')');
	$i = 0;
	while($r = $DB->fetch_assoc()){
		$Json = json_decode($r['Products_Json'],true);
		$html .= '<div class="items"><ul><li class="img"><a href="'.shop_url().'products/'.$r['Products_ID'].'/"><img src="'.(empty($Json["ImgPath"][0]) ? '' : $Json["ImgPath"][0]).'" /></a></li><li class="price">&yen;'.$r['Products_PriceX'].'<span>&yen;'.$r['Products_PriceY'].'</span></li><li class="name"><a href="'.shop_url().'products/'.$r['Products_ID'].'/">'.$r['Products_Name'].'</a></li></li></ul></div>';
		if($i%2==1){
			$html .= '<div class="clear"></div>';
		}
		$i++;
	}
	
	$Data = array(
		"status"=> 1,
		"msg"=>$html
	);
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}elseif($action == 'get_level_price'){
	$LevelID = $_POST['LevelID'];
	$rsLevel = $DB->GetRs('distribute_level','*','where Users_ID="'.$UsersID.'" and Level_ID='.$LevelID);
	if(!$rsLevel){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($rsLevel['Level_LimitType']<>0){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($rsLevel['Level_LimitValue'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$Data = array(
		"status"=> 1,
		"price"=>number_format($rsLevel['Level_LimitValue'],2,'.','')
	);
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}elseif($action == 'deal_level_order'){
	$LevelID = $_POST['LevelID'];
	$rsLevel = $DB->GetRs('distribute_level','*','where Users_ID="'.$UsersID.'" and Level_ID='.$LevelID);
	if(!$rsLevel){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($rsLevel['Level_LimitType']<>0){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($rsLevel['Level_LimitValue'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$User_ID = $_SESSION[$UsersID.'User_ID'];
	$rsUser = $DB->GetRs('user','Owner_Id','where User_ID='.$User_ID);
	
	$Data = array(
		'Users_ID'=> $UsersID,
		'User_ID'=>$User_ID,
		'Address_Name'=>$_POST['Name'],
		'Address_Mobile'=>$_POST['Mobile'],	
		'Address_Detail'=>$_POST['Detail'],	
		'Address_WeixinID'=>$_POST['WeixinID'],	
		'Order_TotalPrice'=>number_format($rsLevel['Level_LimitValue'],2,'.',''),
		'Owner_ID'=>$rsUser['Owner_Id'],
		'Level_ID'=>$LevelID,
		'Level_Name'=>$rsLevel['Level_Name'],
		'Order_CreateTime'=>time(),
		'Order_Status'=>1
	);
	$Flag = $DB->Add('distribute_order',$Data);
	$order_id = $DB->insert_id();
	if($Flag){
		$Data = array(
			"status"=> 1,
			'orderid'=>$order_id,
			'money'=>number_format($rsLevel['Level_LimitValue'],2,'.','')
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		$Data = array(
			"status"=> 0,
			"msg"=>'提交订单失败'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
}elseif($action == 'deal_level_order_pay'){
	if(empty($_POST['OrderID'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($_POST['password'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$OrderID = $_POST['OrderID'];
	$rsOrder = $DB->GetRs('distribute_order','*','where Users_ID="'.$UsersID.'" and Order_ID='.$OrderID.' and User_ID='.$_SESSION[$UsersID.'User_ID']);
	
	if(!$rsOrder){
		$Data = array(
			"status"=> 0,
			"msg"=>'订单不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsOrder['Order_Status']<>1){
		$Data = array(
			"status"=> 0,
			"msg"=>'该订单不是待付款状态'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$rsUser = $DB->GetRs('user','User_Money,User_PayPassword','where User_ID='.$rsOrder['User_ID']);	
	if($rsUser['User_PayPassword'] != md5($_POST['password'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'支付密码错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsUser['User_Money'] < $rsOrder['Order_TotalPrice']){
		$Data = array(
			"status"=> 0,
			"msg"=>'当前余额不足'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
	$pay_order = new pay_order($DB, $OrderID);
	$response = $pay_order->deal_distribute_order();
	
	if($response['status']){
		$Data = array(
			"status"=> 1,
			'msg'=>'支付成功，您已成功成为'.$rsOrder['Level_Name'],
			'url'=>distribute_url()
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		$Data = array(
			"status"=> 0,
			"msg"=>'支付失败'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
}elseif($action == 'get_distribute_pay_method'){
	if(empty($_POST['OrderID'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($_POST['method'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$method = $_POST['method'];
	
	$OrderID = $_POST['OrderID'];
	$rsOrder = $DB->GetRs('distribute_order','*','where Users_ID="'.$UsersID.'" and Order_ID='.$OrderID.' and User_ID='.$_SESSION[$UsersID.'User_ID']);
	
	if(!$rsOrder){
		$Data = array(
			"status"=> 0,
			"msg"=>'订单不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsOrder['Order_Status']<>1){
		$Data = array(
			"status"=> 0,
			"msg"=>'该订单不是待付款状态'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
	
	if($method==1){//微支付
		$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
		if($rsPay["PaymentWxpayEnabled"]==0 || empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
			$Data = array(
				"status"=> 0,
				"msg"=>'商家“微支付”支付方式未启用或信息不全，暂不能支付！'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if($rsPay["PaymentWxpayType"]==1){
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/wxpay2/sendto_distribute.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/wxpay/sendto_distribute.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
	}elseif($method==2){//支付宝		
		if($rsPay["Payment_AlipayEnabled"]==0 || empty($rsPay["Payment_AlipayPartner"]) || empty($rsPay["Payment_AlipayKey"]) || empty($rsPay["Payment_AlipayAccount"])){
			$Data = array(
				"status"=> 0,
				"msg"=>'商家“支付宝”支付方式未启用或信息不全，暂不能支付！'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/alipay/sendto_distribute.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
}elseif($action=='get_level_info_upgrade'){//获取升级条件
	$LevelID = $_POST['LevelID'];
	$rsLevel = $DB->GetRs('distribute_level','*','where Users_ID="'.$UsersID.'" and Level_ID='.$LevelID);
	if(!$rsLevel){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$rsAccount = $DB->GetRs('distribute_account','Level_ID','where User_ID='.$_SESSION[$UsersID.'User_ID']);
	$my_level = $rsAccount['Level_ID'];
	
	if($my_level>=$LevelID){
		$Data = array(
			"status"=> 0,
			"msg"=>'您的级别必须低于要升级的级别'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$money = 0;
	if($rsLevel['Level_UpdateType']==0){//补差价
		$DB->Get('distribute_level','Level_Name,Level_UpdateType,Level_UpdateValue','where Users_ID="'.$UsersID.'" and Level_ID>'.$my_level.' and Level_ID<'.$LevelID.' order by Level_ID asc');
		while($r = $DB->fetch_assoc()){
			if($r["Level_UpdateType"]==1){
				$Data = array(
					"status"=> 0,
					"msg"=>$r['Level_Name'].'的升级条件是购买指定商品，请先升级至'.$r['Level_Name']
				);
				echo json_encode($Data,JSON_UNESCAPED_UNICODE);
				exit;
			}
			
			$money += $r['Level_UpdateValue'];
		}
		
		$money = $money + $rsLevel['Level_UpdateValue'];
		$Data = array(
			"status"=> 1,
			"price"=>$money
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{//购买指定商品
		$html = '';
		$DB->Get('shop_products','Products_ID,Products_Name,Products_Json,Products_PriceY,Products_PriceX','where Users_ID="'.$UsersID.'" and Products_ID in('.$rsLevel['Level_UpdateValue'].')');
		$i = 0;
		while($r = $DB->fetch_assoc()){
			$Json = json_decode($r['Products_Json'],true);
			$html .= '<div class="items"><ul><li class="img"><a href="'.shop_url().'products/'.$r['Products_ID'].'/"><img src="'.(empty($Json["ImgPath"][0]) ? '' : $Json["ImgPath"][0]).'" /></a></li><li class="price">&yen;'.$r['Products_PriceX'].'<span>&yen;'.$r['Products_PriceY'].'</span></li><li class="name"><a href="'.shop_url().'products/'.$r['Products_ID'].'/">'.$r['Products_Name'].'</a></li></li></ul></div>';
			if($i%2==1){
				$html .= '<div class="clear"></div>';
			}
			$i++;
		}
		
		$Data = array(
			"status"=> 2,
			"msg"=>$html
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}elseif($action == 'upgrade_level_order'){
	$LevelID = $_POST['LevelID'];
	$rsLevel = $DB->GetRs('distribute_level','*','where Users_ID="'.$UsersID.'" and Level_ID='.$LevelID);
	if(!$rsLevel){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($rsLevel['Level_UpdateType']<>0){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别升级条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($rsLevel['Level_UpdateValue'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'该级别升级条件已发生改变'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$rsAccount = $DB->GetRs('distribute_account','Level_ID','where User_ID='.$_SESSION[$UsersID.'User_ID']);
	$my_level = $rsAccount['Level_ID'];
	
	if($my_level>=$LevelID){
		$Data = array(
			"status"=> 0,
			"msg"=>'您的级别必须低于要升级的级别'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	//佣金设置明细
	$distributes = array();
	$dis_config = dis_config($UsersID);
	for($i=1;$i<=$dis_config['Dis_Level'];$i++){
		$distributes[$i] = 0;
	}
	
	$money = 0;
	
	$DB->Get('distribute_level','Level_Name,Level_UpdateType,Level_UpdateValue,Level_UpdateDistributes','where Users_ID="'.$UsersID.'" and Level_ID>'.$my_level.' and Level_ID<'.$LevelID.' order by Level_ID asc');
	while($r = $DB->fetch_assoc()){
		if($r["Level_UpdateType"]==1){
			$Data = array(
				"status"=> 0,
				"msg"=>$r['Level_Name'].'的升级条件是购买指定商品，请先升级至'.$r['Level_Name']
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		$Upgrade = json_decode($r['Level_UpdateDistributes'], true);
		foreach($distributes as $key=>$value){
			if(empty($Upgrade[$key])){
				continue;
			}
			$distributes[$key] = $distributes[$key] + $Upgrade[$key];
		}
			
		$money += $r['Level_UpdateValue'];
	}
		
	$Upgrade = json_decode($rsLevel['Level_UpdateDistributes'], true);
	foreach($distributes as $key=>$value){
		if(empty($Upgrade[$key])){
			continue;
		}
		$distributes[$key] = $distributes[$key] + $Upgrade[$key];
	}
	
	$money = $money + $rsLevel['Level_UpdateValue'];
	
	$User_ID = $_SESSION[$UsersID.'User_ID'];
	//$rsUser = $DB->GetRs('user','Owner_Id','where User_ID='.$User_ID);
	
	$Data = array(
		'Users_ID'=> $UsersID,
		'User_ID'=>$User_ID,
		'Address_Name'=>'',
		'Address_Mobile'=>'',	
		'Address_Detail'=>'',	
		'Address_WeixinID'=>'',	
		'Order_TotalPrice'=>number_format($money,2,'.',''),
		'Owner_ID'=>$User_ID,
		'Level_ID'=>$LevelID,
		'Level_Name'=>$rsLevel['Level_Name'],
		'Order_CreateTime'=>time(),
		'Order_Type'=>1,
		'Order_Status'=>1,
		'UpgradeDistributes'=>json_encode($distributes,JSON_UNESCAPED_UNICODE)
	);
	$Flag = $DB->Add('distribute_order',$Data);
	$order_id = $DB->insert_id();
	if($Flag){
		$Data = array(
			"status"=> 1,
			'orderid'=>$order_id,
			'money'=>number_format($money,2,'.','')
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		$Data = array(
			"status"=> 0,
			"msg"=>'提交订单失败'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}elseif($action == "deal_proxy_order_pay"){

	if(empty($_POST['OrderID'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($_POST['password'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$OrderID = $_POST['OrderID'];
	$rsOrder = $DB->GetRs('agent_order','*','where Users_ID="'.$UsersID.'" and Order_ID='.$OrderID.' and User_ID='.$_SESSION[$UsersID.'User_ID']);
	
	if(!$rsOrder){
		$Data = array(
			"status"=> 0,
			"msg"=>'订单不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsOrder['Order_Status']<>1){
		$Data = array(
			"status"=> 0,
			"msg"=>'该订单不是待付款状态'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$rsUser = $DB->GetRs('user','User_Money,User_PayPassword','where User_ID='.$rsOrder['User_ID']);

	if($rsUser['User_PayPassword'] != md5($_POST['password'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'支付密码错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsUser['User_Money'] < $rsOrder['Order_TotalPrice']){
		$Data = array(
			"status"=> 0,
			"msg"=>'当前余额不足'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
	$pay_order = new pay_order($DB, $OrderID);
	$response = $pay_order->deal_proxy_order();
	
	if($response['status']){
		$Data = array(
			"status"=> 1,
			'msg'=>'支付成功，你已经成为'.$rsOrder['AreaMark'],
			'url'=>distribute_url()
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		$Data = array(
			"status"=> 0,
			"msg"=>'支付失败'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}

}elseif($action == 'get_proxy_pay_method'){
	if(empty($_POST['OrderID'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($_POST['method'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$method = $_POST['method'];
	
	$OrderID = $_POST['OrderID'];
	$rsOrder = $DB->GetRs('agent_order','*','where Users_ID="'.$UsersID.'" and Order_ID='.$OrderID.' and User_ID='.$_SESSION[$UsersID.'User_ID']);
	
	if(!$rsOrder){
		$Data = array(
			"status"=> 0,
			"msg"=>'订单不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsOrder['Order_Status']<>1){
		$Data = array(
			"status"=> 0,
			"msg"=>'该订单不是待付款状态'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
	
	if($method==1){//微支付
		$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
		if($rsPay["PaymentWxpayEnabled"]==0 || empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
			$Data = array(
				"status"=> 0,
				"msg"=>'商家“微支付”支付方式未启用或信息不全，暂不能支付！'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if($rsPay["PaymentWxpayType"]==1){
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/wxpay2/sendto_proxy.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/wxpay2/sendto_proxy.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
	}elseif($method==2){//支付宝		
		if($rsPay["Payment_AlipayEnabled"]==0 || empty($rsPay["Payment_AlipayPartner"]) || empty($rsPay["Payment_AlipayKey"]) || empty($rsPay["Payment_AlipayAccount"])){
			$Data = array(
				"status"=> 0,
				"msg"=>'商家“支付宝”支付方式未启用或信息不全，暂不能支付！'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/alipay/sendto_proxy.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
}elseif($action == "deal_sha_order_pay"){

	if(empty($_POST['OrderID'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($_POST['password'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$OrderID = $_POST['OrderID'];
	$rsOrder = $DB->GetRs('sha_order','*','where Users_ID="'.$UsersID.'" and Order_ID='.$OrderID.' and User_ID='.$_SESSION[$UsersID.'User_ID']);
	if(!$rsOrder){
		$Data = array(
			"status"=> 0,
			"msg"=>'订单不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsOrder['Order_Status']<>1){
		$Data = array(
			"status"=> 0,
			"msg"=>'该订单不是待付款状态'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
        //订单股东级别
        $sha_level =$rsOrder['Applyfor_level'];
        if(empty($sha_level)){
		$Data = array(
			"status"=> 0,
			"msg"=>'未选择股东级别'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
        //股东级别名称
        $dis_config = dis_config($UsersID);
        $Sha_Rate = json_decode($dis_config['Sha_Rate'], true);
	$Sha_Name = !empty($Sha_Rate['sha'][$sha_level]['name'])?$Sha_Rate['sha'][$sha_level]['name']:'未知';
        
	$rsUser = $DB->GetRs('user','User_Money,User_PayPassword','where User_ID='.$rsOrder['User_ID']);

	if($rsUser['User_PayPassword'] != md5($_POST['password'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'支付密码错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsUser['User_Money'] < $rsOrder['Order_TotalPrice']){
		$Data = array(
			"status"=> 0,
			"msg"=>'当前余额不足'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
	$pay_order = new pay_order($DB, $OrderID);
	$response = $pay_order->deal_sha_order();
	
	$effset = $DB->set('distribute_account', array('Enable_Agent' => 1,'sha_level'=>$sha_level), ' WHERE `Users_ID`="' .$rsOrder['Users_ID']. '" AND `User_ID` = '.$rsOrder['User_ID']);
	if($response['status'] && $effset){
		$Data = array(
			"status"=> 1,
			'msg'=>'支付成功，你已经成为'.$Sha_Name.'股东',
			'url'=>distribute_url()
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		$Data = array(
			"status"=> 0,
			"msg"=>'支付失败'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}

}elseif($action == 'get_sha_pay_method'){
	if(empty($_POST['OrderID'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(empty($_POST['method'])){
		$Data = array(
			"status"=> 0,
			"msg"=>'参数错误'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$method = $_POST['method'];
	
	$OrderID = $_POST['OrderID'];
	$rsOrder = $DB->GetRs('sha_order','*','where Users_ID="'.$UsersID.'" and Order_ID='.$OrderID.' and User_ID='.$_SESSION[$UsersID.'User_ID']);
	
	if(!$rsOrder){
		$Data = array(
			"status"=> 0,
			"msg"=>'订单不存在'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsOrder['Order_Status']<>1){
		$Data = array(
			"status"=> 0,
			"msg"=>'该订单不是待付款状态'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");
	
	if($method==1){//微支付
		$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");
		if($rsPay["PaymentWxpayEnabled"]==0 || empty($rsPay["PaymentWxpayPartnerId"]) || empty($rsPay["PaymentWxpayPartnerKey"]) || empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
			$Data = array(
				"status"=> 0,
				"msg"=>'商家“微支付”支付方式未启用或信息不全，暂不能支付！'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if($rsPay["PaymentWxpayType"]==1){
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/wxpay2/sendto_sha.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/wxpay2/sendto_sha.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
	}elseif($method==2){//支付宝		
		if($rsPay["Payment_AlipayEnabled"]==0 || empty($rsPay["Payment_AlipayPartner"]) || empty($rsPay["Payment_AlipayKey"]) || empty($rsPay["Payment_AlipayAccount"])){
			$Data = array(
				"status"=> 0,
				"msg"=>'商家“支付宝”支付方式未启用或信息不全，暂不能支付！'
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 1,
				"url"=>'/pay/alipay/sendto_sha.php?UsersID='.$UsersID.'_'.$OrderID
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
}elseif($action=='send_hongbao'){
	if(empty($_POST['RecordID'])){
		$response = array('status'=>0,'msg'=>'参数不全');
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$RecordID = $_POST['RecordID'];
	$rsRecord = $DB->GetRs('distribute_withdraw_record','*','where Record_ID='.$RecordID.' and User_ID='.$_SESSION[$UsersID.'User_ID']);
	if(!$rsRecord){
		$response = array('status'=>0,'msg'=>'该提现记录不存在');
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsRecord['Record_SendType']!='wx_hongbao'){
		$response = array('status'=>0,'msg'=>'该提现记录不是使用的微信红包提现');
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsRecord['Record_Status']!=1){
		$response = array('status'=>0,'msg'=>'请勿非法操作');
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
	$pay_order = new pay_order($DB, 0);
	$Data = $pay_order->withdraw($UsersID,$_SESSION[$UsersID.'User_ID'],$RecordID,'wx_hongbao');
	if($Data['status']==1){
		$response = array('status'=>1,'msg'=>'领取成功，请查看微信红包');		
	}else{
		$response = array('status'=>0,'msg'=>$Data['msg']);
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	exit;
}