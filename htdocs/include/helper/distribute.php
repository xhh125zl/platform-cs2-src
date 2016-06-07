<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/General_tree.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/Distribute.php';

/*获取此店主的信息*/
function getOwner($DB, $UsersID) {

	$rsConfig = $DB->GetRs("shop_config", "Distribute_Customize,ShopName,ShopLogo", "where Users_ID='" . $UsersID . "'");

	//生命全局distribute_class
	
	global $DS_OBJ;
	$DS_OBJ = new Distribute($DB, $UsersID);

	if (!isset($_SESSION[$UsersID.'User_ID']) || empty($_SESSION[$UsersID.'User_ID'])) {
		$owner = getOwnerByUrl($DB, $UsersID); //用户不登录
	} else {
		$owner = getOwnerBySql($DB, $UsersID); //用户登录

	}

	//如果不允许会员自定义店名
	if ($rsConfig['Distribute_Customize'] == 0) {
		$owner['shop_name'] = $rsConfig['ShopName'];
		$owner['shop_logo'] = !empty($rsConfig['ShopLogo']) ? $rsConfig['ShopLogo'] : '/static/api/images/user/face.jpg';
	}

	return $owner;

}


/*通过url获取此店主的信息*/
function getOwnerByUrl($DB, $UsersID) {

	$owner_id = !empty($_GET['OwnerID']) ? $_GET['OwnerID'] : 0;
	$UsersID = $_GET['UsersID'];

	if ($owner_id != 0) {
		$ownerAccount = get_dsaccount_by_id($DB, $UsersID, $owner_id);
		$shop_name = $ownerAccount['Shop_Name'];
		$shop_logo = !empty($ownerAccount['Shop_Logo']) ? $ownerAccount['Shop_Logo'] : '/static/api/images/user/face.jpg';
		$shop_announce = $ownerAccount['Shop_Announce'];
		$owner = array('id' => $owner_id, 'shop_name' => $shop_name, 'shop_logo' => $shop_logo, 'shop_announce' => $shop_announce);
	} else {
		$owner = array('id' => 0);

	}

	return $owner;

}



/**
 *增加分销账户记录
 */
function add_distribute_account_record($DB, $UsersID,$Product, $OrderID, $OwnerID,$Qty,$Product_Price,$Ds_Record_ID){
	//增加分销账户记录

	global $DS_OBJ;
	
	$ancestor_list = $DS_OBJ->get_ancestor($OwnerID, $_SESSION[$UsersID.'User_ID']);	
	foreach ($ancestor_list as $key => $item) {

		if ($OwnerID == $item['User_ID']) {
			//自己获取佣金
			$Record_Description = '自己销售' . $Product['Products_Name'] . '&yen;' . $Product_Price. '成功，获取奖金';

		} else {
			//上级分销商获取佣金
			$Record_Description = '下属分销商分销' . $Product['Products_Name'] . '&yen;' . $Product_Price. '成功，获取奖金';
		}

		$data = array(
			"Users_ID" => $UsersID,
			"Buyer_ID" => $_SESSION[$UsersID.'User_ID'],
			"Ds_Record_ID"=>$Ds_Record_ID,
			"Order_ID" => $OrderID,
			"Product_ID"=> $Product['Products_ID'],
			"User_ID" => $item['User_ID'],
			"Owner_ID" => $OwnerID,
			"Record_Sn" => build_withdraw_sn(),
			"level" => $key + 1,
			"Record_Money" => !empty($Product['Distribute_List'][$key]) ? $Product_Price*$Product['Distribute_List'][$key]*$Qty : 0,
			"Record_CreateTime" => time(),
			"Record_Description" => $Record_Description,
			"Record_Type" => 0,
			"Record_Status" => 0,
		);

		$DB->add('distribute_account_record', $data);
	}

}

/**
 *获取此分销商的祖先
 */
