<?php
//网中网分销处理helper
if (!function_exists('get_owner')) {
	/*获取此店主的信息*/
	function get_owner($rsConfig, $UsersID) {
		if (empty($_SESSION[$UsersID . 'User_ID'])) {
			$owner = get_owner_by_url($UsersID); //用户不登录
		} else {
			$owner = get_owner_by_sql($UsersID); //用户登录
		}
	
		//如果不允许会员自定义店名
		if ($rsConfig['distribute_customize'] == 0) {
			$owner['shop_name'] = $rsConfig['shopname'];
			$owner['shop_logo'] = !empty($rsConfig['logo']) ? $rsConfig['logo'] : '/static/api/images/user/face.jpg';
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
			$shop_name = $ownerAccount['shop_name'];
			$shop_logo = !empty($ownerAccount['shop_logo']) ? $ownerAccount['shop_logo'] : '/static/api/images/user/face.jpg';
			$shop_announce = $ownerAccount['shop_announce'];
			
			$owner = array('id' => $owner_id, 'shop_name' => $shop_name, 'shop_logo' => $shop_logo, 'shop_announce' => $shop_announce, 'root_id'=>get_owner_rootid($ownerAccount));
		} else {
			$owner = array('id' => 0, 'root_id'=>0);
		}
		return $owner;
	}
}

if (!function_exists('get_owner_by_sql')) {
	//通过url获得ownerid
	function get_owner_by_sql($UsersID) {
		$User_ID = $_SESSION[$UsersID . 'User_ID'];
		$user = model('user')->where(array('Users_ID' => $UsersID, 'User_ID' => $User_ID))->find();
		if (empty($user)) {
			echo '发生不可预知的错误，请联系管理员';
			exit();
		}
						
		if ($user['is_distribute'] == 1) {
			$owner_id = $_SESSION[$UsersID . 'User_ID'];
			$ownerAccount = get_dsaccount_by_id($UsersID, $owner_id);
			
			//若登录用户的分销身份已审核通过,则店主就是他自己
			//若登录用户的分销身份未审核通过,则店主仍是他的推荐人
			if ($ownerAccount['is_audit'] != 1) {
				$owner_id = $user['owner_id'];
				if ($owner_id > 0) {
					$ownerAccount = get_dsaccount_by_id($UsersID, $user['owner_id']);
					if (empty($ownerAccount)) {
						echo '不存在这个店主,您的推荐人已被删除!!!';
			            exit();
					}
				} else {
					$ownerAccount = array(
						'shop_name' => '',
						'shop_logo' => '',
						'shop_announce' => '',
						'user_id'=>0,
					);
				}
			}
		} else {
			$owner_id = $user['owner_id'];
			$ownerAccount = get_dsaccount_by_id($UsersID, $owner_id);
		}
		$shop_name = $ownerAccount['shop_name'];
		$shop_logo = !empty($ownerAccount['shop_logo']) ? $ownerAccount['shop_logo'] : '/static/api/images/user/face.jpg';
		$shop_announce = $ownerAccount['shop_announce'];

		$owner = array('id' => $owner_id, 'shop_name' => $shop_name, 'shop_logo' => $shop_logo, 'shop_announce' => $shop_announce, 'root_id' => get_owner_rootid($ownerAccount));
		
		return $owner;
	}
}

//获取根节点
if(!function_exists('get_owner_rootid')){
	function get_owner_rootid($dsAccount) {
		
		if(!empty($dsAccount['fulldispath'])) {
			   //若全路径中含逗号，则是逗号前第一个数字,
			   //若全路径不含逗号，只是一个数字,则RootID就是这个数字
			   
			   $res  = strstr($dsAccount['fulldispath'], ',', TRUE);
			   $rootID = $res ? $res : $dsAccount['fulldispath'];
			}else {	
			   $rootID = $dsAccount['user_id'];	
		}
		return $rootID;
	}
}

if (!function_exists('get_dsaccount_by_id')) {
	function get_dsaccount_by_id($UsersID, $User_ID) {
		if($User_ID == 0){			
			$account = array('id' => 0,'user_id'=>0, 'shop_name' => '', 'shop_logo' => '', 'shop_announce' => '','root_id'=>0);
		}else{

            $account_model = model('distribute_account', 'common');

			$account = $account_model->where(['Users_ID' => $UsersID, 'User_ID' => $User_ID])->find();
			if(empty($account)){
				echo '不存在这个店主';
				exit();
			}
            $account['fullDisPath'] = $account_model->getFullDisPath($account);					  
		}
		return $account;
	}
}

