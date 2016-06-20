<?php
namespace shop\logic;
/**
 *分销账号观察者
 */
class DisAccountObserver {

	public $cur_ds_account;
	private $account_list;
	private $shop_config;

	/**
	 *分销账号创建时的相应操作
	 */
	public function created($ds_account) {
		$this->cur_ds_account = $ds_account;
		$flag_b = $this->handle_group_num();
		return $flag_b;
	}

	/*
	 *处理分销团队人数,若符合升级条件，则升级并给予额外奖励
	 *
	 */
	private function handle_group_num() {

		$UsersID = $this->cur_ds_account['Users_ID'];
		$Owner_ID = $this->cur_ds_account['Owner_ID'];
		$User_ID = $this->cur_ds_account['User_ID'];
		
		$level = $this->shop_config['dis_level'];
		$ancestors = getAncestorIds($level, $this->cur_ds_account);
		
		if (!empty($ancestors)) {
			foreach ($ancestors as $key => $Ancestor_User_ID) {
				//增加祖先团队数量，若符合升级条件，则升级并给予额外奖励
				$item_flag = $this->up_professional_title_by_group_num($Ancestor_User_ID);

				if (!$item_flag) {
					return false;
				}
			}
		}
		return true;
	}


	public function up_professional_title_by_group_num($UserID) {

		$ds_account_obj = model('distribute_account');

		$Users_ID = $this->cur_ds_account['Users_ID'];
		$pro_titles = get_dis_pro_title($Users_ID);

		$ds_account = $ds_account_obj->field('Group_Num,Account_ID')->where(array('Users_ID'=>$Users_ID,'User_ID'=>$UserID))->find();
		$data = array();
		$data['Group_Num'] = $ds_account['Group_Num'] + 1;

		$Flag = TRUE;
		if (!empty($pro_titles)) {
			$pro = new \shop\logic\ProTitle($Users_ID, $UserID);
			$flag = $pro->up_nobility_level();
		}
		
		$account_id = $ds_account['Account_ID'];
		$Flag = $ds_account_obj->where(array('Account_ID'=>$account_id))->update($data);
		return $Flag;
	}
}