function get_distribute_ancestor($DB, $UsersID, $OwnerID) {
	$rsAccounts = $DB->Get('distribute_account', '*', "where Users_ID='" . $UsersID . "'");
	//实例化通用树类
	$account_list = $DB->toArray($rsAccounts);
	$param = array('result' => $account_list, 'fields' => array('User_ID', 'invite_id'));
	$generalTree = new General_tree($param);
	$ancestor_list = $generalTree->navi($OwnerID);
	//返回数组中前三个元素
	ksort($ancestor_list);
	//如果是自己在自己的店购买，自己不得佣金
	if ($_SESSION[$UsersID."User_ID"] == $OwnerID) {
		array_shift($ancestor_list);
	}

	while (count($ancestor_list) > 3) {
		array_pop($ancestor_list);
	}

	return $ancestor_list;

}


/*分销成功后续操作*/
function handle_distribute_success($DB, $Order_ID,$ProductID,$UsersID) {

	mysql_query('start transaction');

	//更改分销记录状态
	$data = array('status' => 1);
	$condition = "where Users_ID='" . $UsersID . "' and Order_ID=" .$Order_ID." and Product_ID =".$ProductID;
	$DB->set('distribute_record', $data, $condition);

	

	//更改分销账户记录
	$data = array('Record_Status' => 2);
	$condition = "where Users_ID='" . $UsersID . "' and Order_ID=" . $Order_ID." and Product_ID =".$ProductID;
	$DB->set('distribute_account_record', $data, $condition);

	//分销账户增加余额
	$condition = "where Users_ID='" . $UsersID . "' and Order_ID=" . $Order_ID." and Product_ID =".$ProductID;
	$rsAccounts = $DB->Get('distribute_account_record', "User_ID,Record_Money", $condition);
	$account_list = $DB->toArray($rsAccounts);

	foreach ($account_list as $key => $item) {
		$condition = "where Users_ID='" . $UsersID . "' and User_ID=" . $item['User_ID'];
		$interest = $item['Record_Money'];
		$DB->set('distribute_account', 'balance=balance+' . $interest . ',Total_Income=Total_Income+' . $interest, $condition);
	}
	
	mysql_query('commit');
	
	//增加卖出者销售额
	global $DS_OBJ;
	$DS_OBJ = new Distribute($DB, $UsersID);
	$DS_OBJ->refresh();
	
	$DS_OBJ->update_group_sales($Order_ID,$ProductID);
}


/*
 *整理级别列表
 */
function orange_level($ds_dropdown, $user_dropdown, $UserID) {

	$level1 = $level2 = $level3 = array();

	foreach ($ds_dropdown as $key => $item) {
		if ($item['invite_id'] == $UserID) {
			if (!empty($user_dropdown[$key])) {
				$item['User_Name'] = $user_dropdown[$key];
				$level1[$item['User_ID']] = $item;
			}
		}

	}

	$leve1_ids = array_keys($level1);

	foreach ($ds_dropdown as $key => $item) {
		if (in_array($item['invite_id'], $leve1_ids)) {
			if (!empty($user_dropdown[$key])) {
				$item['User_Name'] = $user_dropdown[$key];
				$level2[$item['User_ID']] = $item;
			}
		}
	}

	$level2_ids = array_keys($level2);

	foreach ($ds_dropdown as $key => $item) {
		if (in_array($item['invite_id'], $level2_ids)) {
			if (!empty($user_dropdown[$key])) {
				$item['User_Name'] = $user_dropdown[$key];
				$level3[$item['User_ID']] = $item;
			}
		}
	}

	$level_list = array(1 => $level1, 2 => $level2, 3 => $level3);
	return $level_list;
}

/**
 *删除分销记录
 */
