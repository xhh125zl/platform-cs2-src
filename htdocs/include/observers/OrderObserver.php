<?php
/**
 *订单的观察者
 */
defined('BASEPATH') OR exit('No direct script access allowed');
use Illuminate\Database\Capsule\Manager as Capsule;

class OrderObserver {
	
	private $shop_config;  //店铺配置
	private $user_config;  //店铺用户相关配置
	private $order;
	private $user;	
	
	/**
	 *订单确认收货之后的事情
	 */
	public function confirmed($order){
		$rsConfig = shop_config($order['Users_ID']);
		$dis_config = dis_config($order['Users_ID']);
		//合并参数
		$rsConfig = array_merge($rsConfig,$dis_config);
		$this->shop_config = $rsConfig;
		$this->user_config = shop_user_config($order['Users_ID']);
		
		$this->order = $order;	 
		
		begin_trans();
		$flag_a = $this->handle_user_info();
		//业务提成处理
                require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Salesman_ Commission.php');
                $salesman = new Salesman_commission();
                $salesman->up_sales_status($order['Order_ID'],2);
                $salesman->up_salesincome($order['Order_ID']);
                
		$flag_b = $flag_c = $flag_d = $sha_Flag =true;
		if($order->disAccountRecord()->count() > 0){
			//更改分销账户得钱记录状态
			$flag_b = $this->handle_dis_record_info();  
			//处理分销账号信息，增加余额，总收入，以及晋级操作
			$flag_c = $this->handle_dis_account_info();  
		}
		
		//获取本店分销配置
		if($this->shop_config['Dis_Agent_Type'] != 0){
			$flag_d = $this->handle_dis_agent_info();
		}

		//获取本店分销股东信息
		if ($this->shop_config['Sha_Agent_Type'] != 0) {
			$sha_Flag = $this->handle_dis_sha_info();
		}
		
		$flag = $flag_a && $flag_b && $flag_c && $flag_d && $sha_Flag;
	    if(!$flag){
			back_trans();
			$response = array(
				"status"=>0,
				"msg"=>'确认收货失败'
			);
			
			echo json_encode($response,JSON_UNESCAPED_UNICODE);
		}else{
			global $DB1;
			require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
			$balance_sales= new balance($DB1,$order['Users_ID']);
			$balance_sales->add_sales($order['Order_ID']);
			commit_trans();
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
		$User_Level_Json = $this->user_config['UserLevel'];
		$User_Level_Config = json_decode ( $User_Level_Json, TRUE );
		
		$interval = 0;
		if (! empty ( $this->shop_config ['Integral_Convert'] )) {
			$interval = intval ( $order ['Order_TotalPrice'] / abs ( $this->shop_config ['Integral_Convert'] ) );
		}		
		$user = $order->User()->getResults();
		$res = true;

		if($user){
			$this->user = $user;
			
			if($user->Is_Distribute == 1){//爵位晋级
				if($this->shop_config ['Pro_Title_Status']==4){
					$pro = new ProTitle($user->Users_ID,$user->User_ID);
					$flag_x = $pro->up_nobility_level();
				}
				
			}else{//是否一次性消费成为分销商
				if($this->shop_config ['Pro_Title_Status']==4){
					$pro = new ProTitle($user->Users_ID,$user->Owner_ID);
					$flag_x = $pro->up_nobility_level();
				}
				
				if($this->shop_config ['Distribute_Type']==1){//消费金额
					global $DB1;
					$level_data = get_dis_level($DB1,$user->Users_ID);
					$LevelID = get_user_distribute_level_confirmcost($level_data,$this->shop_config,$order ['Order_TotalPrice']);
					if($LevelID >= 1){
						$rsUser = $DB1->GetRs('user','*','where User_ID='.$user->User_ID);
						$f = create_distribute_acccount($this->shop_config, $rsUser, $LevelID, '', 1);
					}
				}
			}
			
			$user->User_Integral = $user->User_Integral + $interval;
			$user->User_TotalIntegral = $user->User_TotalIntegral + $interval;
			$user->User_Cost = $user->User_Cost + $order->Order_TotalAmount;
			
			if (count ( $User_Level_Config ) > 1) {
				$level = $user->determineUserLevel($User_Level_Config,$order->Order_TotalAmount);
				if ($level > $user->User_Level) {
					$user->User_Level = $level;
				}
			}
			
			
			
			$res = $user->save ();
			
			// 增加积分记录
			if ($interval > 0) {
				$this->handle_integral_record ( $interval );
			}
			
			// 发送积分变动信息
			if ($interval > 0) {
				require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
				global $DB1;
				$weixin_message = new weixin_message($DB1,$order['Users_ID'],$order['User_ID']);
				$contentStr = '购买商品送 '.$interval.' 个积分';
				$weixin_message->sendscorenotice($contentStr);
			}
		}
		
		return $res;
	}
	
	/**
	 * 增加用户积分记录
	 */
	private function handle_integral_record($interval) {
		
		$user_integral_record = new User_Integral_Record ();
		$user_integral_record->Record_Integral = $interval;
		$user_integral_record->Record_SurplusIntegral = $this->user ['User_Integral'] + $interval;
		$user_integral_record->Operator_UserName = '';
		$user_integral_record->Record_Type = 2;
		$user_integral_record->Record_Description = '购买商品送 ' . $interval . ' 个积分';
		$user_integral_record->Record_CreateTime = time ();
		$user_integral_record->User_ID = $this->user ['User_ID'];
		$user_integral_record->Users_ID = $this->user['Users_ID'];
		$flag = $user_integral_record->save ();
		
		return $flag;
	}
	
	/**
	 * 处理分销记录信息
	 */
	private function handle_dis_record_info() {
		
		global $DB1;
		//$rsRecord = $DB1->GetRs('distribute_record','Record_ID',' WHERE `Order_ID`='.$this->order['Order_ID']);
		$rsRecord = $DB1->GetRs('distribute_record','Record_ID,Order_ID',' WHERE `Order_ID`='.$this->order['Order_ID']);
		if($rsRecord){
			 
			//$flag_a = $DB1->Set('distribute_record', array('status' => 1), ' WHERE `Record_ID`='.$rsRecord['Record_ID']);
			$flag_a = $DB1->Set('distribute_record', array('status' => 1), ' WHERE `Order_ID`='.$rsRecord['Order_ID']);
			
			$flag_b = $DB1->Set('distribute_account_record', array('Record_Status' => 2), ' WHERE `Ds_Record_ID`='.$rsRecord['Record_ID']);


			return $flag_a && $flag_b;
		}else{
			return true;
		}
		
		
	}
	
	/**
	 * 增加分销账号余额,总销售额
	 */
	private function handle_dis_account_info() {
	
		//
		
		// 得到获得佣金的UserID
		$disAccountRecord = $this->order->disAccountRecord()->getResults ();
		
		$userID_List = $disAccountRecord->map ( function ($disAccountRecord) {
			return $disAccountRecord->User_ID;
		} )->all ();
		
		$userIDS = array_unique ( $userID_List );
		$flag = true;
	    //$pro_titles = Dis_Config::get_dis_pro_title($this->order->Users_ID);
		
		if (! empty ( $userIDS )) {

			foreach ( $userIDS as $key => $item ) {
				$interest_list [$item] = 0;
				$nobi_list[$item] = 0;
				$sales_list [$item] = 0;
				
			}		
		    foreach ( $disAccountRecord as $key =>$accountRecord) {	
				$interest_list [$accountRecord->User_ID] += $accountRecord->Record_Money;
				$nobi_list[$accountRecord->User_ID] += $accountRecord->Nobi_Money;
				$DisRecord = $accountRecord->DisRecord ()->getResults ();
				$sales_list [$accountRecord->User_ID] += $DisRecord->Product_Price * $DisRecord->Qty;
			}
			
		
			$disAccoutn_list = Dis_Account::where('Users_ID',$this->order->Users_ID)
										   ->whereIn('User_ID',$userIDS )
										   ->get();
			
			// 取出所有获得佣金者的分销账号
			
			foreach ( $disAccoutn_list as $disAccount ) {
				$cur_blance = $disAccount->balance + $interest_list[$disAccount->User_ID]+$nobi_list[$disAccount->User_ID];
				$disAccount->balance = $cur_blance;
				$cur_total_income =  $disAccount->Total_Income + $interest_list [$disAccount->User_ID]+$nobi_list[$disAccount->User_ID];
				$disAccount->Total_Income = $cur_total_income;
				
				$flag = $disAccount->save();
			
				if (! $flag) {
					break;
				}

			}
			
		}
		
		return $flag;
		
	}


	
    /**
     *处理分销代理信息 
     */
	private function handle_dis_agent_info(){
		
		// if($this->shop_config['Dis_Agent_Type'] == 1){
		// 	$res = $this->handle_common_dis_agent();
		// }else{
			$res = $this->handle_area_dis_agent();
		// }
		
		return $res;
	   
	}
	
	/**
	 * 处理普通分销代理信息
	 * @return boolean
	 */
	private function handle_common_dis_agent(){
		
		$Users_ID = $this->order->Users_ID;
		$User_ID = $this->order->User_ID;
		
		$user = $this->user = User::find($User_ID);		
		$owner_id = $user->Owner_Id;
		$Agent_Rate = $this->shop_config['Agent_Rate'];
		$order = $this->order;
		
		$flag = true;
		//如果此用户不是根用户
		if($owner_id != 0){
			$root_id = $this->user->Root_ID;
			
			if($root_id != 0){
				$accountinfo =  Dis_Account::Multiwhere(array('Users_ID'=>$Users_ID,'User_ID'=>$root_id,'Enable_Agent'=>1))
			   ->first();
			   $account_id = empty($accountinfo->Account_ID) ? 0 : $accountinfo->Account_ID;
				//计算出用户应得钱数
				if($Agent_Rate > 0 ){
					$total_price = $order->Order_TotalPrice;
					$record_money = $total_price*$Agent_Rate/100;
					$flag_a = $this->do_agent_award($account_id,$record_money);
					$flag_b = $this->add_agent_award_record($account_id,$record_money);
					$flag = $flag_a&&$flag_b;
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
	    
	    $area_rate = json_decode($this->shop_config['Agent_Rate'],TRUE);

	    $province_rate = $area_rate['pro']['Province'];
	    $city_rate = $area_rate['cit']['Province'];
	    $area_agent_rate = $area_rate['cou']['Province'];
	  	
		$user = $order->User()->getResults();
		if($order->Address_Province){//有收货地址,按收货地址；否则，按微信地址
			$User_Province = $order->Address_Province;
			$User_City = $order->Address_City;
			$User_Area = $order->Address_Area;
			$area_agents = Dis_Agent_Area::where('Users_ID',$order->Users_ID)
					   ->whereIn('area_id',array($User_Province,$User_City,$User_Area))
					   ->get();
		}else{
			$User_Province = trim($user->User_Province);
			$User_City = trim($user->User_City);
			$area_agents = Dis_Agent_Area::where('Users_ID',$order->Users_ID)
					   ->whereIn('area_name',array($User_Province,$User_City))
					   ->get();
		}
		$cartProduct = json_decode($order->Order_CartList, true);
		$flag = true;
		
		if (!empty($cartProduct)) 
		{
			foreach ($cartProduct as $k => $v) 
			{
				foreach ($v as $p => $productInfo) 
				{					
					//循环给每个代理商级别发放佣金
					foreach ($area_agents as $key=>$agent_area) 
					{
						//省代account_id
						if($agent_area->type == 1){
							$province_agent_id = $agent_area->Account_ID;
							$areaid = $agent_area->area_id;
							if($province_rate > 0 ){
								//计算当前产品的佣金
								$product_record_money = ($productInfo['ProductsProfit']*($productInfo['platForm_Income_Reward']/100))*($productInfo['area_Proxy_Reward']/100)*($province_rate/100)*($productInfo['Qty']);
								$flag_a = $this->do_agent_award($province_agent_id,$product_record_money);

								$flag_b = $this->add_agent_award_record($province_agent_id,$product_record_money,1,$order,$productInfo,$areaid);
								$flag = $flag_a && $flag_b;
							}
						}

						//市代account_id
						if($agent_area->type == 2){
							$city_agent_id =  $agent_area->Account_ID;
							$areaid = $agent_area->area_id;
							if($city_rate > 0 ){
								//计算当前产品的佣金
								$product_record_money = ($productInfo['ProductsProfit']*($productInfo['platForm_Income_Reward']/100))*($productInfo['area_Proxy_Reward']/100)*($city_rate/100)*($productInfo['Qty']);
								$flag_a = $this->do_agent_award($city_agent_id,$product_record_money);
								$flag_b = $this->add_agent_award_record($city_agent_id,$product_record_money,2,$order,$productInfo,$areaid);
								$flag = $flag_a && $flag_b; 
							}
						}

						//市代account_id
						if($agent_area->type == 3){
							$area_agent_id =  $agent_area->Account_ID;
							$areaid = $agent_area->area_id;
							if($area_agent_rate > 0 ){
								//计算当前产品的佣金
								$product_record_money = ($productInfo['ProductsProfit']*($productInfo['platForm_Income_Reward']/100))*($productInfo['area_Proxy_Reward']/100)*($area_agent_rate/100)*($productInfo['Qty']);
								$flag_a = $this->do_agent_award($area_agent_id,$product_record_money);
								$flag_b = $this->add_agent_award_record($area_agent_id,$product_record_money,3,$order,$productInfo,$areaid);
								$flag = $flag_a && $flag_b; 
							}
						}

					}

				}
			}
		}
	
		return $flag;
	}
	

	//计算发放股东分红佣金
    private function handle_dis_sha_info()
	{
            global $DB1;
            
            if(!empty($this->shop_config['Sha_Rate'])){
                $sha_config = $this->shop_config['Sha_Rate'];
                $Sha_Rate = json_decode($sha_config, true);
            }
            $order = $this->order;
            //获取各级股东和各级股东人数
	    $Sha_Rate_Obj = $DB1->Get('distribute_account', 'Account_ID,Real_Name,Account_Mobile,sha_level', ' where `Users_ID`="' .$order->Users_ID. '" AND `Enable_Agent`=1');
            $Sha_Rate_Count = array();
            $SHA_Account_Id_List = array();
	    while ($row = $DB1->fetch_assoc()) 
	    {
	    	$Sha_Rate_Count[$row['sha_level']]['data'][] = $row;
	    	$SHA_Account_Id_List[$row['sha_level']][] = $row['Account_ID'];
            }
            foreach ($SHA_Account_Id_List as $k => $v) {
                    $SHA_Account_Id_List[$k] = ','.implode(',', $v).',';
                    $Sha_Rate_Count[$k]['count'] = count($v);
            }
            $SHA_Account_Id_List = !empty($SHA_Account_Id_List)?$SHA_Account_Id_List:',';
             
            
	    if (count($Sha_Rate_Count) <= 0) { return; }
	    $cartProduct = json_decode($order->Order_CartList, true);
	    $User_ID = $order->User_ID;
	    $user = $this->user = User::find($User_ID);
	    $owner_id = $user->Owner_Id;
	    $flag = true;
	    
	    $accountinfo =  $DB1->GetRs('distribute_account', '*', ' WHERE `Users_ID`="' .$order->Users_ID. '" and `User_ID`='.$User_ID);
	    $account_id = $accountinfo['Account_ID'];
	    $flag_a = TRUE;
	    if (!empty($cartProduct)) 
	    {
	    	foreach ($cartProduct as $k => $v) 
		    {
		    	foreach ($v as $p => $productInfo) 
		    	{
		    		if (!empty($productInfo['sha_Reward'])) 
		    		{   
                                    //当前商品的股东分红佣金
                                    $sha_record_monery = $productInfo['Qty']*($productInfo['ProductsProfit']*($productInfo['platForm_Income_Reward']/100)*($productInfo['sha_Reward']/100));
                                    //遍历出各个级别对应的的股东
                                    foreach ($Sha_Rate_Count as $ks => $vs) {
                                        if(!empty($vs['data'])){
                                            //计算当前股东级别应该分红佣金
                                            $sha_record_monery_level = $sha_record_monery*$Sha_Rate['sha'][$ks]['Province']/100;
                                            foreach ($vs['data'] as $key => $value) {
                                                $flag_a = $this->do_agent_award($value['Account_ID'], $sha_record_monery_level/$vs['count']);
                                            }
                                            
                                        }
                                        $flag_b = $this->do_sha_award_record($accountinfo, $sha_record_monery_level, $productInfo, $vs['count'], $SHA_Account_Id_List[$ks],$Sha_Rate['sha'][$ks]['name']);
                                        
                                    }
                                    $flag = $flag_a && $flag_b;
		    		}
		    		
		    	}
		    }
	    }
		return $flag;
	}

	private function do_sha_award_record($accountinfo, $sha_record_monery, $productInfo, $sha_count, $SHA_Account_Id_List,$sha_level_name)
	{
		global $DB1;
		
		$order = $this->order;
		$dis_sha_data['Users_ID'] = $order->Users_ID; 
		$dis_sha_data['Account_ID'] = !empty($accountinfo['Account_ID'])?$accountinfo['Account_ID']:'0';
		$dis_sha_data['Record_Money'] = $sha_record_monery;
		$dis_sha_data['Record_CreateTime'] = time();
		$dis_sha_data['Sha_Qty'] = $sha_count;

		$dis_sha_data['Products_Name'] = $productInfo['ProductsName'];
		$dis_sha_data['Products_Qty'] = $productInfo['Qty'];
		$dis_sha_data['Products_PriceX'] = $productInfo['ProductsPriceX'];
		$dis_sha_data['sha_Reward'] = $productInfo['sha_Reward'];
		$dis_sha_data['Order_ID'] = $order->Order_ID; 
		$dis_sha_data['Order_CreateTime'] = $order->Order_CreateTime; 
		$dis_sha_data['Sha_Accountid'] = $SHA_Account_Id_List;
		$dis_sha_data['Real_Name'] = $accountinfo['Real_Name']; 
		$dis_sha_data['Account_Mobile'] = $accountinfo['Account_Mobile']; 
                $dis_sha_data['sha_level_name'] = $sha_level_name; 
		
		$flag = $DB1->Add('distribute_sha_rec', $dis_sha_data);
		return $flag;
	}


	/*
	*给代理人添加佣金
	*添加代理记录
	*/
	private function do_agent_award($root_id,$record_money){		
		$flag = TRUE;
		$UsersID = $this->order['Users_ID'];
		$dis_account =  Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'Account_ID'=>$root_id))->first();
		if (empty($dis_account->balance)) 
		{
			return $flag;
		}
                $balance = $dis_account->balance+$record_money;
		$Total_Income = $dis_account->Total_Income +$record_money;
		$dis_account->balance = $balance;
		$dis_account->Total_Income = $Total_Income;
 
		$flag = $dis_account->save();					 
		return $flag;
	}
	
	/**
	 *添加代理奖励记录
	 */
	private function add_agent_award_record($root_id,$record_money,$type = 1, $order = array(), $productInfo = array(),$careaid){
		$flag = TRUE;
		$dis_agent_record = new Dis_Agent_Record();
		$UsersID = $this->order['Users_ID'];
		$dis_account =  Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'Account_ID'=>$root_id))->first();
		
		$order = $this->order;
		$dis_agent_record->Users_ID = $order->Users_ID; 
		$dis_agent_record->Account_ID = $root_id;
		$dis_agent_record->Record_Money = $record_money;
		$dis_agent_record->Record_CreateTime = time();
		$dis_agent_record->Record_Type = $type;
		$dis_agent_record->area_id = $careaid;
		$dis_agent_record->Order_CreateTime = $order->Order_CreateTime;
		$dis_agent_record->Order_ID = $order->Order_ID;
		$dis_agent_record->Products_Name = $productInfo['ProductsName'];
		$dis_agent_record->Products_Qty = $productInfo['Qty'];
		$dis_agent_record->Products_PriceX = $productInfo['ProductsPriceX'];
		$dis_agent_record->area_Proxy_Reward = $productInfo['area_Proxy_Reward'];
		if (!empty($dis_account->Real_Name)) 
		{
			$dis_agent_record->Real_Name = $dis_account->Real_Name;
		}
		if (!empty($dis_account->Account_Mobile)) 
		{
			$dis_agent_record->Account_Mobile = $dis_account->Account_Mobile;
		}
		
		$flag = $dis_agent_record->save();
		return $flag;
	}	
}