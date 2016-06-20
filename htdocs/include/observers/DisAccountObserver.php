<?php
/**
 *分销账号观察者
 */
class DisAccountObserver {

	public $ds_account;
	private $account_list;
	static $shop_config;

	/**
	 *分销账号创建时的相应操作
	 */
	public function created($ds_account) {

		$this->ds_account = $ds_account;
		$this->get_account_list($ds_account->Users_ID);

		$User_ID = $ds_account->User_ID;

		//将用户身份改为分销商
		$flag_a = User::find($User_ID)->update(array('Is_Distribute' => 1));
		$flag_b = $this->handle_group_num();
		
		if($flag_a && $flag_b){
			commit_trans();
		}else{
			back_trans();
		}
		
	
		return $flag_a && $flag_b;
	}

	/*
	 *处理分销团队人数,若符合升级条件，则升级并给予额外奖励
	 *
	 */
	private function handle_group_num() {

		$UsersID = $this->ds_account->Users_ID;
		$Owner_ID = $this->ds_account->Owner_ID;
		$User_ID = $this->ds_account->User_ID;
		
		$level = self::$shop_config['Dis_Level'];
		
		$ancestors = $this->ds_account->getAncestorIds($level,0);
		
		$Flag = TRUE;
		if (!empty($ancestors)) {
			foreach ($ancestors as $key => $Ancestor_User_ID) {
				//增加祖先团队数量，若符合升级条件，则升级并给予额外奖励
				$item_flag = $this->up_professional_title_by_group_num($Ancestor_User_ID);

				if (!$item_flag) {
					$Flag = FALSE;
					break;
				}
			}
		}
		return $Flag;

	}


	public function up_professional_title_by_group_num($UserID) {
		
		$Users_ID = $this->ds_account['Users_ID'];
		$pro_titles = Dis_Config::get_dis_pro_title($Users_ID);
		
		$ds_account_obj =  Dis_Account::Multiwhere(array('Users_ID'=>$Users_ID,'User_ID'=>$UserID))->first();
		$ds_account = $ds_account_obj->toArray();
		$data = array();
		$data['Group_Num'] = $ds_account['Group_Num'] + 1;

		$Flag = TRUE;
		
		$account_id = $ds_account['Account_ID'];
		$Flag = $ds_account_obj->update($data);

		return $Flag;

	}

	/**
	 *
	 * @param String $UsersID 店铺唯一标示
	 * @return Array此店所有分销账号列表
	 */
	private function get_account_list($UsersID) {

		$fields = array('Users_ID', 'User_ID', 'invite_id', 'User_Name', 'Account_ID',
			'Shop_Name', 'Group_Num', 'Up_Group_Num', 'Ex_Bonus', 'last_award_income',
			'Professional_Title');
		$account_list = Dis_Account::where(array('Users_ID' => $UsersID))
			->get($fields)
			->toArray();
		$this->account_list = $account_list;
	}

}