if (!function_exists('add_distribute_record')) {
	//增加分销记录
	function add_distribute_record($UsersID, $OwnerID, $Product_Price, $ProductID, $Qty, $OrderID, $Profit, $CartID) {
		//此产品利润为零,不处理
		if ($Profit <= 0) {
			return false;
		}

		$Product = model('shop_products')->field('Products_ID,Products_PriceX,Products_Distributes,Biz_ID,Products_Name,commission_ratio,platForm_Income_Reward')->where(array('Users_ID' => $UsersID, 'Products_ID' => $ProductID))->find();

		
		if($Product['commission_ratio'] <= 0) {//佣金比例
			return false;
		}
		
		$Product['Products_Profit'] = $Profit;
		
		//佣金比例数组、重新组装

                $Products_Distributes = $Product['Products_Distributes'] ? json_decode($Product['Products_Distributes'], true) : array();
		$distribute_bonus_list = array();
		/*foreach ($Products_Distributes as $Key => $item) {
			$distribute_bonus_list[$Key] = $Profit * $Product['commission_ratio'] * $item / 10000;
		}*/
                if(!empty($Products_Distributes)){
                    foreach ($Products_Distributes as $Key => $item) {
                        foreach($item as $k=>$v){
                            $distribute_bonus_list[$Key][$k] = $Product['Products_Profit']*$Product['platForm_Income_Reward']* $Product['commission_ratio'] * $v / 1000000;
                        }
                    }
		}

		if(empty($distribute_bonus_list)){
			return false;
		}

		$Product['Distribute_List'] = $distribute_bonus_list;//佣金列表，已经计算出具体金额	
		$Product['Products_Price'] = $Product_Price;
		
		$Product['CartID'] = $CartID;

		//增加分销记录

		//$dis_record_mode = model('shop_distribute_account_record');
                $dis_record_mode = model('distribute_record');

		$dis_record['Users_ID'] = $UsersID;
		$dis_record['Buyer_ID'] = $_SESSION[$UsersID . 'User_ID'];
		$dis_record['Owner_ID'] = $OwnerID;
		$dis_record['Order_ID'] = $OrderID;
		$dis_record['Product_ID'] = $ProductID;
		$dis_record['Product_Price'] = $Product_Price;
		$dis_record['Qty'] = $Qty;
		$dis_record['Record_CreateTime'] = time();
		$dis_record['status'] = 0;
		$dis_record['Biz_ID'] = $Product['Biz_ID'];
		$dis_record['Record_Type'] = 0;
		

		$insert_id = $dis_record_mode->insert($dis_record); 
                $shop_config = model('shop_config')->field('*')->where(array('Users_ID'=>$UsersID))->find();
                $dis_config = model('distribute_config')->field('*')->where(array('Users_ID'=>$UsersID))->find();
                $shop_config = array_merge($shop_config,$dis_config);

		//为分销记录model设置观察者，
		$DisRecordObserver = new \shop\logic\DisRecordObserver();
		$DisRecordObserver->shop_config = $shop_config;
		$DisRecordObserver->Product = $Product;
		$DisRecordObserver->Qty = $Qty;
		$DisRecordObserver->Order_ID = $OrderID;
		$DisRecordObserver->created($insert_id);
	}
}

if (!function_exists('change_dsaccount_record_status')) {
	/**
	 *更改分销账号明细状态
	 *
	 */
	function change_dsaccount_record_status($OrderID, $Status) {

	    $shop_distribute_account_record = model('distribute_account_record');
		$disAccountRecords = model()->query('SELECT * FROM distribute_account_record as dis_account_record RIGHT JOIN distribute_record as dis_record ON dis_account_record.Ds_Record_ID = dis_record.Record_ID LEFT JOIN user_order AS orders ON orders.Order_ID = dis_record.Order_ID WHERE orders.Order_ID = ' . $OrderID, 'SELECT');
		$flag = true;
		if($disAccountRecords) {
		    $record_ids = array();
			$dis_records = model('distribute_record')->where(array('Order_ID'=>$OrderID))->select();

			if($dis_records){
				foreach($dis_records as $k => $v) {
					$record_ids[] = $v['record_id'];
				}
			}
			if($record_ids){
			    $account_record_count = $shop_distribute_account_record->where(array('Ds_Record_ID'=>$record_ids))->total();
				if($account_record_count > 0){
			        $flag = model('distribute_account_record')->where(array('Ds_Record_ID'=>$record_ids))->update(array('Record_Status'=>$Status));

				}
			}
		}
		return $flag;
	}
}

