<?php
/*订单操作函数*/

/**
 * 订单确认收货操作
 * @param  Object $DB     数据库操作对象
 * @param  String $UsersID 店主唯一ID
 * @param  String $OrderID 用户订单号
 * @param
 * @return Bool   $Falg    最终是否操作成功
 */
function confirm_receive($DB, $UsersID, $Order_ID) {

	$rsConfig = $DB->GetRs("shop_config", "*", "where Users_ID='" . $UsersID . "'");

	$condition = "where Users_ID='" . $UsersID . "'  and Order_ID=" . $Order_ID;

	$order = $DB->getRs('user_order', 'Order_TotalPrice,Order_CartList,User_ID,Order_TotalAmount', $condition);
	$User_ID = $order['User_ID'];

	$CartList = json_decode(htmlspecialchars_decode($order["Order_CartList"]), true);

	//分销返利处理
	$interest_total = 0;
	if(!empty($CartList)){
		foreach ($CartList as $ProductID => $product_list) {
			foreach ($product_list as $key => $item) {
				if ($item['OwnerID'] != 0) {
					//将分销返利打入用户分销账号
					handle_distribute_success($DB,$Order_ID,$ProductID,$UsersID);
				}
			}
		}
	}
	
	$interval = 0;
	
	if(!empty($rsConfig['Integral_Convert'])){
		$interval = floor($order['Order_TotalPrice'] / abs($rsConfig['Integral_Convert']));
	}
	
	//更新用户信息
	$condition = "where Users_ID='" . $UsersID . "' and User_ID='" . $User_ID . "'";
	$user = $DB->GetRs('user', 'User_Integral,User_TotalIntegral,User_Cost,User_Level,Is_Distribute,Owner_Id,User_Name,User_NickName', $condition);
	if($user){
		//获取用户升级条件
		$rsUserConfig = $DB->GetRs("User_Config", "UserLevel", "where Users_ID='" . $UsersID . "'");
		$level_list = json_decode($rsUserConfig["UserLevel"], TRUE);

		//检测用户购买此次产品后是否升级

		$user_cost = $user['User_Cost'] + $order['Order_TotalAmount'];
		
		$cur_level =0;
		
		if(count($level_list) >1 ){
			$cur_level = determine_user_level($level_list, $user_cost);
			if ($cur_level > $user['User_Level']) {
				$user['User_Level'] = $cur_level;
			}
		}
		
		$data = array(
			"User_Integral" => $user['User_Integral'] + $interval,
			"User_TotalIntegral" => $user['User_TotalIntegral'] + $interval,
			"User_Cost" => $user_cost,
			"User_Level" => $cur_level,
		);

		$Flag = $DB->set('user', $data, $condition);
		$Data = array(
			'Record_Integral' => $interval,
			'Record_SurplusIntegral' => $user['User_Integral'] + $interval,
			'Operator_UserName' => '',
			'Record_Type' => 2,
			'Record_Description' => '购买商品送 '.$interval.' 个积分',
			'Record_CreateTime' => time() ,
			'Users_ID' => $UsersID,
			'User_ID' => $User_ID
		);
		$Flag = $DB->Add('user_Integral_record', $Data);
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
		$weixin_message = new weixin_message($DB,$UsersID,$User_ID);
		$contentStr = '购买商品送 '.$interval.' 个积分';
		$weixin_message->sendscorenotice($contentStr);
	}

	$data = array("Order_Status" => 4);

	$condition = "where Users_ID='" . $UsersID . "' and User_ID='" . $User_ID . "' and Order_ID=" . $Order_ID;
	$Flag = $DB->set('user_order', $data, $condition);
	
	return $Flag;
}

