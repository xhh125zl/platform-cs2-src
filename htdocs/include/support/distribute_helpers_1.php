<?php
//网中网分销处理helper
if (!function_exists('get_owner')) {
	/*获取此店主的信息*/
	function get_owner($rsConfig, $UsersID) {
		
		if (!isset($_SESSION[$UsersID . 'User_ID']) || empty($_SESSION[$UsersID . 'User_ID'])) {
			$owner = get_owner_by_url($UsersID); //用户不登录
		} else {
			$owner = get_owner_by_sql($UsersID); //用户登录
		}
	
		//如果不允许会员自定义店名
		if ($rsConfig['Distribute_Customize'] == 0) {
			$owner['shop_name'] = $rsConfig['ShopName'];
			$owner['shop_logo'] = !empty($rsConfig['ShopLogo']) ? $rsConfig['ShopLogo'] : '/static/api/images/user/face.jpg';
		}
		return $owner;
	}
}

if (!function_exists('get_owner_by_url')) {
	//通过url获得ownerid
	function get_owner_by_url($UsersID) {
		$owner_id = !empty($_GET['OwnerID']) ? $_GET['OwnerID'] : 0;

		if ($owner_id != 0) {
			$ownerAccount = get_dsaccount_by_id($UsersID, $owner_id);
			$shop_name = $ownerAccount['Shop_Name'];
			$shop_logo = !empty($ownerAccount['Shop_Logo']) ? $ownerAccount['Shop_Logo'] : '/static/api/images/user/face.jpg';
			$shop_announce = $ownerAccount['Shop_Announce'];
			
			$owner = array('id' => $owner_id, 'shop_name' => $shop_name, 'shop_logo' => $shop_logo, 'shop_announce' => $shop_announce,'Root_ID'=>get_owner_rootid($ownerAccount));
		} else {
			
			$owner = array('id' => 0,'Root_ID'=>0);
		}
		
		
		return $owner;
	}
}

if (!function_exists('get_owner_by_sql')) {
	//通过url获得ownerid
	function get_owner_by_sql($UsersID) {
		$User_ID = $_SESSION[$UsersID ."User_ID"];
		$user_obj = User::Multiwhere(array('Users_ID' => $UsersID, 'User_ID' => $User_ID))
			->first();
		
		if(!empty($user_obj)){
			$user = $user_obj->toArray();
		}else{
			echo '发生不可预知的错误，请联系管理员';
			exit();
		}
						
		if ($user['Is_Distribute'] == 1) {
			$owner_id = $_SESSION[$UsersID . 'User_ID'];
			$ownerAccount = get_dsaccount_by_id($UsersID, $owner_id);
			
			//若登录用户的分销身份已审核通过,则店主就是他自己
			//若登录用户的分销身份未审核通过,则店主仍是他的推荐人
			if ($ownerAccount['Is_Audit'] != 1) {
				$owner_id = $user['Owner_Id'];
				if ($owner_id > 0) {
					$ownerAccount = get_dsaccount_by_id($UsersID, $user['Owner_Id']);

					if (empty($ownerAccount)) {
						echo '不存在这个店主,您的推荐人已被删除!!!';
						exit();
					}
				} else {
					$ownerAccount = array(
						'Shop_Name' => '',
						'Shop_Logo' => '',
						'Shop_Announce' => '',
						'User_ID'=>0,
						
					);
				}
			}

		} else {
			$owner_id = $user['Owner_Id'];
			$ownerAccount = get_dsaccount_by_id($UsersID, $user['Owner_Id']);
		}
	
		
		$root_id = get_owner_rootid($ownerAccount);
		$shop_name = $ownerAccount['Shop_Name'];
		$shop_logo = !empty($ownerAccount['Shop_Logo']) ? $ownerAccount['Shop_Logo'] : '/static/api/images/user/face.jpg';
		$shop_announce = $ownerAccount['Shop_Announce'];

		$owner = array('id' => $owner_id, 'shop_name' => $shop_name, 'shop_logo' => $shop_logo, 'shop_announce' => $shop_announce,'Root_ID'=>get_owner_rootid($ownerAccount));
		
		return $owner;
	}
}


if(!function_exists('get_owner_rootid')){
	
	function get_owner_rootid($dsAccount){
		
		 if(!empty($dsAccount['fullDisPath'])){
			   //若全路径中含逗号，则是逗号前第一个数字,
			   //若全路径不含逗号，只是一个数字,则RootID就是这个数字
			   
			   $res  = strstr($dsAccount['fullDisPath'],',',TRUE);
			   $rootID = $res?$res:$dsAccount['fullDisPath'];
			}else{	
			   $rootID = $dsAccount['User_ID'];	
		 } 	
		
		 return $rootID;
		 
	}
}

if (!function_exists('get_dsaccount_by_id')) {
	function get_dsaccount_by_id($UsersID, $User_ID) {
	global $DB1;	
 
		if($User_ID==0){			
			$account = array('id' => 0,'User_ID'=>0, 'Shop_Name' => '', 'Shop_Logo' => '', 'Shop_Announce' => '','Root_ID'=>0);
		}else{
			
                        $where = ['Users_ID' => $UsersID, 'User_ID' => $User_ID];
			$account_obj = Dis_Account::Multiwhere($where);			   
			if(empty($account_obj)){
				echo '不存在这个店主';
				exit();
			}				
			$account =  $account_obj->toArray();
                        $account['fullDisPath'] = $account_obj->getFullDisPath(); 
                       
                       /*$account = $DB1->GetRs('distribute_account','*',"where Users_ID='".$UsersID."' and User_ID=".$User_ID);
                        if(empty($account)){
				echo '不存在这个店主';
				exit();
			}
                        $account_record = new Dis_Account();
                        $account['fullDisPath'] = 	$account_record->getFullDisPath();*/
							  
		}

		return $account;

	}

}

if (!function_exists('income_list')) {
	function income_list($list, $num = 0) {
		if ($num == 0) {
			$num = $list->count();
		}

		$income_list = $list->sortByDesc('Total_Income')
		                          ->take($num)
		                          ->map(function ($node) {
			                          return $node->toArray();
		                          })->toArray();
		return $income_list;

	}
}

if (!function_exists('dsaccount_bonus_statistic')) {
	/**
	 * 整理下属分销账号
	 * @param  Int $User_ID        用户ID
	 * @param  Array $Descendants 下属分销账号
	 * @return Array $posterity    含有级别下属分销账号
	 *         结构  array(1=>array(),2=>array(),3=>array());
	 *
	 */
	function dsaccount_bonus_statistic($record_list) {

		$un_pay = $payed = $completed = $total = 0;
		$day_income = $month_income = $week_income = 0;

		//计算时间点
		$today = strtotime('today');
		$now = strtotime('now');

		//计算本周时间始末
		$date = date('Y-m-d'); //当前日期
		$first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
		$w = date('w', strtotime($date)); //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
		$week_start = strtotime("$date -" . ($w ? $w - $first : 6) . ' days'); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		$week_start_day = date('Y-m-d', $week_start); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		$week_end = strtotime("$week_start_day +6 days"); //本周结束日期

		//计算本月时间始末
		$month_start = mktime(0, 0, 0, date('m'), 1, date('Y'));
		$month_end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));

		foreach ($record_list as $key => $item) {

			//未付款
			if ($item['Record_Status'] == 0) {
				$un_pay += $item['Record_Money'];
			}

			//已付款
			if ($item['Record_Status'] == 1) {
				$payed += $item['Record_Money'];
			}

			//已完成
			if ($item['Record_Status'] == 2) {
				$payed += $item['Record_Money'];
				$completed += $item['Record_Money'];
			}

			//今日收入
			if ($today < $item['Record_CreateTime'] && $item['Record_CreateTime'] < $now) {
				$day_income += $item['Record_Money'];
			}

			//本周收入
			if ($week_start < $item['Record_CreateTime'] && $item['Record_CreateTime'] < $week_end) {
				$week_income += $item['Record_Money'];
			}

			//本月收入
			if ($month_start < $item['Record_CreateTime'] && $item['Record_CreateTime'] < $month_end) {
				$month_income += $item['Record_Money'];
			}

			$total += $item['Record_Money'];
		}

		$result = array('un_pay' => round_pad_zero($un_pay, 2),
			'payed' => round_pad_zero($payed, 2),
			'completed' => round_pad_zero($completed, 2),
			'day_income' => round_pad_zero($day_income, 2),
			'week_income' => round_pad_zero($week_income, 2),
			'month_income' => round_pad_zero($month_income, 2),
			'total' => round_pad_zero($total, 2));

		return $result;

	}

}