if (!function_exists('create_distribute_acccount')) {

	/**
	 *创建分销商
	 */

	function create_distribute_acccount($rsConfig, $UserID, $Real_Name, $ownerid, $Account_Mobile, $status = 0) {

		/*获取此店铺的配置信息*/
		$UsersID = $rsConfig['users_id'];
		$user_model = model('user');
		$user = $user_model->field('*')->where(array('User_ID'=>$UserID))->find();
		//若不存在指定用户
		if (empty($user)) { return false; }

                $dis_account_model = model('distribute_account', 'common');

		
		//返本相关
		$Fanben = array();
		if($rsConfig['fanben_open'] == 1){
			$Fanben = $rsConfig['fanben_rules'] ? json_decode($rsConfig['fanben_rules'], true) : array();
			if($ownerid > 0){
				deal_distribute_fanben($Fanben, $ownerid);
			}
		}

		//创建分销账号
		$Account_Data = array(
		    'Users_ID'=>$UsersID,
			'User_ID'=>$UserID,
			//'Level_ID'=>$LevelID,

			'Real_Name'=>$Real_Name,
			'Shop_Name'=>$user['User_NickName'] . '的店',
			'Shop_Logo'=>$user['User_HeadImg'],
			'balance'=>0,
			'status'=>1,
			'Is_Audit'=>$status,
			'Account_Mobile'=> $Account_Mobile,
			'Account_CreateTime'=>time(),
			'Group_Num'=>1,
			'Fanxian_Remainder'=>empty($Fanben[0]) ? 0 : intval($Fanben[0]),
			'invite_id'=>!empty($ownerid) ? $ownerid : 0,
		);
		
		$Dis_Path = generateDisPath($UserID, $Account_Data['invite_id']);
		$Account_Data['Dis_Path'] = $Dis_Path;
		
		$insert_id = $dis_account_model->insert($Account_Data);
		
		
		$dis_account = $dis_account_model->field('*')->where(array('Users_ID' => $UsersID, 'User_ID' => $UserID, 'Account_ID'=>$insert_id))->find();

		//若此分销账户已存在，只需将其通过审核 Is_Audit
		
		if (!empty($dis_account)) {
			if($dis_account['Is_Delete'] == 1) {
				$dis_account['Is_Delete'] = 0;
				$dis_account['Is_Dongjie'] = 0;
			}
			if ($status == 1 && $dis_account['Is_Audit'] == 0) {
				$dis_account['Is_Audit'] = 1;
			}
			
			$dis_account_model->where(array('Account_ID'=>$dis_account['Account_ID']))->update($dis_account);
			
			if($user['Is_Distribute'] == 0) {
				$user_model->where(array('User_ID' => $UserID))->update(array('Is_Distribute' => 1));
			}
			if($rsConfig['fuxiao_open'] == 1) {//复销记录更新
				$Fuxiao = $rsConfig['fuxiao_rules'] ? json_decode($rsConfig['fuxiao_rules'], true) : array();
				deal_distribute_fuxiao_record($Fuxiao, $UsersID, $UserID, $user['User_OpenID']);
			}
			return true;
		}
		
		//爵位晋级相关处理
		$DisAccountObserver = new \shop\logic\DisAccountObserver();
		$DisAccountObserver->shop_config = $rsConfig;
		$DisAccountObserver->created($dis_account);
		
		if ($Flag) {
			if($user['Is_Distribute'] == 0) {
				$user_model->where(array('User_ID' => $UserID))->update(array('Is_Distribute' => 1));
			}
			if($rsConfig['fuxiao_open'] == 1) {//复销记录更新
				$Fuxiao = $rsConfig['fuxiao_rules'] ? json_decode($rsConfig['fuxiao_rules'], true) : array();
				deal_distribute_fuxiao_record($Fuxiao, $UsersID, $UserID, $user['User_OpenID']);
			}
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('deal_distribute_fanben')) {//返本处理
	function deal_distribute_fanben($Fanben, $UserID){
		$Fanben[0] = empty($Fanben[0]) ? 1 : $Fanben[0];//直推人数
		$Fanben[1] = empty($Fanben[1]) ? 0 : $Fanben[1];//返现金额
		$Fanben[2] = empty($Fanben[2]) ? 0 : $Fanben[2];//返现次数

		$dis_account_model = model('distribute_account');
		$user_model = model('user');
		$rsAccount = $dis_account_model->field('Account_ID,Fanxian_Remainder,Fanxian_Count')->where(array('User_ID'=>$UserID))->find();
		$rsUser = $user_model->field('Users_ID,User_Money')->where(array('User_ID'=>$UserID))->find();
		if(!$rsAccount || !$rsUser){//用户不存在
			return false;
		}
		
		if($Fanben[1] <= 0){//返现金额不正确
			return false;
		}
		if($Fanben[2] > 0 && $rsAccount['Fanxian_Count'] >= $Fanben[2]){//返现次数已达到最大值
			return false;
		}
		
		//更新分销商账号相关信息
		$account = array(
			'Fanxian_Remainder' => $rsAccount['Fanxian_Remainder'] == 1 ? $Fanben[0] : $rsAccount['Fanxian_Remainder'] - 1
		);		
		
		if($rsAccount['Fanxian_Remainder'] == 1){//返现处理		
			$account['Fanxian_Count'] = $rsAccount['Fanxian_Count'] + 1;
			//更新用户相关信息
			$Data = array(
				'User_Money' => $rsUser['User_Money'] + $Fanben[1]
			);
			
			$user_model->where(array('User_ID'=>$UserID))->update($Data);
			//更新资金流水
			$Data = array(
				'Users_ID'=>$rsUser['Users_ID'],
				'User_ID'=>$UserID,				
				'Type'=>0,
				'Amount'=>$Fanben[1],
				'Total'=>$rsUser['User_Money'] + $Fanben[1],
				'Note'=>'发展直接下属达到'.$Fanben[0].'个，系统返现'.$Fanben[1].'元',
				'CreateTime'=>time()		
			);
			model('user_money_record')->insert($Data);
		}
		$dis_account_model->where(array('Account_ID'=>$rsAccount['Account_ID']))->update($account);
		return true;
	}
}

if (!function_exists('pre_add_distribute_account')) {

	function pre_add_distribute_account($shop_config, $UsersID) {		
		$error_msg = '';

		if (!empty($_SESSION[$UsersID . 'User_ID'])) {
			$User_ID = $_SESSION[$UsersID . 'User_ID'];
			$user = model('user')->where(array('Users_ID' => $UsersID, 'User_ID' => $User_ID))->find();
			if ($user) {

				global $DB1;
				//获得分销级别
				//$level_data = get_dis_level($DB1,$UsersID);
				//$LevelID = get_user_distribute_level($shop_config,$level_data,$_SESSION[$UsersID . 'User_ID']);//获得该用户应得的分销级别

				if ($user['is_distribute'] == 0) {
					$ownerid = $user['owner_id'];
					$truename = $user['user_name'] ? $user['user_name'] : ($user['user_nickname'] ? $user['user_nickname'] : '暂无姓名');
					switch ($shop_config['distribute_type']) {
						case '0': //自动成为分销商																				

							$flag = create_distribute_acccount( $shop_config, $_SESSION[$UsersID . 'User_ID'], $truename, $ownerid, '', 1);

							$error_msg = $flag ? 'OK' : '会员自动成为分销商失败';
							break;
						case '1': //积分限制
							if ($user['user_totalintegral'] >= $shop_config['distribute_limit']) {

								$flag = create_distribute_acccount( $shop_config, $_SESSION[$UsersID . 'User_ID'], $truename, $ownerid, '', 1);

								$error_msg = $flag ? 'OK' : '会员成为分销商失败';
							} else {
								$error_msg = '1';
							}
							break;
						case '2': //消费金额
							$arr_temp = explode('|', $shop_config['distribute_limit']);
							$arr_temp[1] = !empty($arr_temp[1]) ? intval($arr_temp[1]) : 0;
							if ($arr_temp[0] == 0 && $user['user_cost'] >= $arr_temp[1]) {

								$flag = create_distribute_acccount($shop_config, $_SESSION[$UsersID . 'User_ID'], $truename, $ownerid, '', 1);

								$error_msg = $flag ? 'OK' : '会员成为分销商失败';
							} else {
								$error_msg = '2';
							}
							break;
						case '3'://指定产品
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

/*-20160603add--start--*/
/*
if(!function_exists('get_dis_level')){
	function get_dis_level($DB1,$UsersID){
		$file_path = $_SERVER["DOCUMENT_ROOT"].'/data/cache/'.$UsersID.'dis_level.php';
		if(is_file($file_path)){
			include($file_path);
			return $dis_level;
		}else{
			$dis_level = array();
			$DB->Get('distribute_level','*','where Users_ID="'.$UsersID.'" order by Level_ID asc');
			while($r = $DB->fetch_assoc()){
				$dis_level[$r['Level_ID']] = $r;
			}
			return $dis_level;
		}
	}
}
if(!function_exists('get_user_distribute_level')){//根据消费额获得分销商级别（总消费额），用于生成分销商或更新
	function get_user_distribute_level($shop_config,$level_data,$UserID){
		$first_level_data = reset($level_data);
		$LevelID = $first_level_data['Level_LimitType']==3 ? $first_level_data['Level_ID'] : 0;
		if($shop_config['distribute_type']==1){//消费额门槛
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
 */
/*-20160603add--end--*/

if (!function_exists('deal_distribute_fuxiao_record')) {
	/**
	 * 启动分销商复销记录
	 */
	function deal_distribute_fuxiao_record($Fuxiao, $UsersID, $UserID, $OpenID) {

		$dis_account = model('distribute_account', 'common')->where(array('Users_ID' => $UsersID, 'User_ID' => $UserID))->find();
		$fuxiao_model = model('distribute_fuxiao');

		if(!empty($dis_account)){
			$rsRecord = $fuxiao_model->where(array('Users_ID'=>$UsersID,'Account_ID'=>$dis_account['account_id'],'User_ID'=>$UserID))->find();
			$fxstarttime = getmonth(1);//获得下个月的一号
			if($rsRecord){//存在记录，更改记录
				$Data = array(
					'Fuxiao_StartTime'=>$fxstarttime,
					'Fuxiao_Count'=>0,
					'Fuxiao_Status'=>0,
					'Fuxiao_SubNoticeCount'=>intval($Fuxiao[2]),
					'Fuxiao_LastNoticeTime'=>0,
					'Fuxiao_SubDenedCount'=>intval($Fuxiao[1]),
					'Fuxiao_LastDenedTime'=>0,	
				);
				$fuxiao_model->where(array('Users_ID'=>$UsersID,'Account_ID'=>$dis_account['account_id'],'User_ID'=>$UserID))->update($Data);
			}else{//不存在记录，则增加记录
				$Data = array(
					'Users_ID'=>$UsersID,
					'User_ID'=>$UserID,
					'User_OpenID'=>$OpenID,
					'Account_ID'=>$dis_account['account_id'],
					'Fuxiao_StartTime'=>$fxstarttime,
					'Fuxiao_SubNoticeCount'=>intval($Fuxiao[2]),
					'Fuxiao_SubDenedCount'=>intval($Fuxiao[1])
				);
				$fuxiao_model->insert($Data);
			}
		}
	}
}


if (!function_exists('distribute_fuxiao_tixing')) {
	/**
	 * 启动分销商复销提醒动作，冻结前
	 */
	function distribute_fuxiao_tixing($rsConfig){
		$Fuxiao = $rsConfig['fuxiao_rules'] ? json_decode($rsConfig['fuxiao_rules'], true) : array();
		$UsersID = $rsConfig['users_id'];
		$now = time();//当前时间
		$starttime = strtotime(date('Y-m-01', time()));//当前月的第一天
		$notice_start = strtotime(date('Y-m-t', time())) - 86400 * intval($Fuxiao[2]);//提醒开始时间
		$notice_end = strtotime(date('Y-m-t', time())) + 86399;//提醒结束时间

		$fuxiao_model = model('distribute_fuxiao');

		
		if($now<=$notice_end && $now>=$notice_start){//提醒时间段
			$list = array();
			$counts = array();//消息发送归类
			$list_tmp = $fuxiao_model->field('Record_ID,Fuxiao_LastNoticeTime,User_OpenID,Fuxiao_SubNoticeCount')->where(array('Users_ID'=>$UsersID,'Fuxiao_StartTime'=>$starttime,'Fuxiao_Status'=>0))->select();
			foreach($list_tmp as $k => $v) {
				if($v['Fuxiao_LastNoticeTime'] == 0 || $v['Fuxiao_LastNoticeTime'] < strtotime(date('Y-m-d', time()))){
					$list[$v['Record_ID']] = $v;
					$counts[$v['Fuxiao_SubNoticeCount']][] = $v['User_OpenID'];
				}
			}
			
			if(!empty($list)){
				$sql = 'Fuxiao_LastNoticeTime=' . time().',Fuxiao_SubNoticeCount=Fuxiao_SubNoticeCount-1';

				model('distribute_fuxiao')->where(array('Record_ID'=>array_keys($list)))->update($sql);

				//消息发送
				$weixin_message = new \shop\logic\weixin_message($UsersID,0);
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
	function distribute_dongjie_action($rsConfig) {
		$UsersID = $rsConfig['users_id'];
		$now = time();//当前时间
		$starttime = getmonth(0);//上月的第一天
		$notice_start = strtotime(date('Y-m-01', time()));//冻结开始时间，当前月的第一天
		if($now >= $notice_start){
			$list = array();
			$counts = array();//分销账号ID

			$fuxiao_model = model('distribute_fuxiao');

			$list_tmp = $fuxiao_model->field('Record_ID,Account_ID')->where(array('Users_ID'=>$UsersID,'Fuxiao_StartTime'=>$starttime,'Fuxiao_Status'=>0))->select();
			
			foreach($list_tmp as $k => $r) {
				$list[] = $r['Record_ID'];
				$counts[] = $r['Account_ID'];
			}
			
			if(!empty($list)) {
				$fuxiao_model->where(array('Record_ID'=>$list))->update('Fuxiao_Status=1');

				model('distribute_account')->where(array('Account_ID'=>$counts))->update('Is_Dongjie=1');

			}
		}
	}
}

if (!function_exists('distribute_dongjie_tixing')) {
	/**
	 * 启动分销商复销提醒动作，冻结后
	 */
	function distribute_dongjie_tixing($rsConfig){
		$Fuxiao = $rsConfig['fuxiao_rules'] ? json_decode($rsConfig['fuxiao_rules'], true) : array();
		$UsersID = $rsConfig['users_id'];
		$now = time();//当前时间
		$starttime = getmonth(0);//上月的第一天
		$notice_start = strtotime(date('Y-m-01', time()));//提醒开始时间，当前月的第一天
		$notice_end = $notice_start + 86400 * intval($Fuxiao[1]);//提醒结束时间
		if($now <= $notice_end && $now >= $notice_start){//提醒时间段
			$list = array();
			$counts = array();//消息发送归类

			$fuxiao_model = model('distribute_fuxiao');

			$list_tmp = $fuxiao_model->field('Record_ID,Fuxiao_LastDenedTime,User_OpenID,Fuxiao_SubDenedCount')->where(array('Users_ID'=>$UsersID,'Fuxiao_StartTime'=>$starttime,'Fuxiao_Status'=>1))->select();
			foreach($list_tmp as $k => $r) {
				if($r['fuxiao_lastdenedtime'] == 0 || $r['fuxiao_lastdenedtime'] < strtotime(date('Y-m-d', time()))){
					$list[$r['record_id']] = $r;
					$counts[$r['fuxiao_subdenedcount']][] = $r['user_openid'];
				}
			}
			
			if(!empty($list)){
				$sql = "Fuxiao_LastDenedTime=".time().",Fuxiao_SubDenedCount=Fuxiao_SubDenedCount-1";
				$fuxiao_model->where(array('Record_ID'=>array_keys($list)))->update($sql);
				//消息发送
				$weixin_message = new \shop\logic\weixin_message($UsersID,0);	
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
	function distribute_delete_action($rsConfig){
		$Fuxiao = $rsConfig['fuxiao_rules'] ? json_decode($rsConfig['fuxiao_rules'], true) : array();
		$UsersID = $rsConfig['users_id'];
		$now = time();//当前时间
		$starttime = getmonth(0);//上月的第一天
		$notice_start = strtotime(date('Y-m-01', time())) + 86400 * $Fuxiao[1];//冻结开始时间，当前月的第一天
		if($now >= $notice_start){

			$fuxiao_model = model('distribute_fuxiao');

			$list = array();
			$counts = array();//分销账号ID
			$usersids = array();
			$list_tmp = $fuxiao_model->field('Record_ID,Account_ID,User_ID')->where(array('Users_ID'=>$UsersID,'Fuxiao_StartTime'=>$starttime,'Fuxiao_Status'=>1))->select();
			foreach($list_tmp as $r){
				$list[] = $r['Record_ID'];
				$counts[] = $r['Account_ID'];
				$usersids[] = $r['User_ID'];
			}
			
			if(!empty($list)){
				$fuxiao_model->where(array('Record_ID'=>$list))->update('Fuxiao_Status=2');

				model('distribute_account')->where(array('Account_ID'=>$counts))->update('Is_Delete=1');

				model('user')->where(array('User_ID'=>$usersids))->update('Is_Distribute=0');
			}
		}
	}
}

if (!function_exists('distribute_fuxiao_return_action')) {
	/**
	 * 启动分销商复销成功
	 */
	function distribute_fuxiao_return_action($Rules, $rsAccount, $OpenID){
		$Fuxiao = $Rules ? json_decode($Rules, true) : array();
		$UsersID = $rsAccount['Users_ID'];
		$UserID = $rsAccount['User_ID'];
		$order_endtime = time() + 3600;
		$enabled = 0;
		//账号冻结后
		if($rsAccount['Is_Dongjie'] == 1){
			if (date('n') == 1) {
			    $tmpMonth = 12;
			    $tmpYear = date ('Y') - 1;
			}else {
			    $tmpMonth = date ('n') - 1;
			    $tmpYear = date ('Y');
			}
			$order_starttime = $tmpYear. '-' . $tmpMonth . '-01';//上个月的第一天		
		}else{
			if (date('n') == 12) {
			    $tmpMonth = 1;
			    $tmpYear = date ('Y') + 1;
			}else {
			    $tmpMonth = date ('n') + 1;
			    $tmpYear = date ('Y');
			}
			$order_starttime = $tmpYear. '-' . $tmpMonth . '-01';//当月的第一天
		}
		
		$order = model('user_order')->field('SUM(Order_TotalPrice) as money')->where(array('Users_ID'=>$UsersID,'User_ID'=>$UserID,'Order_Status >'=>2,'Order_CreateTime >='=>$order_starttime,'Order_CreateTime <='=>$order_endtime))->find();//用户在时间段中的总消费，付款之后
		
		$total = empty($order['money']) ? 0 : $order['money'];
		if($total >= $Fuxiao[0] && $rsAccount['Is_Dongjie'] == 1){//解冻

		    model('distribute_account')->where(array('Account_ID'=>$rsAccount['Account_ID']))->update(array('Is_Dongjie'=>0));				

		}
		
		//更改记录
		$condition = array(
		    'Users_ID'=>$UsersID,
			'Account_ID'=>$rsAccount['Account_ID'],
			'User_ID'=>$UserID,
			'Fuxiao_StartTime'=>$order_starttime
		);

		$rsRecord = model('distribute_fuxiao')->field('Record_ID,Fuxiao_Count')->where($condition)->find();

		
		if($rsRecord) {//存在记录，更改记录
			$Data = array(
				'Fuxiao_StartTime' => $rsAccount['Is_Dongjie'] == 1 ? strtotime(date('Y-m-01', time())) : strtotime(date('Y-m-01', strtotime('1 month'))),//当前月的第一天
				'Fuxiao_Count' => $rsRecord['Fuxiao_Count']+1,
				'Fuxiao_Status' => 0,
				'Fuxiao_SubNoticeCount' => intval($Fuxiao[2]),
				'Fuxiao_LastNoticeTime' => 0,
				'Fuxiao_SubDenedCount' => intval($Fuxiao[1]),
				'Fuxiao_LastDenedTime' => 0,
			);

			model('distribute_fuxiao')->where(array('Account_ID'=>$rsRecord['Record_ID']))->update($Data);

		}else {//不存在记录，则增加记录
			$Data = array(
				'Users_ID' => $UsersID,
				'User_ID' => $UserID,
				'User_OpenID' => $OpenID,
				'Fuxiao_Count' => 1,
				'Account_ID' => $rsAccount['Account_ID'],
				'Fuxiao_StartTime' => $rsAccount['Is_Dongjie']==1 ? strtotime(date('Y-m-01', time())) : strtotime(date('Y-m-01', strtotime('1 month'))),
				'Fuxiao_SubNoticeCount' => intval($Fuxiao[2]),
				'Fuxiao_SubDenedCount' => intval($Fuxiao[1])
			);

			model('distribute_fuxiao')->insert($Data);

		}
	}
}


if (!function_exists('get_filter_cart_list')) {
	/**
	 * 计算产品网站提成(即产品利润：佣金发放+网站所得)，重组购物车
	 */
	function get_filter_cart_list($cartlist){
		$BizList = array();
		$bizids = array_keys($cartlist);
		$BizList_tpm = model('biz')->field('Finance_Type,Finance_Rate,Biz_ID')->where(array('Biz_ID'=>$bizids))->select();
		foreach($BizList_tpm as $key => $val){
			$BizList[$val['Biz_ID']] = $val;
		}
		
		foreach($cartlist as $Biz_ID => $Biz_CartList){//第一次循环 按商家
			$finance_type_biz = $BizList[$Biz_ID]['Finance_Type'];
			$finance_type_products = 0;
			foreach($Biz_CartList as $Products_ID => $Products){//第二次循环 按商品
				if($finance_type_biz ==1){//按单个商品具体配置
				    $rsProducts = model('shop_products')->field('Products_FinanceType,Products_PriceS,Products_FinanceRate')->where(array('Products_ID'=>$Products_ID))->find();
					$finance_type_products = $rsProducts["Products_FinanceType"];
				}
				foreach($Products as $Cart_ID => $Cart_Info){
					if($finance_type_biz==0){//商家统一使用比例结算
						$cartlist[$Biz_ID][$Products_ID][$Cart_ID]["ProductsProfit"] = $Cart_Info["ProductsPriceX"] * $BizList[$Biz_ID]["Finance_Rate"] / 100;
					}else{
						if($finance_type_products==0) {//产品使用比例结算
							$cartlist[$Biz_ID][$Products_ID][$Cart_ID]['ProductsProfit'] = $Cart_Info["ProductsPriceX"] * $rsProducts["Products_FinanceRate"] / 100;
						}else {//产品供货价结算
							$ProductsS = empty($Cart_Info['spec_list']) ? $rsProducts["Products_PriceS"] : get_products_supply_price($Products_ID,$rsProducts["Products_PriceS"],$Cart_Info["spec_list"]);
							$cartlist[$Biz_ID][$Products_ID][$Cart_ID]['ProductsProfit'] = $Cart_Info['ProductsPriceX'] - $ProductsS;
						}
					}
				}
			}
		}
		return $cartlist;
	}
}

if (!function_exists('get_products_supply_price')) {//获取供货价
	function get_products_supply_price($ProductsID, $ProductsPriceS, $spec_list){
		if(empty($spec_list)){
			return $ProductsPriceS;
		}else{
			$spec_list = explode(',', $spec_list);
			$products_attr_list = model('shop_products_attr')->where(array('Product_Attr_ID'=>$spec_list,'Products_ID'=>$ProductsID))->find();
			foreach($products_attr_list as $key => $val){
				if(!empty($val['supply_price'])){
					$ProductsPriceS = $ProductsPriceS + number_format($val['supply_price'], 2);
				}
			}
		}
		return $ProductsPriceS;
	}
}
if (!function_exists('generateDisPath')) {
    /**
	 * 生成本分销账号的Dis_Path
	 * @return string $Dis_Path
	 */
	function generateDisPath($UserID, $invite_id) {
		$Dis_Path = '';
		$user = model('user')->where(array('User_ID'=>$UserID))->find();
		$userOwnerID = $user['owner_id'];
		$inviter_info = get_dsaccount_by_id($user['users_id'], $invite_id);
		if ($inviter_info && $invite_id != 0) {
			$inviterDisPath = $inviter_info['dis_path'];
			$ids = explode(',', trim($inviterDisPath, ','));
			$num = count($ids);

			//如果等于九
			if ($num == 9) {
				array_shift($ids);
				array_push($ids, $userOwnerID);
				$Dis_Path = ',' . implode(',', $ids) . ',';
				//如果小于九
			} else if ($num < 9) {
				$pre = strlen($inviterDisPath) ? '' : ',';
				$Dis_Path = $pre . $inviterDisPath . $userOwnerID . ',';
			}

		} else {
			//如果不是根店
			if ($userOwnerID != 0) {
				$Dis_Path = ',' . $userOwnerID . ',';
			}
		}
		return $Dis_Path;
	}
}
if (!function_exists('getAncestorIds')) {
    /**
	 * 获取此分销账号的祖先id列表
	 * @param 本店当前分销级数 int $level
	 * @param 
	 * @return Array $ids 祖先id列表
	 */
	function getAncestorIds($level, $Disaccount_info) {
		$ids = array();
		if (!empty($Disaccount_info['Dis_Path'])) {
			$res = trim($Disaccount_info['Dis_Path'], ',');
			$list = explode(',', $res);

			$ids = array_slice($list, -$level);

			//convert id from  string to int
			foreach ($ids as $key => $item) {
				$ids[$key] = intval($item);
			}
		}
		return $ids;
	}
}
if (!function_exists('get_dis_pro_title')) {
    function get_dis_pro_title($UsersID, $type = 'front') {

		$dis_config = model('distribute_config')->field('Pro_Title_Level')->where(array('Users_ID'=>$UsersID))->find();

		$pro_titles = false;
		if (!empty($dis_config)) {
			$pro_titles = json_decode(htmlspecialchars_decode($dis_config['Pro_Title_Level']), TRUE);
			if(!empty($pro_titles)){
			    if($type == 'front'){
					foreach($pro_titles as $key => $item){
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
}
if (!function_exists('get_my_leiji_income')) {
	/*
	 *获取我的累计佣金收入
	 */
	function get_my_leiji_income($UsersID, $UserID) {

        $record_list = model('distribute_account_record')->field('Record_Money')->where(array('Users_ID' => $UsersID, 'User_ID' => $UserID,'Record_Type'=>0))->select();


		$total_income = 0;

		foreach ($record_list as $key => $item) {
			$total_income += $item['Record_Money'];
		}

		return $total_income;
	}
}
if (!function_exists('get_my_leiji_sales')) {
	/**
	 *我的团队累计销售额
	 */
	function get_my_leiji_sales($UsersID, $UserID, $posterity) {

        $shop_distribute_record = model('distribute_record');

		$total_sales = 0;
		
		//计算本店当前用户所购买商品销售额
		$record_list = $shop_distribute_record->field('Product_Price,Qty')->where(array('Users_ID' => $UsersID,'Owner_ID'=>$UserID))->select();
		if(!empty($record_list)){
			foreach ($record_list as $key => $item) {
				$total_sales += $item['Product_Price'] * $item['Qty'];
			}
		}
		//计算本店下属分销商作为用户所购买商品销售额
		$posterityids = array();
		if (count($posterity) > 0) {
		    foreach($posterity as $key => $val){
			    $posterityids[] = $val['User_ID'];
			}
		}
		if (count($posterityids) > 0) {
			$record_list = $shop_distribute_record->field('Product_Price,Qty,Buyer_ID,Owner_ID')->where(array('Users_ID' => $UsersID,'Owner_ID'=>$posterityids))->select();
			
			$posterity_total_sales = 0;
			foreach ($record_list as $key => $item) {
			    if($item['Buyer_ID'] == $item['Owner_ID']){
				    $posterity_total_sales += $item['Product_Price'] * $item['Qty'];
				}
			}
			$total_sales += $posterity_total_sales;
		}
		return $total_sales;
	}
}
if (!function_exists('getPosterity')) {
	/**
	 * 获取此账号下属分销商
	 * @param  int $level 此店的分销商层数
	 * @return Collection $posterity
	 */
	function getPosterity($Users_ID, $User_ID, $level) {

		$shop_distribute_account = model('distribute_account');

		//获取分销父路径中包含此用户ID的分销商
		$descendants = $shop_distribute_account->field('Account_ID,User_ID,Dis_Path,Shop_Name,balance,Is_Audit,Total_Income,Account_CreateTime')->where(array('Users_ID'=>$Users_ID,'Dis_Path'=>'%,' . $User_ID . ',%'))->select();
		//筛选出处于$level级别中的分销商
		foreach($descendants as $k => $dsAccount) {
			//计算出分销商级数
			$dis_path = trim($dsAccount['Dis_Path'], ',');
			$dis_path_nodes = explode(',', $dis_path);
			$dis_path_nodes = array_reverse($dis_path_nodes);
			$pos = array_search($User_ID, $dis_path_nodes);
			$curLevel = $pos + 1;
            if ($curLevel <= $level) {
				//为分销账号动态指定级别
				$descendants[$k]['level'] = $curLevel;
			}else{
			    continue;
			}
		}
		return $descendants;
	}
}
/**
 *判断此订单是否为分销订单
 */
function is_distribute_order($UsersID, $OrderID) {
	//检测此订单是否为分销订单
    $condition = array(
	    'Users_ID'=>$UsersID,
		'Order_ID'=>$OrderID
	);
	$order = model('user_order')->field('Order_CartList')->where($condition)->find();

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
 *删除分销记录
 */
function delete_distribute_record($UsersID, $OrderID) {

	$shop_distribute_record = model('distribute_record');

	//删除分销记录
	$condition = array(
	    'Users_ID'=>$UsersID,
		'Order_ID'=>$OrderID
	);
	$recordIDS = array();
	$record_list = $shop_distribute_record->field('Record_ID')->where($condition)->select();
	foreach($record_list as $id){
            $recordIDS[] = $id['Record_ID'];
	}
	$shop_distribute_record->where($condition)->delete();
	//删除分销账户记录
	if($record_list){
            $recordIDSs = implode(',',$recordIDS); 
            $conditions['Users_ID'] = $UsersID;
            $conditions['Ds_Record_ID'] = array('in',$recordIDSs);
            model('distribute_account_record')->where($conditions)->delete();

	}
}