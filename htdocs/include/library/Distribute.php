<?php
class Distribute
{
	private $DB; //数据库
	public  $account_list; //分销商列表
	public  $account_tree;  //分销商列表树
	public  $account_level_list; //含等级的分销商列表
	public  $user_list; //用户列表
	private $Users_ID;
	private $_temp_childs;
	
	/*构造函数*/
	function __construct($DB,$Users_ID){
		
		$this->DB = $DB;
		$this->Users_ID = $Users_ID;
		$this->account_list = $this->_get_account_list();
		$this->account_tree = $this->_get_dis_tree();
		$this->user_list= $this->_get_user_list();
		$this->account_level_list = $this->_orange_level();
		
	}
	
	
	public function get_posterity($UserID = 0){
	
		$my_account_level_list = $this->_orange_level($UserID);
		return $my_account_level_list;
	}
	
	
	private function _orange_level($UserID =0 ){
	
		$user_dropdown = get_dropdown_list($this->user_list,'User_ID','User_NickName');
		$ds_dropdown = get_dropdown_list($this->account_list,'User_ID');
		
		$level1 = $level2 = $level3 = array();
		
		foreach($ds_dropdown as $key=>$item){
			
			if($item['invite_id'] == $UserID){	
				if(!empty($user_dropdown[$key])){
					$item['User_Name'] = $user_dropdown[$key];	
				}else{
				    $item['User_Name'] = '无昵称';
				}
				
				$level1[$item['User_ID']] = $item;
			}
		
		}
	
		
		$leve1_ids = array_keys($level1);
		
		foreach($ds_dropdown as $key=>$item){
			if(in_array($item['invite_id'],$leve1_ids)){
				if(!empty($user_dropdown[$key])){
					$item['User_Name'] = $user_dropdown[$key];
				}else{
					$item['User_Name'] = '无昵称';
				}
			
				$level2[$item['User_ID']] = $item;
			}
		}

		$level2_ids = array_keys($level2);

		foreach($ds_dropdown as $key=>$item){
			if(in_array($item['invite_id'],$level2_ids)){
				if(!empty($user_dropdown[$key])){
					$item['User_Name'] = $user_dropdown[$key];
				}else{
					$item['User_Name'] = '无昵称';
				}
				$level3[$item['User_ID']] = $item;
			}
		}
	
		$level_list = array(1=>$level1,2=>$level2,3=>$level3);
		return $level_list;
	
	}
	
	
	/**
	 * @param int $OwnerID 店铺拥有者用户ID
	 * @param int $UserID 用户ID
	 * @return array $ancestor 祖先id数组
	 */
	public function get_ancestor($OwnerID,$UserID){
		
		$ancestor_list = array();
		if(!empty($this->account_tree)){
			$ancestor_list = $this->account_tree->navi($OwnerID);
		}
		
		if(!empty($ancestor_list)){
			//返回数组中前三个元素
			ksort($ancestor_list);
			//如果是自己在自己的店购买，自己不得佣金
			if($UserID == $OwnerID){
				array_shift($ancestor_list);
			}
		
			while(count($ancestor_list)>3){
				array_pop($ancestor_list);
			}
		}
		
		return $ancestor_list;
	}
	
	public function update_group_num($UserID){

		$posterity_list = $this->_orange_level($UserID);;
		$posterity_count = 1;
		foreach($posterity_list as $key=>$sub_list){
			$posterity_count += count($sub_list); 	
		}
		$condition = "where Users_ID = '".$this->Users_ID."'"." and User_ID=".$UserID;
		$this->DB->Set('distribute_account',array('Group_Num'=>$posterity_count),$condition);
		
		echo '用户'.$UserID.'更新成功';
	
	}
	
