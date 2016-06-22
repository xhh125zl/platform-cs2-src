<?php
//分销商爵位晋级类
// use Illuminate\Database\Capsule\Manager as DB;
class ProTitle {
		
		private $Users_ID;
		private $User_ID;
		
		public function __construct($Users_ID,$UserID){
			$this->Users_ID = $Users_ID;
			$this->User_ID =  $UserID;
		}
		
		//获取下级
		function get_sons($level,$UID){
			$temp = Dis_Account::where('Dis_Path','like','%,'.$UID.',%')->get(array('User_ID','Dis_Path'))
					->map(function($account){
						return $account->toArray();
					})->all();
			$list = array();

			foreach($temp as $t){
				$dispath = substr($t['Dis_Path'],1,-1);
				$arr = explode(',',$dispath);
				$key = array_search($UID,$arr);
				
				if(count($arr)-$key <= $level){
					$list[] = $t['User_ID'];
				}else{
					continue;
				}
			}

			return $list;
		}
		
		//级别晋升
		/*
		* @param int $level	分销商爵位级别 
		* @param array $Pro_Title_Level	商家爵位级别设置信息
		*/
		/*function up_nobility_level(){
			$dis_config = Dis_Config::where('Users_ID',$this->Users_ID)->first();
			if(empty($dis_config->Pro_Title_Level)){
				return true;
			}
			
			$protitles = json_decode($dis_config->Pro_Title_Level,true);			
			$protitles_temp = array_reverse($protitles,true);
			
			foreach($protitles as $pk=>$pv){
				if(!empty($pv['Name'])){
					$protitles[$pk]=$pv;
				}
			}
			
			$disaccount =  Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))
					 ->first();
			if(empty($disaccount->Users_ID)){
				return true;
			}
			$ancestors = $disaccount->getAncestorIds($dis_config->Dis_Level,0);
			array_push($ancestors,$this->User_ID);
			$ancestors = array_reverse($ancestors);
			
			foreach($ancestors as $UID){
				$user_distribute_account = Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$UID))
					 ->first(array('Professional_Title'));
				if($user_distribute_account->Professional_Title == count($protitles)){//已经是最高级
					continue;
				}elseif($user_distribute_account->Professional_Title > count($protitles)){//大于最高级
					Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$UID))->update(array('Professional_Title'=>count($protitles)));
					continue;
				}else{
					//自身消费额
					$Consume = Order::where(array('User_ID'=>$UID,'Order_Status'=>$dis_config->Pro_Title_Status))->sum('Order_TotalPrice');
					//自身销售额
					$Sales_Self = Order::where(array('Owner_ID'=>$UID,'Order_Status'=>$dis_config->Pro_Title_Status))->sum('Order_TotalPrice');

					//下级会员
					$childs = $this->get_sons($dis_config->Dis_Level,$UID);

					if(!empty($childs)){
						$Sales_Group = Order::where(array('Order_Status'=>$dis_config->Pro_Title_Status))->whereIn('Owner_ID',$childs)->sum('Order_TotalPrice');
					}else{
						$Sales_Group = 0;
					}
					
					$level = 0;
					foreach($protitles as $key=>$item){
						if($item['Sales_Group']<=$Sales_Group && $item['Sales_Self']<=$Sales_Self && $item['Consume']<=$Consume){
							$level = $key;
							break;
						}
					}

					if($level > $user_distribute_account->Professional_Title){
						Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$UID))->update(array('Professional_Title'=>$level));
						continue;
					}
				}
			}
			
			return true;
		}*/
		
		function up_nobility_level(){
			$dis_config = Dis_Config::where('Users_ID',$this->Users_ID)->first();
			if(empty($dis_config->Pro_Title_Level)){
					return true;
			}

			$protitles = json_decode($dis_config->Pro_Title_Level,true);
			$protitles_temp = array_reverse($protitles,true);
			foreach($protitles as $pk=>$pv){
					if(!empty($pv['Name'])){
							$protitless[$pk]=$pv;
					}
			}
			$disaccount =  Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$this->User_ID))
							 ->first();
			if(empty($disaccount->Users_ID)){
					return true;
			}

			$ancestors = $disaccount->getAncestorIds($dis_config->Dis_Level,0);
			array_push($ancestors,$this->User_ID);
			$ancestors = array_reverse($ancestors);
			foreach($ancestors as $UID){
				$user_distribute_account = Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$UID))->first(array('Professional_Title'));
				 
					//自身消费额
					//$Consume = Order::where(array('User_ID'=>$UID,'Order_Status'=>4))->sum('Order_TotalPrice');
					$Consume = Order::where(array('User_ID'=>$UID))->where('Order_Status','>=',$dis_config->Pro_Title_Status)->sum('Order_TotalPrice');
					
					//自身销售额
					$Sales_Self = Order::where(array('Owner_ID'=>$UID))->where('Order_Status','>=',$dis_config->Pro_Title_Status)->sum('Order_TotalPrice');

					//下级会员
					$childs = $this->get_sons($dis_config->Dis_Level,$UID);

					if(!empty($childs)){
							$Sales_Group = Order::where('Order_Status','>=',$dis_config->Pro_Title_Status)->whereIn('Owner_ID',$childs)->sum('Order_TotalPrice');
					}else{
							$Sales_Group = 0;
					}

					$level = 0;
					 
					foreach($protitless as $key=>$item){
							if($item['Sales_Group']<=$Sales_Group && $item['Sales_Self']<=$Sales_Self && $item['Consume']<=$Consume){

									$level = $key;
									//break;
							}
					}
							 
						Dis_Account::Multiwhere(array('Users_ID'=>$this->Users_ID,'User_ID'=>$UID))->update(array('Professional_Title'=>$level));
						continue;
				
			}

			return true;
		}
}