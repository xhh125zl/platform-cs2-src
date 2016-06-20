<?php
namespace shop\logic;
//分销商爵位晋级类
class ProTitle {
	private $Users_ID;
	private $User_ID;
		
	public function __construct($Users_ID, $UserID){
		$this->Users_ID = $Users_ID;
		$this->User_ID =  $UserID;
	}
		
	//消费额级别
	/*
	* @param array $Pro_Title_Level	商家爵位级别设置信息 
	* @return $consume_level	消费额级别
	*/
	function get_consume_level($Pro_Title_Level = '') {
		$total = $user_consue = model('user_order')->field('SUM(Order_TotalPrice) as consueTotal')->where(array('User_ID'=>$this->User_ID,'Order_Status'=>'4'))->find();
		if(empty($Pro_Title_Level)) {
			$Pro_Title_Level = get_dis_pro_title($this->Users_ID);
		}
			
		$Pro_Title_Level = empty($Pro_Title_Level) ? array() : array_reverse($Pro_Title_Level, true);
			
		$consume_level = 0;
			
		foreach($Pro_Title_Level as $k => $v){
			if(isset($v['Consume']) && $total >= $v['Consume']) {
				$consume_level = $k;
				break;
			}
		}
		return $consume_level;
	}
		
	//直销级别
	/*
	* @param array $Pro_Title_Level	商家爵位级别设置信息 
	* @return $son_level	直销级别
	*/
	function get_son_level($Pro_Title_Level = ''){
		if(empty($Pro_Title_Level)){
			$Pro_Title_Level = get_dis_pro_title($this->Users_ID);
		}
		$Pro_Title_Level = empty($Pro_Title_Level) ? array() : array_reverse($Pro_Title_Level, true);
		//直销人数

		$num = model('distribute_account')->where(array('invite_id'=>$this->User_ID))->total();

		$son_level = 0;
		foreach($Pro_Title_Level as $k => $v){
			if(isset($v['Saleroom']) && $num >= $v['Saleroom']) {
				$son_level = $k;
				break;
			}
		}
		return $son_level;
	}
	//团队级别
	/*
	* @param array $Pro_Title_Level	商家爵位级别设置信息 
	* @return $group_level	团队级别
	*/
	function get_group_level($Pro_Title_Level = ''){
		if(empty($Pro_Title_Level)){
			$Pro_Title_Level = get_dis_pro_title($this->Users_ID);
		}
		$Pro_Title_Level = empty($Pro_Title_Level) ? array() : array_reverse($Pro_Title_Level, true);

		$shop_distribute_account = model('distribute_account');

		$num = $shop_distribute_account->where(array('Dis_Path'=>'%' . $this->User_ID . ',%'))->total();
		$group_level = 0;
		foreach($Pro_Title_Level as $k => $v){
			if(isset($v['Group_Num']) && $num >= $v['Group_Num']) {
				$group_level = $k;
				break;
			}
		}
		return $group_level;
	}
		
	//获取当前分销商爵位级别
	function get_nobility_level(){

		$md = model('distribute_account')->where(array('User_ID'=>$this->User_ID))->find();

		$nobility = 0;
		if(!empty($md['professional_title'])) {
			$nobility = $md['professional_title'];
		}
		return $nobility;//(爵位)
	}
		
	//级别晋升
	/*
	* @param int $level	分销商爵位级别 
	* @param array $Pro_Title_Level	商家爵位级别设置信息 
	*/
	function up_nobility_level($level = '', $Pro_Title_Level = array()) {
			
		if(empty($level)) {
			$level = $this->get_nobility_level();
		}
			
		if(empty($Pro_Title_Level)) {
			$Pro_Title_Level = get_dis_pro_title($this->Users_ID);
		}
			
		$level_sort['consume'] = $this->get_consume_level($Pro_Title_Level);
		$level_sort['group'] = $this->get_group_level($Pro_Title_Level);
		$level_sort['son'] = $this->get_son_level($Pro_Title_Level);
		asort($level_sort);
			
		$my_now_level = current($level_sort);
		$data['level'] = $my_now_level;
		$data['pro_name'] = isset($Pro_Title_Level[$my_now_level]) ? $Pro_Title_Level[$my_now_level] : '你还没有爵位';
		if($my_now_level > $level) {

			$Flag = model('distribute_account')->where(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))->update(array('Professional_Title'=>$my_now_level));

			if($Flag) {
				$data['status'] = 1;
				return $data;//级别晋升成功
			}else {
				$data['status'] = 2;
				return $data;//级别晋升失败
			}
		}else {
			$data['status'] = 3;
			return $data;//没达到晋升条件
		}
	}
}