	/**
	 * 更新祖先用户组数量
	 * @param int $UserID
	 * @return boolean
	 */
	public function update_ancestor_group_num($OwnerID,$UserID){
		
		$this->account_list = $this->_get_account_list(); //更新account_list
		
		$ancestor = $this->get_ancestor($OwnerID,$UserID);
		
		$Flag = TRUE;
		
		if(!empty($ancestor)){
			foreach($ancestor as $key=>$item){
			//增加祖先团队数量，若符合升级条件，则升级并给予额外奖励
				$item_flag = up_professional_title_by_group_num($this->DB,$this->Users_ID,$item['User_ID'],$this->account_list);
				
				if(!$item_flag){
					$Flag = FALSE;
					break;
				}	
			}
		}
		
		return $Flag;

	}
	/**
	 * 更新祖先团队销售额
	 * @param int $UserID
	 * @return boolean
	 */
	public function update_group_sales($Order_ID,$Product_ID){
		
		$condition = "where Users_ID='".$this->Users_ID."' and Order_ID=".$Order_ID;
		$condition .= " and Product_ID =".$Product_ID;
		
		$rsOrderSales = $this->DB->getRs('distribute_record','Buyer_ID,Owner_ID,SUM(Product_Price) as sum',$condition);
		
		$members = $this->get_ancestor($rsOrderSales['Owner_ID'],$rsOrderSales['Buyer_ID']);
		$ds_dropdown = get_dropdown_list($this->account_list,'User_ID');
	
		$Flag = TRUE;
		
		foreach($members as $key=>$item){
		//增加团队成员销售额，若符合升级条件，则升级并给予额外奖励
		$item_flag = up_professional_title_by_group_sales($this->DB,$this->Users_ID,$item['User_ID'],$ds_dropdown,$rsOrderSales['sum'],$rsOrderSales['Owner_ID']);
			
			if(!$item_flag){
				$Flag = FALSE;
				break;
			}	
		}
		
		return $Flag;
		
	}

	
		
	
	/**
	 *更新Distribute 类
	 */
	public function refresh(){
		
		$this->account_list = $this->_get_account_list();
		$this->account_tree = $this->_get_dis_tree();
	
	}
	/**
	 * 获取指定分销商下属分销商列表
	 * @param  int $UserID 指定分销商UserID
	 * @return array  $account_list   下属分销商列表
	 */
	private function _get_account_list(){
		$fields = 'User_ID,invite_id,Real_Name,Group_Num,Up_Group_Num,Professional_Title,Ex_Bonus'.
		',last_award_income,Total_Income,Total_Sales,Up_Group_Sales,Group_Sales,Shop_Name,balance,Account_CreateTime,Is_Audit';
	
		$condition = "where Users_ID='".$this->Users_ID."'";
		$rsAccount = $this->DB->get('distribute_account',$fields,$condition);				 
		$ds_list = $this->DB->toArray($rsAccount);
	
		return $ds_list;
	}

	/**
	 * 获取分销树
	 * @return [type] [description]
	 */
	private function _get_dis_tree(){

		$account_tree = null;
		
		if(count($this->account_list)>0){
			
			$param = array('result'=>$this->account_list,'fields'=>array('User_ID','invite_id'));
			$account_tree = new General_tree($param);
		}
		
		return $account_tree;
	}
	
	private function _get_user_list(){
		$rsUsers = $this->DB->get('user','User_ID,User_NickName',"where Is_Distribute = 1 and Users_ID='".$this->Users_ID."'");
		$user_list = $this->DB->toArray($rsUsers);
		
		return $user_list;
	}
	
	/**
	 * 获得分销商称号列表
	 * @return Array $rsDsConfig 分销商配置
	 *  */
	private function _get_dis_pro_title(){
		$rsDsConfig = $this->db->GetRs('distribute_config','Pro_Title_Level',"where Users_ID='".$this->Users_ID."'");
	
		$pro_titles = false;
	
		if($rsDsConfig){
			$pro_titles = json_decode($rsDsConfig['Pro_Title_Level'],TRUE);
		}
	
	
		return $pro_titles;
	}
}
?>