if (!function_exists('get_distribute_withdraw')) {
	function get_distribute_withdraw($UsersID, $enable, $type, $limit, $shop_url, $color = '#FFF', $status = 0) {
		$msg = '';
		if ($enable == 0) {
			$msg .= '您还不是老板,';
			if ($type == 1) {
				$msg .= '请 <a href="' . $shop_url . '" style="color:' . $color . '; text-decoration:underline">购买</a> 成为老板';
				if ($status == 1) {
					$msg .= '<a href="' . $shop_url . '" style="display:block; width:120px; margin-top:4px; line-height:28px; height:28px; border:1px #FFF solid; border-radius:10px; color:#FFF; text-align:center">立即成为老板</a>';
				}
			} elseif ($type == 2) {
				
				
				$products = Product::Multiwhere(array('Users_ID' => $UsersID, 'Products_ID'=> $limit))
					->first(array('Products_ID', 'Products_Name'));

				if (!empty($products)) {
					$products = $products->toArray();
					$msg .= '请 <a href="' . $shop_url . 'products_virtual/' . $products["Products_ID"] . '/" style="color:' . $color . '; text-decoration:underline">点击购买</a> 成为老板';
					if ($status == 1) {
						$msg .= '<a href="' . $shop_url . 'products_virtual/' . $products["Products_ID"] . '/" style="display:block; width:120px; margin-top:4px; line-height:28px; height:28px; border:1px #FFF solid; border-radius:10px; color:#FFF; text-align:center">立即成为老板</a>';
					}
				} else {
					$msg .= '请 <a href="' . $shop_url . '" style="color:' . $color . '; text-decoration:underline">点击购买</a> 成为老板';
					if ($status == 1) {
						$msg .= '<a href="' . $shop_url . '" style="display:block; width:120px; margin-top:4px; line-height:28px; height:28px; border:1px #FFF solid; border-radius:10px; color:#FFF; text-align:center">立即成为老板</a>';
					}
				}
			} elseif ($type == 3) {
				
				$msg .= '您的分销佣金达到 ' . $limit . ' 元才可成为老板';
				if ($status == 1) {
					$msg .= '<a href="' . $shop_url . '" style="display:block; width:120px; margin-top:4px; line-height:28px; height:28px; border:1px #FFF solid; border-radius:10px; color:#FFF; text-align:center">立即成为老板</a>';
				}
				
				if($limit == 0){
					$msg = '';
				}
				
			}
		}

		return $msg;
	}

}

if (!function_exists('get_posterity_ids')) {
	/**
	 *获取下属分销商id数组
	 */
	function get_posterity_ids($posterity) {

		$id_array = array();
		foreach ($posterity as $key => $sub_list) {
			foreach ($sub_list as $key => $item) {
				$id_array[] = $item['User_ID'];
			}
		}

		return $id_array;

	}
}

if (!function_exists('get_my_leiji_income')) {
	/*
	 *获取我的累计佣金收入
	 */
	function get_my_leiji_income($UsersID, $UserID) {
		global $DB1;
		//付过款才计入，商城销售
		/*
		$record_list = Dis_Account_Record::Multiwhere(array('Users_ID' => $UsersID, 'User_ID' => $UserID,'Record_Type'=>0))
			->whereIn('Record_Status',array(1,2))
			->get(array('Record_Money'))
			->toArray();
			
		foreach ($record_list as $key => $item) {
			$total_income += $item['Record_Money'];
		}
		*/
		$total_income = 0;
		
		$r = $DB1->GetRs('distribute_account_record','SUM(Record_Money) as money','where User_ID='.$UserID.' and Record_Type=0 and Record_Status>=1');
		if(!empty($r['money'])){
			$total_income = $total_income + $r['money'];
		}

		
		//下级购买或升级分销商级别销售
		
		$r = $DB1->GetRs('distribute_order_record','SUM(Record_Money) as money','where User_ID='.$UserID);
		if(!empty($r['money'])){
			$total_income = $total_income + $r['money'];
		}

		return $total_income;
	}
}


if (!function_exists('get_my_leiji_sales')) {
	/**
	 *我的团队累计销售额
	 */
	function get_my_leiji_sales($UsersID, $UserID, $posterity) {

		$total_sales = 0;
		
		/*
		//计算本店下普通用户所购买商品销售额
		$record_list_obj = Dis_Record::Multiwhere(array('Users_ID' => $UsersID))
				->where('Owner_ID',$UserID)
				->get(array('Product_Price', 'Qty'));

		if(!empty($record_list_obj)){
			$record_list = $record_list_obj->toArray();
			foreach ($record_list as $key => $item) {
				$total_sales += $item['Product_Price'] * $item['Qty'];
			}
		}
		*/		
		
		//计算本店下属分销商作为用户所购买商品销售额
		
		$posterityids = array();
		if (count($posterity) > 0) {
			$posterityids = $posterity->map(function ($node) {
				return $node->User_ID;
			})->toArray();
		}
		
		$posterityids[] = $UserID;
		
		global $DB1;
		$r = $DB1->GetRs('user_order','SUM(Order_TotalAmount) as money','where Owner_ID in('.implode(',',$posterityids).') and Order_Status>=2');
		if(!empty($r['money'])){
			$total_sales = $total_sales + $r['money'];
		}
		$r = $DB1->GetRs('distribute_order','SUM(Order_TotalPrice) as money','where Owner_ID in('.implode(',',$posterityids).') and Order_Status>=2');
		if(!empty($r['money'])){
			$total_sales = $total_sales + $r['money'];
		}
		
		/*
		if (count($posterityids) > 0) {
			$recode_list = array();
			$record_list = Dis_Record::Multiwhere(array('Users_ID' => $UsersID))
				->whereIn('Owner_ID', $posterityids)
				->get(array('Product_Price', 'Qty'))
				->toArray();

			$posterity_total_sales = 0;
			foreach ($record_list as $key => $item) {
				$posterity_total_sales += $item['Product_Price'] * $item['Qty'];
			}
			$total_sales += $posterity_total_sales;

		}
		*/

		return $total_sales;

	}
}