function delete_distribute_record($DB, $UsersID, $OrderID) {
	//删除分销记录
	$condition = "Users_ID='" . $UsersID . "' and Order_ID=" . $OrderID;
	$record_list = array();
	$DB->Get("distribute_record","Record_ID","where ".$condition);
	while($r = $DB->fetch_assoc()){
		$record_list[] = $r["Record_ID"];
	}
	$DB->Del("distribute_record", $condition);
	//删除分销账户记录
	if(count($record_list)>0){
		$condition = "Users_ID='" . $UsersID . "' and Ds_Record_ID in(" .implode(",",$record_list).")";
		$DB->Del("distribute_account_record", $condition);
	}

}

/**
 *判断此订单是否为分销订单
 */
function is_distribute_order($DB, $UsersID, $OrderID) {
	//检测此订单是否为分销订单
	$condition = "where Users_ID='" . $UsersID . "' and Order_ID=" . $OrderID;

	$order = $DB->getRs('user_order', 'Order_CartList', $condition);

	$Flag = false;

	$CartList = json_decode(htmlspecialchars_decode($order['Order_CartList']), TRUE);

	if (count($CartList) > 0) {
		foreach ($CartList as $ProductID => $product_list) {
			foreach ($product_list as $key => $item) {
				if ($item['OwnerID'] > 0) {
					$Flag = true;
					break 2;
				}
			}
		}
	}

	return $Flag;
}

/**
 * 生成分销推广海报
 * @param  string $data 海报内容的base64数据
 * @return  bool  $Flag 是否成功生成海报
 *
 * */
function generate_postere($img, $UsersID, $owner_id) {

	define('UPLOAD_DIR', $_SERVER["DOCUMENT_ROOT"] . '/data/poster/');
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file_name = '';
	$file_path = UPLOAD_DIR . $UsersID . $owner_id . '.png';
	$web_path = '/data/poster/' . $UsersID . $owner_id . '.png';
	$Flag = file_put_contents($file_path, $data);

	return $Flag;
}

/**
 * 增加分销称号
 * @param   $DB 数据库连接
 * @param   $UsersID
 * @return  $Flag 是否设置成功
 *
 */
function add_dis_pro_title($DB, $UsersID, $pro_titles) {
	$data = array('Users_ID' => $UsersID,
		'Pro_Title_Level' => json_encode($pro_titles, JSON_UNESCAPED_UNICODE));

	$Flag = $DB->Add('distribute_config', $data, "where Users_ID='" . $UsersID . "'");
	return $Flag;

}
function set_dis_pro_title($DB, $UsersID, $pro_titles) {
	$data = array('Users_ID' => $UsersID,
		'Pro_Title_Level' => json_encode($pro_titles, JSON_UNESCAPED_UNICODE));

	$Flag = $DB->Set('distribute_config', $data, "where Users_ID='" . $UsersID . "'");
	return $Flag;

}

/**
 * 获得分销商称号
 * @param   $DB 数据库连接
 * @param   $UsersID
 * @param   $type  front前台调用，back 后台调用
 * @return Array $rsDsConfig 分销商配置
 *  */
function get_dis_pro_title($DB, $UsersID,$type= 'front') {
	$rsDsConfig = $DB->GetRs('distribute_config', 'Pro_Title_Level', "where Users_ID='" . $UsersID . "'");

	$pro_titles = false;

	if ($rsDsConfig) {
		$pro_titles = json_decode($rsDsConfig['Pro_Title_Level'], TRUE);
		
		if(!empty($pro_titles)){
		if($type == 'front'){
			
			foreach($pro_titles as $key=>$item){
				if(strlen($item['Name']) == 0){
					unset($pro_titles[$key]);
				}
			}
			ksort($pro_titles);
			
		}
		}
		
	}

	return $pro_titles;
}