/*逾期未确认订单，自动确认收货*/
function batch_confirm_receive_auto($DB, $UsersID) {

	global $DS_OBJ;
	$DS_OBJ = new Distribute($DB, $UsersID);
	
	$rsConfig = $DB->GetRs("shop_config", "Confirm_Time", "where Users_ID='" . $UsersID . "'");
	$Confirm_Time = $rsConfig['Confirm_Time'];

	$end_time = time();
	$begin_time = $end_time - $rsConfig['Confirm_Time'];

	$condition = "where Users_ID= '" . $UsersID . "'";

	$condition .= " and Is_Backup = 0 ";
	$condition .= " and Order_Status = 3 ";

	$condition .= " and Order_CreateTime <=" . $begin_time;
	$rsOrders = $DB->Get("user_order", "Order_ID", $condition);

	$order_list = $DB->toArray($rsOrders);

	if (count($order_list) > 0) {
		$order_id_array = array();
		foreach ($order_list as $key => $item) {
			confirm_receive($DB, $UsersID, $item['Order_ID']);
		}
	}

}

/**
 *根据消费额确定用户级别
 */
function determine_user_level($User_Level, $User_Cost) {

	$level_dropdown = array();
	$level_range_list = array();
	$level_count = count($User_Level);
	$level_begin_cost = $User_Level[1]['UpCost'];
	$level_end_cost = $User_Level[$level_count - 1]['UpCost'];

	//如果消费额小于等级起始消费额，1级

	if ($User_Cost < $level_begin_cost) {
		return 0;
	}

	//如果消费额大于等级结束消费额,最高级
	if ($User_Cost >= $level_end_cost) {
		return $level_count - 1;
	}

	//除此之外，循环确定
	foreach ($User_Level as $key => $item) {

		if ($key != $level_count - 1) {
			$end_cost = $User_Level[$key + 1]['UpCost'];
		} else {
			$end_cost = 999999999; //用一个很大的数表示等级的终点
		}

		$level_range_list[$key] = array('begin_cost' => $item['UpCost'], 'end_cost' => $end_cost);
	}

	foreach ($level_range_list as $key => $item) {

		if ($User_Cost >= $item['begin_cost'] && $User_Cost < $item['end_cost']) {

			return $key;
		}
	}

}


/**
 * 用户积分加减与添加积分变动记录
 * @param  String $UsersID  
 * @param  int $User_Id  用户ID
 * @param  int $Integral 所变动积分多少
 * @param  String $type    变动类型 add与reduce
 * @param  String $desc    积分变动描述
 * @param  String $useless  真实积分变动，还是虚拟积分变动 TRUE ,真实积分 FALSE 真实积分
 * @return Bool           操作是否成功
 */
function change_user_integral($UsersID,$User_Id,$Integral,$type,$desc,$useless = TRUE){

	if($type == 'add'){
		$operate = '+';
	}else if($type == 'reduce'){
		$operate = '-';
	}
	
	global $DB1;
	
	$rsUser = $DB1->GetRs("user","User_Money,User_PayPassword,Is_Distribute,User_Name,User_NickName,Owner_Id,User_Integral","where Users_ID='".$UsersID."' and User_ID=".$User_Id);
	
	mysql_query('start transaction');
	
	if($useless){
		$integral_data['User_UseLessIntegral'] = "User_UseLessIntegral$operate$Integral";
	}else{
		$integral_data['User_Integral'] = "User_Integral$operate$Integral"; 
	}
	
	$condition = "where Users_ID='".$UsersID."' and User_ID=".$User_Id;
	
	if($useless){
		$Flag_a = $DB1->Set('user',$integral_data,$condition,'User_UseLessIntegral');
	}else{
		$Flag_a = $DB1->Set('user',$integral_data,$condition,'User_Integral');
	}

	$integral_minus_data=array(
			'Record_Integral'=>$operate.$Integral,
			'Record_SurplusIntegral'=>$rsUser['User_Integral']-$Integral,
			'Operator_UserName'=>'',
			'Record_Type'=>3,
			'Record_Description'=>$desc,
			'Record_CreateTime'=>time(),
			'Users_ID'=>$UsersID,
			'User_ID'=>$User_Id
										);
	$Flag_b = $DB1->Add('user_Integral_record',$integral_minus_data);
	

	if($Flag_a&&$Flag_b){
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
		$weixin_message = new weixin_message($DB1,$UsersID,$User_Id);
		$contentStr = '购买商品抵用 '.$Integral.' 个积分';
		$weixin_message->sendscorenotice($contentStr);
		
		mysql_query('commit');
		$result =  TRUE;
	}else{
		mysql_query("ROLLBACK");
		$result =  FALSE;
	}

	return $result;
}	
 