if (!function_exists('add_distribute_record')) {
	//增加分销记录
	function add_distribute_record($UsersID, $OwnerID, $Product_Price, $ProductID, $Qty, $OrderID, $Profit, $CartID) {
		//此产品利润为零,不处理
		if ($Profit <= 0) {
			return false;
		}
		
		$fields = ['Products_ID', 'Products_PriceX', 'Products_Distributes', 'Biz_ID', 'Products_Name', 'commission_ratio', 'platForm_Income_Reward', 'area_Proxy_Reward', 'nobi_ratio', 'sha_Reward'];
		$Product = Product::Multiwhere(array('Users_ID' => $UsersID, 'Products_ID' => $ProductID))
			->first($fields)
			->toArray();
		
		if($Product["commission_ratio"]<=0){//佣金比例
			return false;
		}
		
		$Product["Products_Profit"] = $Profit;
		
		//佣金比例数组
		$Products_Distributes = $Product['Products_Distributes'] ? json_decode($Product['Products_Distributes'], true) : array();
		$distribute_bonus_list = array();
		if(!empty($Products_Distributes)){
			foreach ($Products_Distributes as $Key => $item) {
				foreach($item as $k=>$v){
					$distribute_bonus_list[$Key][$k] = $Profit * $Product['commission_ratio'] * $Product['platForm_Income_Reward'] * $v / 1000000;
				}
			}
		}
		
		if(empty($distribute_bonus_list)){
			return false;
		}
		
		$Product['Distribute_List'] = $distribute_bonus_list;//佣金列表，已经计算出具体金额	
		$Product['Products_Price'] = $Product_Price;
		
		$Product['CartID'] = $CartID;
		$rsConfig = shop_config($UsersID);
		//分销相关设置
		$dis_config = dis_config($UsersID);
		//合并参数
		$rsConfig = array_merge($rsConfig,$dis_config);
		
		//增加分销记录
		$dis_record = new Dis_Record();
		$dis_record->Users_ID = $UsersID;
		$dis_record->Buyer_ID = $_SESSION[$UsersID . 'User_ID'];
		$dis_record->Owner_ID = $OwnerID;
		$dis_record->Order_ID = $OrderID;
		$dis_record->Product_ID = $ProductID;
		$dis_record->Product_Price = $Product_Price;
		$dis_record->Qty = $Qty;
		$dis_record->Record_CreateTime = time();
		$dis_record->status = 0;
		$dis_record->Biz_ID = $Product["Biz_ID"];
		
		//$dis_record->Product = $Product;
		//为分销记录model设置观察者，
		DisRecordObserver::$shop_config = $rsConfig;
		DisRecordObserver::$Product = $Product;
		DisRecordObserver::$Qty = $Qty;
		DisRecordObserver::$Order_ID = $OrderID;
		$dis_record->save();
	}
}

if (!function_exists('get_product_distribute_info')) {
    /**
    *获取指定产品的分析信息
    */
	function get_product_distribute_info($UsersID, $ProductID) {

		$fields = ['Products_ID', 'Products_Distributes', 'Products_Profit', 'Products_Name',
   		           'Products_PriceX','commission_ratio'];
		$product = Product::Multiwhere(array('Users_ID' => $UsersID, 'Products_ID' => $ProductID))
			->first($fields)
			->toArray();

		$Products_Distributes = json_decode($product['Products_Distributes'], true);
		$distribute_bonus_list = array();
		if (count($Products_Distributes) > 0) {
			foreach ($Products_Distributes as $Key => $item) {
				$distribute_bonus_list[$Key] = $product['Products_Profit'] * $item / 10000;
			}
		}

		$product['Distribute_List'] = $distribute_bonus_list;

		return $product;
	}
} 

if (!function_exists('change_dsaccount_record_status')) {
	/**
	 *更改分销账号明细状态
	 *
	 */
	function change_dsaccount_record_status($OrderID, $Status) {
		
		$order = Order::Find($OrderID);
		
		$disAccountRecord = $order->disAccountRecord();
		$flag = true;
		if($disAccountRecord->count() >0){
			 $flag = $disAccountRecord->rawUpdate(array('Record_Status'=>$Status)); 
		}	         

	
		return $flag;

	}
}

