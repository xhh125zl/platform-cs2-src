<?php
namespace shop\logic;
/**
 *分销记录观察者
 */
class DisRecordObserver{

	
	private $DisRecord;  //此观察者所观察的分销记录
	private $DisAccount;  //店铺拥有者的分销账号
	public  $Product;
	public  $Qty;
	public  $Order_ID;
	public  $shop_config;
		
	//创建此分销记录所产生的佣金记录
	public function created($Record_ID) {

                $this->DisRecord = model('distribute_record')->field('*')->where(array('Record_ID'=>$Record_ID))->find();

		$account_records = $this->generateDistributeAccountRecord();//二维数组
		//爵位奖
		$comm = new \shop\logic\Commission();
		$nobi = $comm->handout_dis_commission($this->Order_ID, $this->Product, $this->Qty);
		$final_account_records = $this->combine_records($account_records, $nobi);
		//组装批量插入sql语句
		if($final_account_records){

			//$sql = 'INSERT INTO shop_distribute_account_record ';
                        $sql = 'INSERT INTO distribute_account_record ';

			$files = $value = $values = '';
			foreach($final_account_records as $key => $val){
				$value = '';
				$files = implode(',', array_keys($val));
				foreach($val as $k => $v){
					$value .= '\''.$v . '\',';
				}
				$values .= '(' . trim($value, ',') . '),';
			}
			$sql .= '(' . $files . ') VALUES ' . trim($values, ',');
			model()->query($sql, 'insert');
		}
	}
	
	
	private function combine_records($account_records, $nobi){

		foreach($account_records as $key => $recordItem){
			
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

                $Dis_Account_model = model('distribute_account');

		//获取祖先id
		$UsersID = $this->DisRecord['Users_ID'];
		$Owner_ID = $this->DisRecord['Owner_ID'];
		$Buyer_ID = $this->DisRecord['Buyer_ID'];		 
		$Ds_Record_ID = $this->DisRecord['Record_ID'];

		$level = $this->shop_config['Dis_Level'];
		$self = $this->shop_config['Dis_Self_Bonus'];//是否开启自销	
		$self_flag = false;
		//自销
		if($Owner_ID == $Buyer_ID) {
			$this->DisAccount = $Dis_Account_model->field('*')->where(array('Users_ID'=>$UsersID,'User_ID'=>$Owner_ID))->find();
			$ancestors = getAncestorIds($level, $this->DisAccount);
			$ancestors_rsort = array_reverse($ancestors);
			if($self) {
			    $self_flag = true;
				array_push($ancestors_rsort, $Owner_ID);
			}
		}else {
			$this->DisAccount = $Dis_Account_model->field('*')->where(array('Users_ID'=>$UsersID,'User_ID'=>$Owner_ID))->find();
			$level_parent = $level - 1;
			//获取此分销账号的祖先id列表
			$ancestors = getAncestorIds($level_parent, $this->DisAccount);
			array_push($ancestors, $Owner_ID);
			$ancestors_rsort = array_reverse($ancestors);
		}
		
		$ancestors_meet = $this->getUserfulAncestor($ancestors, $self_flag, $Buyer_ID);//获取符合分销佣金限制的分销商
		$dis_account_records = array();
		
		$Product = $this->Product;
		$Qty = $this->Qty;

                $my_level = $this->DisAccount['Level_ID'];
		$Distribute_List = $Product['Distribute_List'];

		foreach ($ancestors_rsort as $key => $value) {
			if ($Buyer_ID == $value) {//自销
				//自己获取佣金                                
				$Record_Description = '自己销售自己购买' . $Product['Products_Name'] . '&yen;' . $Product['Products_Price']. '成功，获取奖金';

				$Record_Money = !empty($Distribute_List[$my_level][$level]) ? $Distribute_List[$my_level][$level] * $Qty : 0;
				$Record_Price = !empty($Distribute_List[$my_level][$level]) ? $Distribute_List[$my_level][$level] : 0;

			} else {
				if($Owner_ID == $value) {
					$Record_Description = '自己销售下属购买' . $Product['Products_Name'] . '&yen;' .$Product['Products_Price'] .  '成功，获取奖金';
				}else {
					$Record_Description = '下属分销商分销' . $Product['Products_Name'] . '&yen;' . $Product['Products_Price']. '成功，获取奖金';
				}
				//上级分销商获取佣金
				

				$Record_Money = !empty($Distribute_List[$my_level][$key]) ? $Distribute_List[$my_level][$key] * $Qty : 0;
				$Record_Price = !empty($Distribute_List[$my_level][$key]) ? $Distribute_List[$my_level][$key] : 0;
			}
			 

			if(!in_array($value, $ancestors_meet)) {
				$Record_Money = 0;
				$dis_account_records[$key]['Record_Description'] = '没达到获利条件';
			} else {
				$dis_account_records[$key]['Record_Description'] = $Record_Description;
			}
			
			$dis_account_records[$key]['Users_ID'] = $UsersID;
			$dis_account_records[$key]['Ds_Record_ID'] = $Ds_Record_ID;
			$dis_account_records[$key]['User_ID'] = $value;
			$dis_account_records[$key]['Record_Sn'] = build_withdraw_sn();
			$dis_account_records[$key]['level'] = $key + 1;
			$dis_account_records[$key]['Record_Money'] = $Record_Money;
			$dis_account_records[$key]['Record_CreateTime'] = time();
			$dis_account_records[$key]['Record_Type'] = 0;
			$dis_account_records[$key]['Record_Status'] = 0;
			$dis_account_records[$key]['Record_Price'] = $Record_Price;
			$dis_account_records[$key]['Record_Qty'] = $Qty;
			$dis_account_records[$key]['CartID'] = $Product['CartID'];
		}

		return $dis_account_records; 

	}
	
	
	/**
	 * 获取符合分销佣金限制的分销商
	 * @param  [type] $ids [description] //上级ids
	 * @return [type]      [description]
	 */
	function getUserfulAncestor($ids, $self_flag, $userid){
		$user_model = model('user');
		$shop_config = $this->shop_config;

		//$bonusLimit =  json_decode($shop_config['Dis_Bonus_Limit'], true);
                $bonusLimit =  json_decode($shop_config['Distribute_Limit'], true);


		$res = array();
		if($self_flag){
			$userinfo = $user_model->field('User_Cost')->where(array('User_ID'=>$userid))->find();
			$count = count($bonusLimit);
			if(($bonusLimit[$count]['Enable'] == 0) || ($bonusLimit[$count]['Enable'] == 1 && floatval($userinfo['User_Cost']) >= floatval($bonusLimit[$count]['Cost']))) {
				$res[] = $userid;
			}
		}
		
		if(!empty($ids)) {
		    $costList = $user_model->field('User_ID,User_Cost')->where(array('User_ID'=>$ids))->order('User_ID desc')->select();		
		
			foreach($costList as $key => $user){
				$cur_level = $key + 1;
				if($bonusLimit[$cur_level]['Enable'] == 0 || (floatval($user['User_Cost']) >= floatval($bonusLimit[$cur_level]['Cost']) && $bonusLimit[$cur_level]['Enable'] == 1)){		    
					$res[] = $user['User_ID'];
				}
			}
		}
		return $res;
	}
}  