function up_professional_title_by_group_sales($DB, $UsersID, $User_ID, $account_list, $OrderSales,$Owner_ID) {

	$Flag = true;
	if (!empty($Owner_ID)) {
	
		//获取此订单店主分销账号信息
		$ds_account = $account_list[$User_ID];
		
		//获取分销称号信息
		$data['Total_Sales'] = $ds_account['Total_Sales'] + $OrderSales;
		$pro_titles = get_dis_pro_title($DB, $UsersID);
		
		if (!empty($pro_titles)) {
			
			$top_level = count($pro_titles);
			$total_sales = $ds_account['Total_Sales'];
			$up_group_sales = $ds_account['Up_Group_Sales'];
			$group_sales = $ds_account['Group_Sales'];
			$Ex_Bonus = $ds_account['Ex_Bonus'];
			$last_award_income = $ds_account['last_award_income'];
			$pro_title = $ds_account['Professional_Title'];

			$total_sales = $total_sales + $OrderSales;
			$up_group_sales = $up_group_sales + $OrderSales;

			$group_sales = $group_sales + $OrderSales;
		
		
			//已经是最高级
			if ($pro_title == $top_level) {
				//最高级利润率

				if (count($pro_titles) > 2) {
					$top_up_stock = $pro_titles[$top_level]['Saleroom'] - $pro_titles[$top_level - 1]['Saleroom'];
				} else {
					$top_up_stock = $pro_titles[$top_level];
				}

				if ($up_group_sales >= $top_up_stock) {
					$income = $ds_account['Total_Income'] - $last_award_income;
					$Bonus = $pro_titles[$top_level]['Bonus'];
					$rate = $Bonus / 100;
					$Ex_Bonus += $income * $rate;
					$last_award_income = $ds_account['Total_Income'];
					$up_group_sales = $up_group_sales - $top_up_stock;
				}
				
				$cur_title = $pro_title;

			}else{
				//不是最高级

				$cur_title = determine_dis_protitle_by_group_sales($pro_titles, $group_sales, $pro_title);

				if ($cur_title > $pro_title) {
					$income = $ds_account['Total_Income'] - $last_award_income;
				
					
					$Bonus = $pro_titles[$cur_title]['Bonus'];
					$rate = $Bonus/100;
					$Ex_Bonus += $income*$rate;
					
					//计算极差
					if($cur_title == 1){
						$level_range = $pro_titles[$cur_title]['Saleroom'];
					}else{
						$level_range = $pro_titles[$cur_title]['Saleroom']-$pro_titles[$cur_title-1]['Saleroom'];
					}
					
					$last_award_income = $ds_account['Total_Income'];
					$up_group_sales = $up_group_sales - $level_range;
				}

			}

			$data['Up_Group_Sales'] = $up_group_sales;
			$data['Group_Sales'] = $group_sales;
			$data['Professional_Title'] = $cur_title;
			$data['Ex_Bonus'] = $Ex_Bonus;
			$data['last_award_income'] = $last_award_income;
		}

		$condition = "where Users_ID='" . $UsersID . "' and User_ID=" . $User_ID;
		$Flag = $DB->set('distribute_account', $data, $condition);
	}
	
	return $Flag;

}





/**
 *通过团队人数对分销账号授予爵位并发送奖励
 */
