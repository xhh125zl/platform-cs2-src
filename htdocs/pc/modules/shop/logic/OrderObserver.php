<?php
namespace shop\logic;
/**
 *订单的观察者
 */
class OrderObserver {
	
	public $shop_config;  //店铺配置
	public $user_config;  //店铺用户相关配置
	private $order;
	private $user;	
	
	/**
	 *订单确认收货之后的事情
	 */
	public function confirmed($order){
		$this->order = $order;
		$flag_a = $this->handle_user_info();
		
		$flag_b = $flag_c = $flag_d = true;

		$disAccountRecord = model()->query('SELECT COUNT(*) as count FROM distribute_account_record AS a RIGHT JOIN distribute_record AS r ON a.Ds_Record_ID=r.Record_ID WHERE r.Order_ID=' . $this->order['order_id'], 'find');

		if($disAccountRecord['count']){
			//更改分销账户得钱记录状态
			$flag_b = $this->handle_dis_record_info();  
			//处理分销账号信息，增加余额，总收入，以及晋级操作
			$flag_c = $this->handle_dis_account_info();  
		}
		
		//获取本店分销配置
		if($this->shop_config['dis_agent_type'] != 0){
			$flag_d = $this->handle_dis_agent_info();
		}
		
	
	    if(!$flag_a && $flag_b && $flag_c && $flag_d) {
			$response = array(
				"status"=>0,
				"msg"=>'确认收货失败'
			);
			echo json_encode($response, JSON_UNESCAPED_UNICODE);exit;
		}else {
			$balance_sales = new \shop\logic\balance($order['users_id']);
			$balance_sales->add_sales($order['order_id']);
		}
	}
	
	
	/**
	 * 处理用户信息
	 * 更新用户积分，用户销售额等信息
	 * 增加积分记录
	 */
	private function handle_user_info() {
		$order = $this->order;
		
		// 用户级别设置
		$User_Level_Json = $this->user_config['userlevel'];
		$User_Level_Config = json_decode ( $User_Level_Json, TRUE );
		
		$interval = 0;
		/*if (! empty ( $this->shop_config ['integral_convert'] )) {
			$interval = intval ( $order ['order_totalprice'] / abs ( $this->shop_config ['integral_convert'] ) );
		}*/
                $man_list = $this->shop_config['Man'];
                $order_money = $order ['Order_TotalPrice'];
                if (!empty($man_list)) {
                    global $DB;
                    $orderid = $order['Order_ID'];
                    $is_back = $DB->GetRs('user_back_order','','where Order_ID='.$orderid.' and  Back_Status=4');
                    if (!empty($is_back)) {
                        $order_money = $order_money - $is_back['Back_Amount'];  
                    }
                    $man_array = json_decode($man_list, true);
                    foreach ($man_array as $k => $v) {
                        if ($order_money >= $v['reach']) {
                            $interval = $v['award'];
                            break;
                        }
                    }
                } 
                
		$user = model('user')->where(array('User_ID'=>$order['user_id']))->find();
		if($user){
			$this->user = $user;
			
			if($user['is_distribute'] == 1) {//爵位晋级
				$pro = new \shop\logic\ProTitle($user['users_id'], $user['user_id']);
				$flag_x = $pro->up_nobility_level();
				
			}else {//是否一次性消费成为分销商
				if($this->shop_config ['distribute_type'] == 2) {//消费金额
					$arr_temp = explode('|', $this->shop_config ['distribute_limit']);
					$arr_temp[1] = empty($arr_temp[1]) ? 0 : intval($arr_temp[1]);
					if($arr_temp[0]==1 && $order['order_totalprice'] >= $arr_temp[1]) {
						$truename = $user['user_name'] ? $user['user_name'] : ($user['user_nickname'] ? $user['user_nickname'] : '真实姓名');
						$owner['id'] = $user['owner_id'];					
						create_distribute_acccount($this->shop_config, $user['user_id'], $truename, $owner['id'], '', 1);
					}
				}
			}
			
			$data['User_Integral'] = $user['user_integral'] + $interval;
			$data['User_TotalIntegral'] = $user['user_totalintegral'] + $interval;
			$data['User_Cost'] = $user['user_cost'] + $order['order_totalamount'];
			
			if (count($User_Level_Config) > 1) {
				$level = $this->determineUserLevel($User_Level_Config);
				if ($level > $user['user_level']) {
					$data['User_Level'] = $level;
				}
			}
			$res = model('user')->where(array('User_ID'=>$user['user_id']))->update($data);
			// 增加积分记录
			if ($interval > 0) {
				$this->handle_integral_record( $interval );
			}
			
			// 发送积分变动信息
			if ($interval > 0) {
				$weixin_message = new \shop\logic\weixin_message($order['users_id'], $order['user_id']);
				$contentStr = '购买商品送 ' . $interval . ' 个积分';
				$weixin_message->sendscorenotice($contentStr);
			}
		}
		
		return $res;
	}
	
