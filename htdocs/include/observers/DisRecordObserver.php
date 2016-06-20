<?php
/**
 *分销记录观察者
 */
class DisRecordObserver{

	
	private $DisRecord;  //此观察者所观察的分销记录
	private $DisAccount;  //店铺拥有者的分销账号
	static  $Product;
	static  $Qty;
	static  $Order_ID;
	static  $shop_config;
		
	//创建此分销记录所产生的佣金记录
	public function created($DisRecord){
		
		$this->DisRecord = $DisRecord;
		
		if($DisRecord->type == 0){
			
			$account_records = $this->generateDistributeAccountRecord();
			
			//爵位奖
			$comm = new Commission();
			$nobi = $comm->handout_dis_commission($this->DisRecord->Users_ID,$this->DisRecord->Owner_ID,$this->DisRecord->Buyer_ID,self::$shop_config['Dis_Self_Bonus'],self::$Product,self::$Qty);
			
			$final_account_records = $this->combine_records($account_records,$nobi);

			$disAccountRecord = new Dis_Account_Record();
			$flag = $disAccountRecord ->batchAdd($final_account_records);
			
			if($flag){
			   commit_trans();
			}else{
			   back_trans();
			}
		}
	
	}
	
	
	private function combine_records($account_records,$nobi){
		foreach($account_records as $key=>$recordItem){
			if(empty($nobi[$recordItem['User_ID']])){
				$nobi[$recordItem['User_ID']] = array(
					'Nobi_Money'=>0,
					'Nobi_Description'=>'',
					'Nobi_Level'=>''
				);
			}
			$nobiItem = $nobi[$recordItem['User_ID']];
			
			$recordItem = array_merge($recordItem,$nobiItem);

			$account_records[$key] = $recordItem;
		}
		
		return $account_records;
	}
	

	/**
	 * 生成本分销记录对应分销佣金记录
	 * @return Array $dis_account_records 分销佣金记录
	 */
	
	private function generateDistributeAccountRecord(){
		
		//获取祖先id
		$UsersID = $this->DisRecord->Users_ID;
		$Owner_ID = $this->DisRecord->Owner_ID;
		$User_ID = $this->DisRecord->Buyer_ID;
		$my_level = 0;
		
		$Ds_Record_ID = $this->DisRecord->Record_ID;
		
		$level = self::$shop_config['Dis_Level'];
		$self = self::$shop_config['Dis_Self_Bonus'];		
		$self_flag = false;	 
		if($Owner_ID == $User_ID){
			$this->DisAccount =  Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$Owner_ID))
					 ->first();
			$my_level = $this->DisAccount->Level_ID;
			$ancestors = $this->DisAccount->getAncestorIds($level,0);
			$ancestors = array_reverse($ancestors);
			$meet_ancestors = $ancestors;
			if($self){
			    $self_flag = true;
				array_push($ancestors,$Owner_ID);
			}
		}else{
			$this->DisAccount =  Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$Owner_ID))
					 ->first();
			$level_parent = $level-1;
			$ancestors = $this->DisAccount->getAncestorIds($level_parent,0);
			array_push($ancestors,$Owner_ID);
			$ancestors = array_reverse($ancestors);
			$meet_ancestors = $ancestors;
		}
		
		//佣金会员筛选,并组合佣金单价
		$Product = self::$Product;
		$Qty = self::$Qty;
		$ancestors_meet = get_distribute_balance_userids($UsersID,$User_ID,$meet_ancestors,$Product['Distribute_List']);
		
		$dis_account_records = array();		

		foreach ($ancestors as $key =>$value) {
			$dis_account_record = new Dis_Account_Record();
			
			if ($User_ID == $value) {//自销
				//自己获取佣金                                
				$Record_Description = '自己销售自己购买' . $Product['Products_Name'] . '&yen;' . $Product['Products_Price']. '成功，获取奖金';
				$Record_Money = !empty($Product['Distribute_List'][$my_level][$level]) ? $Product['Distribute_List'][$my_level][$level]*$Qty : 0;
				$Record_Price = !empty($Product['Distribute_List'][$my_level][$level]) ? $Product['Distribute_List'][$my_level][$level] : 0;
			} else {
				if($Owner_ID == $value){
					$Record_Description = '自己销售下属购买' . $Product['Products_Name'] . '&yen;' .$Product['Products_Price']. '成功，获取奖金';
				}else{
					$Record_Description = '下属分销商分销' . $Product['Products_Name'] . '&yen;' .$Product['Products_Price']. '成功，获取奖金';
				}
				//上级分销商获取佣金

				if(!empty($ancestors_meet[$value])){
					if($ancestors_meet[$value]['status']==1){//正常
						$Record_Money = $ancestors_meet[$value]['bonus']*$Qty;
						$Record_Price = $ancestors_meet[$value]['bonus'];
					}else{
						$Record_Money = 0;
						$Record_Price = 0;
						$Record_Description = $ancestors_meet[$value]['msg'];
					}
				}else{
					continue;
				}
			}
			
			$dis_account_record->Record_Description = $Record_Description;
			
			
			$dis_account_record->Users_ID = $UsersID;
			$dis_account_record->Ds_Record_ID = $Ds_Record_ID;
			$dis_account_record->User_ID = $value;
			$dis_account_record->Record_Sn = build_withdraw_sn();
			$dis_account_record->level = $key+1;
			$dis_account_record->Record_Money = $Record_Money;
			$dis_account_record->Record_CreateTime = time();
			$dis_account_record->Record_Type = 0;
			$dis_account_record->Record_Status = 0;
			$dis_account_record->Record_Price = $Record_Price;
			$dis_account_record->Record_Qty = $Qty;
			$dis_account_record->CartID= $Product['CartID'];
		
			$dis_account_records[] = $dis_account_record->toArray();
		}
		
		return $dis_account_records;
		
	}
}   