function up_professional_title_by_group_num($DB, $UsersID, $UserID, $account_list) {
	$ds_dropdown = get_dropdown_list($account_list, 'User_ID');
	$pro_titles = get_dis_pro_title($DB, $UsersID);
	$ds_account = $ds_dropdown[$UserID];

	$data = array();
	$data['Group_Num'] = $ds_account['Group_Num'] + 1;
	$Flag = TRUE;
	if (!empty($pro_titles)) {
		
		$top_level = count($pro_titles);

		$up_group_num = $ds_account['Up_Group_Num'];
		$Ex_Bonus = $ds_account['Ex_Bonus'];
		$last_award_income = $ds_account['last_award_income'];
		$pro_title = $ds_account['Professional_Title'];

		//已经是最高级
		if ($pro_title == $top_level) {
			if (count($pro_titles) > 2) {
				$top_up_stock = $pro_titles[$top_level]['Group_Num'] - $pro_titles[$top_level - 1]['Group_Num'];
			} else {
				$top_up_stock = $pro_titles[$top_level]['Group_Num'];
			}

			//增加人数后需要奖励
			if ($top_up_stock == $up_group_num + 1) {
				$income = $ds_account['Total_Income'] - $last_award_income;
				$Bonus = $pro_titles[$top_level]['Bonus'];
				$rate = $Bonus / 100;
				$Ex_Bonus += $income * $rate;
				$last_award_income = $ds_account['Total_Income'];
				$up_group_num = 0; //奖励成功后升级所需用户数被置零
			} else {
				//未奖励
				$up_group_num = $up_group_num + 1;
			}

		} else {

			$cur_level = determine_dis_protitle_by_num($pro_titles, $data['Group_Num'], $pro_title);

			//升级
			if ($cur_level > $pro_title) {
				$income = $ds_account['Total_Income'] - $last_award_income;
				$Bonus = $pro_titles[$cur_level]['Bonus'];

				$rate = $Bonus / 100;
				$Ex_Bonus += $income * $rate;
				$last_award_income = $ds_account['Total_Income'];
				$up_group_num = 0; //奖励成功后升级所需用户数被置零
				$pro_title = $cur_level;

			} else {
				$up_group_num = $up_group_num + 1;
			}

		}

		$data['Ex_Bonus'] = $Ex_Bonus;
		$data['Up_Group_Num'] = !empty($up_group_num) ? $up_group_num : 0;
		$data['Professional_Title'] = !empty($pro_title) ? $pro_title : 0;
		$data['last_award_income'] = $last_award_income;

		$condition = "where Users_ID = '" . $UsersID . "' and User_ID=" . $UserID;
		$Flag = $DB->Set('distribute_account', $data, $condition);
		
	}
	
	return $Flag;

}



/**
 *获取此用户的不可用余额(即已申请提现，但未执行提现的现金金额)
 */
function get_useless_sum($UsersID,$User_ID){
	global $DB1;
	
	$condition = "where User_ID=".$User_ID." and Users_ID='" . $UsersID . "' and Record_Type=1 and Record_Status = 0";
	$withdraw_records = $DB1->getRs("distribute_account_record", "sum(Record_Money) as useless_sum", $condition);
	$useless_sum = !empty($withdraw_records['useless_sum']) ? $withdraw_records['useless_sum'] : 0;
	return $useless_sum;
}




/**
 *获取总部分销商排行榜名次
 */
function get_h_incomelist_rank($UsersID,$User_ID,$H_Incomelist_Limit,$Open){
   
   global $DB1;
   $condition = "where Users_ID= '".$UsersID."' and Total_Income >= ".$H_Incomelist_Limit;
   $condition .= ' order by Total_Income desc limit 0,100';

   $fields = 'User_ID,Shop_Name,Shop_Logo,Professional_Title,Total_Income';
   
   $rsAccounts = $DB1->get('distribute_account',$fields,$condition);
   $account_list = $DB1->toArray($rsAccounts);
 
   //判断指定$User_ID 是否在列表之中
   $Flag = FALSE;
   $Rank = NULL;
   
   if($Open == 1){
	
	$Flag = TRUE;
	$Rank = TRUE;
	
   }else{
	   
	foreach($account_list as $key=>$item){
			if($User_ID == $item['User_ID']){
				$Rank = $key+1;
				$Flag = TRUE;
				break;
			}
	}   
   
   }
   
   
   if($Flag){
	   $result = array('rank'=>$Rank,
					  'H_Incomelist'=>$account_list);
	   
   }else{
	  $result = false;
   }
	
   return $result;
}

//判断为金额数字
function is_shuz($money){	
	if(preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money)){
	$feifa = 0;
	}else{
	$feifa = 1;
	}	
	return $feifa;	
}