	/**
	 * 增加用户积分记录
	 */
	private function handle_integral_record($interval) {
		$user_integral_record['Record_Integral'] = $interval;
		$user_integral_record['Record_SurplusIntegral'] = $this->user ['user_integral'] + $interval;
		$user_integral_record['Operator_UserName'] = '';
		$user_integral_record['Record_Type'] = 2;
		$user_integral_record['Record_Description'] = '购买商品送 ' . $interval . ' 个积分';
		$user_integral_record['Record_CreateTime'] = time ();
		$user_integral_record['User_ID'] = $this->user ['user_id'];
		$user_integral_record['Users_ID'] = $this->user['users_id'];
		$flag = model('user_integral_record')->insert($user_integral_record);
		return $flag;
	}
	
	/**
	 * 处理分销记录信息
	 */
	private function handle_dis_record_info() {

		$distribute_record_model = model('distribute_record');

		$distribute_records = $distribute_record_model->field('Record_ID')->where(array('Order_ID'=>$this->order['order_id']))->select();
		$Record_IDS = array();
		foreach($distribute_records as $k => $v) {
			$Record_IDS[] = $v['Record_ID'];
		}
		// 将分销记录设置为完成
		$flag_a = $distribute_record_model->where(array('Order_ID'=>$this->order['order_id']))->update(['status' => 1]);
		// 将分销账号记录置为完成

		$flag_b = model('distribute_account_record')->where(array('Ds_Record_ID'=>$Record_IDS))->update(['Record_Status' => 2]);

		return $flag_a && $flag_b;
	}
	
	/**
	 * 增加分销账号余额,总销售额
	 */
	private function handle_dis_account_info() {

		$distribute_account_record_model = model('distribute_account_record');
		$distribute_record_model = model('distribute_record');
		$distribute_account_model = model('distribute_account');

		$distribute_records = $distribute_record_model->field('Record_ID')->where(array('Order_ID'=>$this->order['order_id']))->select();
		$Record_IDS = array();
		foreach($distribute_records as $k => $v) {
			$Record_IDS[] = $v['Record_ID'];
		}
		
		$disAccountRecord = $distribute_account_record_model->where(array('Ds_Record_ID'=>$Record_IDS))->select();
		
		// 得到获得佣金的UserID
		$userID_List = array();
		foreach($disAccountRecord as $k => $v) {
			$userID_List[] = $v['user_id'];
		}
		
		$userIDS = array_unique($userID_List);
	  
	    $pro_titles = get_dis_pro_title($this->order['users_id']);
		
		if (!empty($userIDS)) {
			foreach ($userIDS as $key => $item) {
				$interest_list[$item] = 0;
				$nobi_list[$item] = 0;
				$sales_list[$item] = 0;
			}	
		    foreach ( $disAccountRecord as $key => $accountRecord ) {	
				$interest_list [$accountRecord['user_id']] += $accountRecord['record_money'];
				$nobi_list[$accountRecord['user_id']] += $accountRecord['nobi_money'];
				$DisRecord = $distribute_record_model->where(array('Record_ID'=>$accountRecord['ds_record_id']))->find();
				$sales_list[$accountRecord['user_id']] += $DisRecord['product_price'] * $DisRecord['qty'];
			}
			
		    $disAccoutn_list = $distribute_account_model->where(array('Users_ID'=>$this->order['users_id'],'User_ID'=>$userIDS))->select();
			
			// 取出所有获得佣金者的分销账号
			$flag = FALSE;
			$data = array();
			foreach ( $disAccoutn_list as $disAccount ) {
				$cur_blance = $disAccount['balance'] + $interest_list[$disAccount['user_id']] + $nobi_list[$disAccount['user_id']];
				$data['balance'] = $cur_blance;
				$cur_total_income =  $disAccount['total_income'] + $interest_list [$disAccount['user_id']] + $nobi_list[$disAccount['user_id']];
				$data['Total_Income'] = $cur_total_income;
				$flag = $distribute_account_model->where(array('Account_ID'=>$disAccount['account_id']))->update($data);
				if (! $flag) {
					break;
				}
			}
		}
	}
	
    /**
     *处理分销代理信息 
     */
	private function handle_dis_agent_info() {
		
		if($this->shop_config['dis_agent_type'] == 1) {
			$res = $this->handle_common_dis_agent();
		}else {
			$res = $this->handle_area_dis_agent();
		}
		return $res;
	   
	}
	