if (!function_exists('create_distribute_acccount')) {

	/**
	 *创建分销商
	 */
	function create_distribute_acccount($rsConfig, $user_data, $LevelID, $mobile, $status = 0) {
		/*获取此店铺的配置信息*/
		$UsersID = $rsConfig['Users_ID'];
		$UserID = $user_data['User_ID'];

		//若不存在指定用户
		if (empty($user_data)) { return false; }
		
		//检测该用户是否有分销商账号
		$dis_account = Dis_Account::Multiwhere(array('Users_ID' => $UsersID, 'User_ID' => $UserID))
			          ->first();
                                                
		//若此分销账户已存在，只需将其通过审核 Is_Audit
		if (!empty($dis_account)) {
			if($dis_account->Is_Delete==1){//账号已被标识为删除状态
				$dis_account->Is_Delete=0;
				$dis_account->Is_Dongjie=0;
			}
			if ($status == 1 && $dis_account->Is_Audit == 0) {//未审核
				$dis_account->Is_Audit = 1;
			}
			$dis_account->save();
			
			if($user_data["Is_Distribute"] == 0){
				User::find($UserID)->update(array('Is_Distribute' => 1));
			}
			
			if($rsConfig["Fuxiao_Open"]==1){//复销记录更新
				$Fuxiao = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array();
				deal_distribute_fuxiao_record($Fuxiao, $UsersID, $UserID, $user_data["User_OpenID"]);
			}
			
			return true;
		}
		
		//返本相关
		$Fanben = array();
		if($rsConfig["Fanben_Open"]==1 && $rsConfig["Fanben_Type"]==0){//下级不做限制
			$Fanben = $rsConfig["Fanben_Rules"] ? json_decode($rsConfig['Fanben_Rules'],true) : array();
			if($user_data["Owner_Id"] > 0){
				deal_distribute_fanben($Fanben, $user_data["Owner_Id"]);
			}
		}

		//创建新的Dis_Account对象	
		$disAccount = new Dis_Account();
		
		$disAccount->Users_ID = $UsersID;
		$disAccount->Level_ID = $LevelID;
		$disAccount->User_ID = $UserID;
		$disAccount->Real_Name = $user_data['User_Name'] ? $user_data['User_Name'] : $user_data['User_NickName'];
		$disAccount->Shop_Name = $user_data['User_NickName'].'的店';
		$disAccount->Shop_Logo = $user_data['User_HeadImg'];
		$disAccount->balance = 0;
		$disAccount->status = 1;
		$disAccount->Is_Audit = $status;
		$disAccount->Account_Mobile = '';
		$disAccount->Account_CreateTime = time();
		$disAccount->Group_Num = 1;
		$disAccount->Fanxian_Remainder = empty($Fanben[0]) ? 0 : intval($Fanben[0]);
		$disAccount->invite_id  =  $user_data["Owner_Id"];
		
		$Dis_Path = $disAccount->generateDisPath();
		$disAccount->Dis_Path = $Dis_Path;

		//注册监听器
		DisAccountObserver::$shop_config = $rsConfig;
		Dis_Account::observe(new DisAccountObserver());
		
		begin_trans();
		$Flag  = $disAccount->save();
		
		if ($Flag) {
			if($rsConfig["Fuxiao_Open"]==1){//复销记录更新
				$Fuxiao = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array();
				deal_distribute_fuxiao_record($Fuxiao, $UsersID, $UserID, $user_data["User_OpenID"]);
			}
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('deal_distribute_fanben')){//返本处理
	function deal_distribute_fanben($Fanben, $UserID){
		global $DB1;
		$Fanben[0] = empty($Fanben[0]) ? 1 : $Fanben[0];//直推人数
		$Fanben[1] = empty($Fanben[1]) ? 0 : $Fanben[1];//返现金额
		$Fanben[2] = empty($Fanben[2]) ? 0 : $Fanben[2];//返现次数
		$rsAccount = $DB1->GetRs("distribute_account","Account_ID,Fanxian_Remainder,Fanxian_Count,balance","where User_ID=".$UserID);
		$rsUser = $DB1->GetRs("user","Users_ID,User_Money","where User_ID=".$UserID);
		if(!$rsAccount || !$rsUser){//用户不存在
			return false;
		}
		
		if($Fanben[1]<=0){//返现金额不正确
			return false;
		}
		if($Fanben[2]>0 && $rsAccount["Fanxian_Count"]>=$Fanben[2]){//返现次数已达到最大值
			return false;
		}
		
		//更新分销商账号相关信息
		if($rsAccount["Fanxian_Remainder"]==0){//第一次
			$rsAccount["Fanxian_Remainder"] = $Fanben[0];
		}
		$Remainder = $rsAccount["Fanxian_Remainder"];
		if($rsAccount["Fanxian_Remainder"]==1){
			$Remainder = $Fanben[0];
		}else{
			$Remainder = $rsAccount["Fanxian_Remainder"]-1;
		}
		
		$account = array(
			"Fanxian_Remainder"=> $Remainder
		);
		
		if($rsAccount["Fanxian_Remainder"]==1){//返现处理		
			$account["Fanxian_Count"] = $rsAccount["Fanxian_Count"]+1;
			//更新用户相关信息
			$account["balance"] = $rsAccount["balance"]+$Fanben[1];
			//更新资金流水
			$Data=array(
				'Users_ID'=>$rsUser["Users_ID"],
				'User_ID'=>$UserID,	
				'Record_Money'=>$Fanben[1],
				'Note'=>"发展直接下属达到".$Fanben[0]."个，系统返现".$Fanben[1]."元",
				'CreateTime'=>time()		
			);
			$DB1->Add('distribute_fanben_record',$Data);
		}
		$DB1->Set("distribute_account",$account,"where Account_ID=".$rsAccount["Account_ID"]);
		return true;
	}
}

if(!function_exists('is_agent')){

	/**
	 * 判定某人是否是代理商
	 * @return boolean [description]
	 */
	function is_agent($shop_config,$ds_account){
		
		//非根店
		if($ds_account['invite_id'] != 0){
			return FALSE;
		}	

	   $Dis_Agent_Type  = $shop_config['Dis_Agent_Type'];
	   
	   if($Dis_Agent_Type != 0){
		 if($Dis_Agent_Type == 1){
			//普通代理	
		 	$result = ($ds_account['Enable_Agent'] == 1)?TRUE:FALSE;
		 }else{
		 	//地区代理
		 	$where = array('Users_ID'=>$shop_config['Users_ID'],
		 		           'Account_ID'=>$ds_account['Account_ID']);

			$num = Dis_Agent_Area::Multiwhere($where)->count();
		 	$result = ($num >0)?TRUE:FALSE;
		 }

	   }else{
	   		$result = false;
	   }

	   return $result ;
	}
}


if (!function_exists('determine_dis_protitle_by_group_sales')) {

	
	/**
	 * 通过团队销售量确定用户称号
	 * @param  int $Pro_Title_Level 用户称号列表
	 * @param  float $user_Sales  团队销售额
	 * @param  int $cur_title      用户当前title级别
	 * @return int                计算后的title级别
	 */
	function determine_dis_protitle_by_group_sales($Pro_Title_Level,$Group_Sales,$cur_title) {


		$level_dropdown = array();
		$level_range_list = array();
		$level_count = count($Pro_Title_Level);
		$level_begin_sales = $Pro_Title_Level[1]['Saleroom'];
		$level_end_sales = $Pro_Title_Level[$level_count]['Saleroom'];

		//如果消费额小于等级起始消费额，1级
		if ($Group_Sales < $level_begin_sales) {
			return $cur_title;
		}

		//如果消费额大于等级结束消费额,最高级

		if ($Group_Sales >= $level_end_sales) {
			return $level_count;
		}

		//除此之外，循环确定
		foreach ($Pro_Title_Level as $key => $item) {

			if ($key != $level_count) {
				$end_cost = $Pro_Title_Level[$key + 1]['Saleroom'];
			} else {
				$end_cost = 99999999999; //用一个很大的数表示等级的终点
			}

			$level_range_list[$key] = array('begin_cost' => $item['Saleroom'], 'end_cost' => $end_cost);
		}

		foreach ($level_range_list as $key => $item) {

			if ($Group_Sales >= $item['begin_cost'] && $Group_Sales < $item['end_cost']) {

				if ($key > $cur_title) {
					return $key;
				} else {
					return $cur_title;
				}

			}
		}

	}
}

if (!function_exists('determine_dis_protitle_by_num')) {
	/**
	 * 通过团队人数确定用户级别
	 * @param  array $Pro_Title_Level 用户称号列表
	 * @param  float $user_Sales   用户晋级团队人数
	 * @param  int  $cur_title      用户当前title
	 * @return int   $cur_title              计算后的级别
	 */
	function determine_dis_protitle_by_num($Pro_Title_Level, $Group_Num, $cur_title) {

		$level_dropdown = array();
		$level_range_list = array();
		$level_count = count($Pro_Title_Level);
		$level_begin_group_num = $Pro_Title_Level[1]['Group_Num'];
		$level_end_group_num = $Pro_Title_Level[$level_count]['Group_Num'];

		//如果消费额小于等级起始消费额，1级
		if ($Group_Num < $level_begin_group_num) {
			return $cur_title;
		}

		//如果消费额大于等级结束消费额,最高级
		if ($Group_Num >= $level_end_group_num) {
			return $level_count;
		}

		//除此之外，循环确定
		foreach ($Pro_Title_Level as $key => $item) {

			if ($key != $level_count) {
				$end_group_num = $Pro_Title_Level[$key + 1]['Group_Num'];
			} else {
				$end_group_num = 99999999999; //用一个很大的数表示等级的终点
			}
			$level_range_list[$key] = array('begin_group_num' => $item['Group_Num'], 'end_group_num' => $end_group_num);
		}

		foreach ($level_range_list as $key => $item) {
			if ($Group_Num >= $item['begin_group_num'] && $Group_Num < $item['end_group_num']) {

				if ($key > $cur_title) {
					return $key;
				} else {
					return $cur_title;
				}

			}
		}

	}

}

if (!function_exists('deal_distribute_fuxiao_record')) {
	/**
	 * 启动分销商复销记录
	 */
	function deal_distribute_fuxiao_record($Fuxiao, $UsersID, $UserID, $OpenID) {	
		global $DB1;
		$dis_account = Dis_Account::Multiwhere(array('Users_ID' => $UsersID, 'User_ID' => $UserID))
			          ->first();
		
		if(!empty($dis_account)){
			$rsRecord = $DB1->GetRs("distribute_fuxiao","*","where Users_ID='".$UsersID."' and Account_ID=".$dis_account->Account_ID." and User_ID=".$UserID);
			$fxstarttime = getmonth(1);//获得下个月的一号
			if($rsRecord){//存在记录，更改记录				
				$Data = array(
					"Fuxiao_StartTime"=>$fxstarttime,
					"Fuxiao_Count"=>0,
					"Fuxiao_Status"=>0,
					"Fuxiao_SubNoticeCount"=>intval($Fuxiao[2]),
					"Fuxiao_LastNoticeTime"=>0,
					"Fuxiao_SubDenedCount"=>intval($Fuxiao[1]),
					"Fuxiao_LastDenedTime"=>0,
					
				);
				$DB1->Set("distribute_fuxiao",$Data,"where Users_ID='".$UsersID."' and Account_ID=".$dis_account->Account_ID." and User_ID=".$UserID);
			}else{//不存在记录，则增加记录
				$Data = array(
					"Users_ID"=>$UsersID,
					"User_ID"=>$UserID,
					"User_OpenID"=>$OpenID,
					"Account_ID"=>$dis_account->Account_ID,
					"Fuxiao_StartTime"=>$fxstarttime,
					"Fuxiao_SubNoticeCount"=>intval($Fuxiao[2]),
					"Fuxiao_SubDenedCount"=>intval($Fuxiao[1])
				);
				$DB1->Add("distribute_fuxiao",$Data);
			}
		}
	}
}


if (!function_exists('distribute_fuxiao_tixing')) {
	/**
	 * 启动分销商复销提醒动作，冻结前
	 */
	function distribute_fuxiao_tixing($rsConfig,$DB){
		$Fuxiao = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array();
		$UsersID = $rsConfig["Users_ID"];
		$now = time();//当前时间
		$starttime = strtotime(date('Y-m-01', time()));//当前月的第一天
		$notice_start = strtotime(date('Y-m-t', time())) - 86400*intval($Fuxiao[2]);//提醒开始时间
		$notice_end = strtotime(date('Y-m-t', time())) + 86399;//提醒结束时间
		if($now<=$notice_end && $now>=$notice_start){//提醒时间段
			$list = array();
			$counts = array();//消息发送归类
			$DB->Get("distribute_fuxiao","Record_ID,Fuxiao_LastNoticeTime,User_OpenID,Fuxiao_SubNoticeCount","where Users_ID='".$UsersID."' and Fuxiao_StartTime=".$starttime." and Fuxiao_Status=0");
			while($r = $DB->fetch_assoc()){				
				if($r["Fuxiao_LastNoticeTime"] == 0 || $r["Fuxiao_LastNoticeTime"] < strtotime(date('Y-m-d', time()))){
					$list[$r["Record_ID"]] = $r;
					$counts[$r["Fuxiao_SubNoticeCount"]][] = $r["User_OpenID"];
				}
			}
			
			if(!empty($list)){
				$sql = "Fuxiao_LastNoticeTime=".time().",Fuxiao_SubNoticeCount=Fuxiao_SubNoticeCount-1";
				$DB->Set("distribute_fuxiao",$sql,"where Record_ID in(".implode(",",array_keys($list)).")");
				//消息发送
				require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
				$weixin_message = new weixin_message($DB,$UsersID,0);
				foreach($counts as $k=>$v){
					$message = "您的分销商身份将在".$k."天后被冻结，请尽快复销。";
					if(count($v)==1){//单用户发送
						$weixin_message->sendmessage($v[0],$message);
					}else{//群发
						$weixin_message->sendmess($v,$message);
					}
				}
			}
		}
	}
}

if (!function_exists('distribute_dongjie_action')) {
	/**
	 * 启动分销商冻结动作，当前月的1号开始执行上个月的冻结动作
	 */
	function distribute_dongjie_action($rsConfig,$DB){
		$UsersID = $rsConfig["Users_ID"];
		$now = time();//当前时间
		$starttime = getmonth(0);//上月的第一天
		$notice_start = strtotime(date('Y-m-01', time()));//冻结开始时间，当前月的第一天
		if($now>=$notice_start){
			$list = array();
			$counts = array();//分销账号ID
			
			$DB->Get("distribute_fuxiao","Record_ID,Account_ID","where Users_ID='".$UsersID."' and Fuxiao_StartTime=".$starttime." and Fuxiao_Status=0");
			while($r = $DB->fetch_assoc()){	
				$list[] = $r["Record_ID"];
				$counts[] = $r["Account_ID"];
			}
			
			if(!empty($list)){
				$DB->Set("distribute_fuxiao","Fuxiao_Status=1","where Record_ID in(".implode(",",$list).")");
				$DB->Set("distribute_account","Is_Dongjie=1","where Account_ID in(".implode(",",$counts).")");
			}
		}
	}
}

if (!function_exists('distribute_dongjie_tixing')) {
	/**
	 * 启动分销商复销提醒动作，冻结后
	 */
	function distribute_dongjie_tixing($rsConfig,$DB){
		$Fuxiao = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array();
		$UsersID = $rsConfig["Users_ID"];
		$now = time();//当前时间
		$starttime = getmonth(0);//上月的第一天
		$notice_start = strtotime(date('Y-m-01', time()));//提醒开始时间，当前月的第一天
		$notice_end = $notice_start + 86400*intval($Fuxiao[1]);//提醒结束时间
		if($now<=$notice_end && $now>=$notice_start){//提醒时间段
			$list = array();
			$counts = array();//消息发送归类
			$DB->Get("distribute_fuxiao","Record_ID,Fuxiao_LastDenedTime,User_OpenID,Fuxiao_SubDenedCount","where Users_ID='".$UsersID."' and Fuxiao_StartTime=".$starttime." and Fuxiao_Status=1");
			while($r = $DB->fetch_assoc()){				
				if($r["Fuxiao_LastDenedTime"] == 0 || $r["Fuxiao_LastDenedTime"] < strtotime(date('Y-m-d', time()))){
					$list[$r["Record_ID"]] = $r;
					$counts[$r["Fuxiao_SubDenedCount"]][] = $r["User_OpenID"];
				}
			}
			
			if(!empty($list)){
				$sql = "Fuxiao_LastDenedTime=".time().",Fuxiao_SubDenedCount=Fuxiao_SubDenedCount-1";
				$DB->Set("distribute_fuxiao",$sql,"where Record_ID in(".implode(",",array_keys($list)).")");
				//消息发送
				require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
				$weixin_message = new weixin_message($DB,$UsersID,0);	
				foreach($counts as $k=>$v){
					$message = "您的分销商身份已被冻结，将在".$k."天后被删除，请尽快复销。";
					if(count($v)==1){//单用户发送
						$weixin_message->sendmessage($v[0],$message);
					}else{//群发
						$weixin_message->sendmess($v,$message);
					}
				}
			}
		}
	}
}

if (!function_exists('distribute_delete_action')) {
	/**
	 * 启动分销商删除动作，当前月的冻结期过后的第一天开始执行上个月的删除动作
	 */
	function distribute_delete_action($rsConfig,$DB){
		$Fuxiao = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array();
		$UsersID = $rsConfig["Users_ID"];
		$now = time();//当前时间
		$starttime = getmonth(0);//上月的第一天
		$notice_start = strtotime(date('Y-m-01', time()))+86400*$Fuxiao[1];//冻结开始时间，当前月的第一天
		if($now>=$notice_start){
			$list = array();
			$counts = array();//分销账号ID
			$usersids = array();
			
			$DB->Get("distribute_fuxiao","Record_ID,Account_ID,User_ID","where Users_ID='".$UsersID."' and Fuxiao_StartTime=".$starttime." and Fuxiao_Status=1");
			while($r = $DB->fetch_assoc()){	
				$list[] = $r["Record_ID"];
				$counts[] = $r["Account_ID"];
				$usersids[] = $r["User_ID"];
			}
			
			if(!empty($list)){
				$DB->Set("distribute_fuxiao","Fuxiao_Status=2","where Record_ID in(".implode(",",$list).")");
				$DB->Set("distribute_account","Is_Delete=1","where Account_ID in(".implode(",",$counts).")");
				$DB->Set("user","Is_Distribute=0","where User_ID in(".implode(",",$usersids).")");
			}
		}
	}
}

if (!function_exists('distribute_fuxiao_return_action')) {
	/**
	 * 启动分销商复销成功
	 */
	function distribute_fuxiao_return_action($DB, $Rules, $rsAccount,$OpenID){
		$Fuxiao = $Rules ? json_decode($Rules,true) : array();
		$UsersID = $rsAccount["Users_ID"];
		$UserID = $rsAccount["User_ID"];
		$order_endtime = time()+3600;
		$enabled = 0;
		//账号冻结后
		if($rsAccount["Is_Dongjie"]==1){
			$order_starttime = getmonth(0);//上个月的第一天			
		}else{
			$order_starttime = strtotime(date('Y-m-01',time()));//当月的第一天
		}
		
		$order = $DB->GetRs("user_order","SUM(Order_TotalPrice) as money","where Users_ID='".$UsersID."' and User_ID=".$UserID." and Order_Status>=2 and Order_CreateTime>=".$order_starttime." and Order_CreateTime<=".$order_endtime);//用户在时间段中的总消费，付款之后
		
		$total = empty($order["money"]) ? 0 : $order["money"];
		if($total >= $Fuxiao[0]){//启动复销
			if($rsAccount["Is_Dongjie"]==1){//解冻
				$DB->Set("distribute_account",array("Is_Dongjie"=>0),"where Account_ID=".$rsAccount["Account_ID"]);
			}
			//更改记录
			$condition = "where Users_ID='".$UsersID."' and Account_ID=".$rsAccount["Account_ID"]." and User_ID=".$UserID." and Fuxiao_StartTime=".$order_starttime;
			$rsRecord = $DB->GetRs("distribute_fuxiao","Record_ID,Fuxiao_Count",$condition);
			
			if($rsRecord){//存在记录，更改记录
				$Data = array(
					"Fuxiao_StartTime"=>$rsAccount["Is_Dongjie"]==1 ? strtotime(date('Y-m-01', time())) : getmonth(1),//当前月的第一天
					"Fuxiao_Count"=>$rsRecord["Fuxiao_Count"]+1,
					"Fuxiao_Status"=>0,
					"Fuxiao_SubNoticeCount"=>intval($Fuxiao[2]),
					"Fuxiao_LastNoticeTime"=>0,
					"Fuxiao_SubDenedCount"=>intval($Fuxiao[1]),
					"Fuxiao_LastDenedTime"=>0,
						
				);
				$DB->Set("distribute_fuxiao",$Data,"where Account_ID=".$rsRecord["Record_ID"]);
			}else{//不存在记录，则增加记录
				$Data = array(
					"Users_ID"=>$UsersID,
					"User_ID"=>$UserID,
					"User_OpenID"=>$OpenID,
					"Fuxiao_Count"=>1,
					"Account_ID"=>$rsAccount["Account_ID"],
					"Fuxiao_StartTime"=>$rsAccount["Is_Dongjie"]==1 ? strtotime(date('Y-m-01', time())) : getmonth(1),
					"Fuxiao_SubNoticeCount"=>intval($Fuxiao[2]),
					"Fuxiao_SubDenedCount"=>intval($Fuxiao[1])
				);
				$DB->Add("distribute_fuxiao",$Data);
			}			
		}		
	}
}

if (!function_exists('get_filter_cart_list')) {
	/**
	 * 计算产品网站提成(即产品利润：佣金发放+网站所得)，重组购物车
	 */
	function get_filter_cart_list($DB, $cartlist){
		$BizList = array();
		$bizids = array_keys($cartlist);
		$DB->Get("biz","Finance_Type,Finance_Rate,Biz_ID","where Biz_ID in(".implode(",",$bizids).")");
		while($r = $DB->fetch_assoc()){
			$BizList[$r["Biz_ID"]] = $r;
		}
		foreach($cartlist as $Biz_ID => $Biz_CartList){//第一次循环 按商家
			$finance_type_biz = $BizList[$Biz_ID]["Finance_Type"];
			$finance_type_products = 0;
			foreach($Biz_CartList as $Products_ID => $Products){//第二次循环 按商品
				if($finance_type_biz ==1){//按单个商品具体配置
					$rsProducts = $DB->GetRs("shop_products","Products_FinanceType,Products_PriceS,Products_FinanceRate","where Products_ID=".$Products_ID);
					$finance_type_products = $rsProducts["Products_FinanceType"];
				}
				foreach($Products as $Cart_ID => $Cart_Info){
					if($finance_type_biz==0){//商家统一使用比例结算
						$cartlist[$Biz_ID][$Products_ID][$Cart_ID]["ProductsProfit"] = $Cart_Info["ProductsPriceX"] * $BizList[$Biz_ID]["Finance_Rate"] / 100;
					}else{
						if($finance_type_products==0){//产品使用比例结算
							$cartlist[$Biz_ID][$Products_ID][$Cart_ID]["ProductsProfit"] = $Cart_Info["ProductsPriceX"] * $rsProducts["Products_FinanceRate"] / 100;
						}else{//产品供货价结算
							$ProductsS = empty($Cart_Info["spec_list"]) ? $rsProducts["Products_PriceS"] : get_products_supply_price($DB,$Products_ID,$rsProducts["Products_PriceS"],$Cart_Info["spec_list"]);
							$cartlist[$Biz_ID][$Products_ID][$Cart_ID]["ProductsProfit"] = $Cart_Info["ProductsPriceX"] - $ProductsS;
						}
					}
				}
			}
		} 
		return $cartlist;
	}
}

/*if (!function_exists('get_products_supply_price')) {//获取供货价
	function get_products_supply_price($DB,$ProductsID,$ProductsPriceS,$spec_list){
		if(empty($spec_list)){
			return $ProductsPriceS;
		}else{
			$DB->Get("shop_products_attr","Supply_Price","where Products_ID=".$ProductsID." and Product_Attr_ID in(".$spec_list.")");
			while($r = $DB->fetch_assoc()){
				print_R($r["Supply_Price"]);die;
				$ProductsPriceS = $ProductsPriceS + number_format($r["Supply_Price"],2,'.','');
			}
		}
		return $ProductsPriceS;
	}
}*/
/*edit20160528--start--*/
if (!function_exists('get_products_supply_price')) {//获取供货价
	function get_products_supply_price($DB,$ProductsID,$ProductsPriceS,$spec_list){
		if(empty($spec_list)){
			return $ProductsPriceS;
		}else{
			if(!is_array($spec_list)){
				$spec_list_arr = explode(',',$spec_list);
			}
			$DB->Get("shop_products_attr","Supply_Price","where Products_ID=".$ProductsID." and Product_Attr_ID in(".$spec_list.")");
			while($r = $DB->fetch_assoc()){
				
				$ProductsPriceS = $ProductsPriceS + number_format($r["Supply_Price"],2,'.','');
			}
		}
		return $ProductsPriceS;
	}
}
/*edit20160528--end--*/

if(!function_exists('deal_user_to_distribute')){
	function deal_user_to_distribute($UsersID,$shop_config,$user_data,$level_data){
		if(empty($user_data)){//用户不存在，返回设置值
			$first_level_data = reset($level_data);
			return $first_level_data['Level_LimitType']==3 ? true : false;
		}else{
			$LevelID = get_user_distribute_level($shop_config,$level_data,$user_data['User_ID']);//获得该用户应得的分销级别
			if($user_data['Is_Distribute']>0){//用户已经是分销商，更改分销级别				
				$dis_account = Dis_Account::Multiwhere(array('Users_ID' => $UsersID, 'User_ID' => $user_data['User_ID']))
			          ->first();
				if(empty($dis_account->Account_ID)){//某种原因分销商账号不存在
					if($LevelID>0){
						$flag = create_distribute_acccount($shop_config, $user_data, $LevelID, '', 1);
						return $flag;
					}else{
						global $DB1;
						$DB->Set('user','Is_Distribute=0','where User_ID='.$user_data['User_ID']);
						return false;
					}
				}else{
					if($dis_account->Level_ID<$LevelID){//级别晋升
						$dis_account->Level_ID = $LevelID;
						$dis_account->save();
					}
					return true;
				}				
			}else{//用户还不是分销商，成为分销商
				if($LevelID>0){
					$flag = create_distribute_acccount($shop_config, $user_data, $LevelID, '', 1);
					return $flag;
				}else{
					return false;
				}
			}
		}
	}
}

if (!function_exists('pre_add_distribute_account')) {
	function pre_add_distribute_account($shop_config, $UsersID) {
		
		$error_msg = '';
		
		if (!empty($_SESSION[$UsersID . 'User_ID'])) {
			$User_ID = $_SESSION[$UsersID . 'User_ID'];
			$user = User::Multiwhere(array('Users_ID' => $UsersID, 'User_ID' => $User_ID))
				->first()
				->toArray();

			$owner["id"] = $user["Owner_Id"];

			if ($user) {
				$truename = $user["User_Name"] ? $user["User_Name"] : ($user["User_NickName"] ? $user["User_NickName"] : '真实姓名');

				if ($user["Is_Distribute"] == 0) {

					switch ($shop_config["Distribute_Type"]) {
						case '0': //自动成为分销商
																				
							$flag = create_distribute_acccount( $shop_config, $_SESSION[$UsersID . 'User_ID'], $truename, $owner, '', 1);
							$salesman = new Salesman($UsersID, $_SESSION[$UsersID."User_ID"]);
							$salesman->up_salesman();
							$error_msg = $flag ? 'OK' : '会员自动成为分销商失败';
							break;

						case '1': //积分限制
							if ($user["User_TotalIntegral"] >= $shop_config["Distribute_Limit"]) {
								$flag = create_distribute_acccount( $shop_config, $_SESSION[$UsersID . 'User_ID'], $truename, $owner, '', 1);
								$salesman = new Salesman($UsersID, $_SESSION[$UsersID."User_ID"]);
								$salesman->up_salesman();
								$error_msg = $flag ? 'OK' : '会员自动成为分销商失败';
							} else {
								$error_msg = '1';
							}
							break;
						case '2': //消费金额
							if ($user["User_Cost"] >= $shop_config["Distribute_Limit"]) {
								$flag = create_distribute_acccount($shop_config,$_SESSION[$UsersID . 'User_ID'], $truename, $owner, '', 1);
								$salesman = new Salesman($UsersID, $_SESSION[$UsersID."User_ID"]);
								$salesman->up_salesman();
								$error_msg = $flag ? 'OK' : '会员自动成为分销商失败';
							} else {
								$error_msg = '2';
							}
							break;
						case '3':
							$error_msg = '3';
							break;
						case '4':
							$error_msg = '4';
							break;
					}
				} else {
					$error_msg = 'OK';
				}
			} else {
				$error_msg = "会员不存在，请先清除缓存";
			}
		}
		return $error_msg;
	}
}
if(!function_exists('get_user_distribute_level')){//根据消费额获得分销商级别（总消费额），用于生成分销商或更新
	function get_user_distribute_level($shop_config,$level_data,$UserID){
		$first_level_data = reset($level_data);
		$LevelID = $first_level_data['Level_LimitType']==3 ? $first_level_data['Level_ID'] : 0;
		if($shop_config['Distribute_Type']==1){//消费额门槛
			$level_result = array();
			$level_array = array_reverse($level_data,true);
			foreach($level_array as $id=>$value){
				if($value['Level_LimitType']<>1) continue;
				$arr_temp = explode('|',$value['Level_LimitValue']);
				if($arr_temp[0]==0){//商城总消费额
					$level_result[$id] = array(
						'id'=>$value['Level_ID'],
						'money'=>empty($arr_temp[1]) ? 	0 : $arr_temp[1],
						'status'=>$arr_temp[2]
					);
				}
			}
			if(!empty($level_result)){
				global $DB1;
				$r = $DB1->GetRs('user_order','SUM(Order_TotalPrice) as money','where User_ID='.$UserID.' and Order_Status>=2');
				$cost_status_2 = empty($r['money']) ? 0 : $r['money'];
				
				$r = $DB1->GetRs('user_order','SUM(Order_TotalPrice) as money','where User_ID='.$UserID.' and Order_Status=4');
				$cost_status_4 = empty($r['money']) ? 0 : $r['money'];
				foreach($level_result as $key=>$item){
					if(intval($item['status'])==2 && $item['money']<=$cost_status_2){
						$LevelID = $item['id'];
						break;
					}
					if(intval($item['status'])==4 && $item['money']<=$cost_status_4){
						$LevelID = $item['id'];
						break;
					}
				}
			}
		}
		return $LevelID;
	}
}

if(!function_exists('get_user_distribute_level_cost')){//根据消费额获得分销商级别（一次性消费，状态为已付款计入）
	function get_user_distribute_level_cost($level_data,$shop_config,$cost){
		$first_level_data = reset($level_data);
		$LevelID = $first_level_data['Level_LimitType']==3 ? $first_level_data['Level_ID'] : 0;
		if($shop_config['Distribute_Type']==1){//消费额门槛
			$level_result = array();
			$level_array = array_reverse($level_data,true);
			foreach($level_array as $id=>$value){
				if($value['Level_LimitType']<>1) continue;
				$arr_temp = explode('|',$value['Level_LimitValue']);
				if($arr_temp[0]==1 && $arr_temp[2]==2){//一次性消费
					$level_result[$id] = array(
						'id'=>$value['Level_ID'],
						'money'=>empty($arr_temp[1]) ? 	0 : $arr_temp[1]
					);
				}
			}
			
			if(!empty($level_result)){
				foreach($level_result as $key=>$item){
					if($item['money']<=$cost){
						$LevelID = $item['id'];
						break;
					}
				}
			}
		}
		return $LevelID;
	}
}

if(!function_exists('get_user_distribute_level_buy')){//根据购买获得分销商级别
	function get_user_distribute_level_buy($level_data,$shop_config,$productids){
		$first_level_data = reset($level_data);
		$LevelID = $first_level_data['Level_LimitType']==3 ? $first_level_data['Level_ID'] : 0;
		if($shop_config['Distribute_Type']==2){//购买商品
			$level_array = array_reverse($level_data,true);
			foreach($level_array as $id=>$value){
				if($value['Level_LimitType']<>2) continue;
				$arr_temp = explode('|',$value['Level_LimitValue']);
				if($arr_temp[0]==0){//购买任意商品
					$LevelID = $value['Level_ID'];
					break;
				}else{
					$pids = $arr_temp[1] ? explode(',',$arr_temp[1]) : array();
					$array_intersect = array_intersect($productids,$pids);
					if(!empty($array_intersect)){
						$LevelID = $value['Level_ID'];
						break;
					}
				}
			}
		}
		return $LevelID;
	}
}

if(!function_exists('get_user_distribute_level_confirmcost')){//根据消费额获得分销商级别（一次性消费，状态为已完成计入）
	function get_user_distribute_level_confirmcost($level_data,$shop_config,$cost){
		$first_level_data = reset($level_data);
		$LevelID = $first_level_data['Level_LimitType']==3 ? $first_level_data['Level_ID'] : 0;
		if($shop_config['Distribute_Type']==1){//消费额门槛
			$level_result = array();
			$level_array = array_reverse($level_data,true);
			foreach($level_array as $id=>$value){
				if($value['Level_LimitType']<>1) continue;
				$arr_temp = explode('|',$value['Level_LimitValue']);
				if($arr_temp[0]==1 && $arr_temp[2]==4){//一次性消费
					$level_result[$id] = array(
						'id'=>$value['Level_ID'],
						'money'=>empty($arr_temp[1]) ? 	0 : $arr_temp[1]
					);
				}
			}
			
			if(!empty($level_result)){
				foreach($level_result as $key=>$item){
					if($item['money']<=$cost){
						$LevelID = $item['id'];
						break;
					}
				}
			}
		}
		return $LevelID;
	}
}

//获取获得分销佣金的会员数组
if(!function_exists('get_distribute_balance_userids')){
	function get_distribute_balance_userids($UsersID,$BuyerID,$userids,$distribute_bonus,$type=0){
		global $DB1;
		$level_data = get_dis_level($DB1,$UsersID);
		if(empty($userids)){
			return array();
		}
		
		//获得每个分销商级别
		$account_list = Dis_Account::whereIn('User_ID',$userids)
		    ->get(array('Level_ID','User_ID'))
			->map(function($account){
				return $account->toArray();
			})->all();
		$accounts = array();
		foreach($account_list as $account){
			$accounts[$account['User_ID']] = array(
				'limit'=>empty($level_data[$account['Level_ID']]) ? array() : json_decode($level_data[$account['Level_ID']]['Level_PeopleLimit'], true),
				'bonus'=>empty($distribute_bonus[$account['Level_ID']]) ? array() : $distribute_bonus[$account['Level_ID']]
			);
		}
		
		//获得每个分销商得到的佣金记录
		$record_list = array();
		if($type==0){//普通商品
			$sql = 'select a.User_ID,a.level,r.Buyer_ID from distribute_account_record as a left join distribute_record as r on r.Record_ID=a.Ds_Record_ID where a.User_ID in('.(implode(',',$userids)).')';
		}else{//购买分销级别
			$sql = 'select User_ID,level,Buyer_ID from distribute_order_record where User_ID in('.(implode(',',$userids)).')';
		}
		
		$DB1->query($sql);
		while($r = $DB1->fetch_assoc()){
			if(empty($record_list[$r['User_ID']])){
				$record_list[$r['User_ID']][$r['level']][] = $r['Buyer_ID'];
			}elseif(empty($record_list[$r['User_ID']][$r['level']])){
				$record_list[$r['User_ID']][$r['level']][] = $r['Buyer_ID'];
			}elseif(!in_array($r['Buyer_ID'],$record_list[$r['User_ID']][$r['level']])){
				$record_list[$r['User_ID']][$r['level']][] = $r['Buyer_ID'];
			}
		}
		
		
		//循环数组筛选
		$result = array();
		
		foreach($userids as $key=>$value){
			//该用户在这条线上的级别为$key+1
			$lid = $key+1;
			if(empty($accounts[$value]['limit'])){//不存在该分销商级别人数限制，忽略
				$result[$value] = array('status'=>0,'msg'=>'你的分销商级别不存在');
				continue;
			}
			
			if(empty($accounts[$value]['bonus'])){//不存在该分销商级别佣金设置，忽略
				$result[$value] = array('status'=>0,'msg'=>'该分销商级别佣金未设置');
				continue;
			}
			
			if(!isset($accounts[$value]['limit'][$lid])){//限制人数没有到此级别，忽略
				$result[$value] = array('status'=>0,'msg'=>'分销商级别设置有误，该分销级别的限制人数未设置');
				continue;
			}
			
			if(!isset($accounts[$value]['bonus'][$key])){//限制人数没有到此级别，忽略
				$result[$value] = array('status'=>0,'msg'=>'分销佣金设置有误，该分销级别的分销佣金未设置');
				continue;
			}
			
			if($accounts[$value]['limit'][$lid]==-1){//禁止获得该级别佣金，忽略
				$result[$value] = array('status'=>0,'msg'=>'你的分销商级别禁止获得'.$lid.'级佣金');
				continue;
			}
			
			if(empty($record_list[$value])){//该用户未获得过佣金
				$result[$value] = array('status'=>1,'bonus'=>$accounts[$value]['bonus'][$key]);
				continue;
			}
			
			if(empty($record_list[$value][$lid])){//该用户未获得过该级别佣金
				$result[$value] = array('status'=>1,'bonus'=>$accounts[$value]['bonus'][$key]);
				continue;
			}
			
			if($accounts[$value]['limit'][$lid]==0){//该级别佣金不限制
				$result[$value] = array('status'=>1,'bonus'=>$accounts[$value]['bonus'][$key]);
				continue;
			}
			
			if(in_array($BuyerID,$record_list[$value][$lid])){//已经获得此人的佣金
				$result[$value] = array('status'=>1,'bonus'=>$accounts[$value]['bonus'][$key]);
				continue;
			}
			
			if($accounts[$value]['limit'][$lid]>0 && count($record_list[$value][$lid])>=$accounts[$value]['limit'][$lid]){//获得过的该级别佣金未达到最大值
				$result[$value] = array('status'=>0,'msg'=>'你获得的'.$lid.'级佣金人数级已达到最大值');
				continue;
			}
			
			if($accounts[$value]['limit'][$lid]>0 && count($record_list[$value][$lid])<$accounts[$value]['limit'][$lid]){//获得过的该级别佣金未达到最大值
				$result[$value] = array('status'=>1,'bonus'=>$accounts[$value]['bonus'][$key]);
				continue;
			}
		}
		
		return $result;
	}
}

if(!function_exists('get_user_distribute_level_upgrade')){//根据购买获得分销商升级级别
	function get_user_distribute_level_upgrade($level_data,$shop_config,$productids){
		$LevelID = 0;
		$level_array = array_reverse($level_data,true);
		foreach($level_array as $id=>$value){
			if($value['Level_UpdateType']<>1) continue;
			$pids = $value['Level_UpdateValue'] ? explode(',',$value['Level_UpdateValue']) : array();
			$array_intersect = array_intersect($productids,$pids);
			if(!empty($array_intersect)){
				$LevelID = $value['Level_ID'];
				break;
			}
		}
		return $LevelID;
	}
}