	/**
	 * 处理普通分销代理信息
	 * @return boolean
	 */
	private function handle_common_dis_agent() {
		
		$Users_ID = $this->order['users_id'];
		$User_ID = $this->order['user_id'];
		
		$user = $this->user;		
		$owner_id = $user['owner_id'];
		$Agent_Rate = $this->shop_config['agent_rate'];
		$order = $this->order;
		
		$flag = true;
		//如果此用户不是根用户
		if($owner_id != 0){
			$root_id = $this->user['root_id'];
			if($root_id != 0){
				//计算出用户应得钱数
				if($Agent_Rate > 0 ){
					$total_price = $order['order_totalprice'];
					$record_money = $total_price * $Agent_Rate / 100;
					$flag_a = $this->do_agent_award($root_id, $record_money);
					$flag_b = $this->add_agent_award_record($root_id, $record_money);
					$flag = $flag_a && $flag_b;
				}
			}
		}
		return $flag;
	}
	
	
	/**
	 * 处理地区分销代理信息
	 */
	private function handle_area_dis_agent(){
	    $order = $this->order;

	    $area_rate = json_decode($this->shop_config['agent_rate'], TRUE);
	    $province_rate = $area_rate['Province'];
	    $city_rate = $area_rate['City'];
	  	
		$user = $this->user;
		$User_Province = trim($user['user_province']);
		$User_City = trim($user['user_city']);
		
		$area_agents = model('shop_dis_agent_areas')->where(array('Users_ID'=>$order['users_id'],'area_name'=>array($User_Province, $User_City)))->select();
		foreach($area_agents as $key => $agent_area){
			//省代account_id
			if($agent_area['type'] == 1){
				$province_agent_id = $agent_area['account_id'];
				if($province_rate > 0 ){
					$record_money = $order['order_totalprice'] * $province_rate / 100;
					$this->do_agent_award($province_agent_id, $record_money);
					$this->add_agent_award_record($province_agent_id, $record_money, 2);
				}
			}
			
			//市代account_id
			if($agent_area['type'] == 2){
				$city_agent_id =  $agent_area['account_id'];
				if($city_agent_id > 0 ){
					$record_money = $order['order_totalprice'] * $city_rate / 100;
					$this->do_agent_award($city_agent_id, $record_money);
					$this->add_agent_award_record($city_agent_id, $record_money, 3);
				}
			}
		}
	}
	
	/*
	*给代理人添加佣金
	*添加代理记录
	*/
	private function do_agent_award($root_id, $record_money){		

	    $dis_account_model = model('distribute_account');

		
		$UsersID = $this->order['users_id'];
		
		$dis_account = $dis_account_model->where(array('Account_ID'=>$root_id))->find();
		
        $balance = $dis_account['balance'] + $record_money;
		$Total_Income = $dis_account['total_income'] + $record_money;
		$data['balance'] = $balance;
		$data['Total_Income'] = $Total_Income;
        $flag = $dis_account_model->where(array('Account_ID'=>$root_id))->update($data);					 
		return $flag;
	}
	
	/**
	 *添加代理奖励记录
	 */
	private function add_agent_award_record($root_id, $record_money, $type = 1){
		$dis_agent_record_model = model('shop_dis_agent_rec');
		
		$order = $this->order;
		$dis_agent_record['Users_ID'] = $order['users_id']; 
		$dis_agent_record['Account_ID'] = $root_id;
		$dis_agent_record['Record_Money'] = $record_money;
		$dis_agent_record['Record_CreateTime'] = time();
		$dis_agent_record['Record_Type'] = $type;
		$flag = $dis_agent_record_model->insert($dis_agent_record);
		return $flag;
	}
	/*
	 *确定用户是否满足升级条件
	 *@param Int  $sales 销售额
	 */
	private function determineUserLevel($User_Level_Config) {

		$User_Cost = $this->user['user_cost'];
		
		$level_dropdown = array();
		$level_range_list = array();
		$level_count = count($User_Level_Config);
		$level_begin_cost = $User_Level_Config[1]['UpCost'];
		$level_end_cost = $User_Level_Config[$level_count - 1]['UpCost'];

		//如果消费额小于等级起始消费额，1级

		if ($User_Cost < $level_begin_cost) {
			return 0;
		}

		//如果消费额大于等级结束消费额,最高级
		if ($User_Cost >= $level_end_cost) {
			return $level_count - 1;
		}

		//除此之外，循环确定
		foreach ($User_Level_Config as $key => $item) {

			if ($key != $level_count - 1) {
				$end_cost = $User_Level_Config[$key + 1]['UpCost'];
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
}