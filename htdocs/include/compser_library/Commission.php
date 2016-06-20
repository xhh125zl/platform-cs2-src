<?php
//佣金发放类
use Illuminate\Database\Capsule\Manager as Capsule;
class Commission {
		
		//获取分销商爵位级别设置信息
		function get_nobility_config($usersid){
			$Pro_Title_Level = array();
			$md = Dis_Config::where('Users_ID',$usersid)->first(array('Pro_Title_Level'));
			if(!empty($md->Pro_Title_Level)){
				$Pro_Title_Level = json_decode($md->Pro_Title_Level,true);
				krsort($Pro_Title_Level);
			}			
			return $Pro_Title_Level;
		}
		
		//获取上级
		function get_parents($ower_id){
			$cc = Dis_Account::where('User_ID',$ower_id)
			            ->first()
						->Dis_Path;
			return $cc;
		}
		
		//分销级别佣金/爵位奖发放
		/*
		*	$orderid 可为订单id 或订单内容
		*/
		function handout_dis_commission($UsersID,$OwnerID,$BuyerID,$dis_self_bonus,$products,$qty){
			
			//获取爵位级别数组
			$Pro_Title_Level = $this->get_nobility_config($UsersID);
			if(empty($Pro_Title_Level)){
				return array();
			}
			//产品爵位计算			
			
			$nobi_ratio = $products['nobi_ratio'];//佣金/爵位奖比例
			if($nobi_ratio >100){
				$nobi_ratio = 100;
			}elseif($nobi_ratio<0){
				$nobi_ratio = 0;
			}
			$profit = $qty*$products['Products_Profit'];
			$nobility = $profit*$nobi_ratio*0.01*($products['platForm_Income_Reward']/100);//爵位奖励    $products['platForm_Income_Reward'] => 平台利润的发放比例
			
			//取上级信息
			$parents = $this->get_parents($OwnerID);//上级分销商
			$parents_arr = explode(',',$parents);
			$psa = array_filter($parents_arr);
			if($OwnerID==$BuyerID){//自销开启，并且此行为是自销行为
				if($dis_self_bonus){
					array_push($psa,$OwnerID);
				}
			}else{
				array_push($psa,$OwnerID);
			}
			
			if(empty($psa)){
				return array();
			}
			
			$psars = array_reverse($psa);			
			$nobility_level_bak = Dis_Account::wherein('User_ID',$psars)->where('Users_ID',$UsersID)->get(array('User_ID','Professional_Title'))->toArray();
			$nobility_level = array();			
			foreach($nobility_level_bak as $k=>$v){
				$nobility_level[$v['User_ID']] = $v['Professional_Title'];
			}

			$nobility_commission = array();
			//临时变量
			$level_temp = $bonus_temp = 0;
			foreach($nobility_level as $key=>$value){
				if($profit==0){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'该商品无爵位奖','Nobi_Money'=>0,'Nobi_Level'=>'该商品无爵位奖');
					continue;
				}
				
				if($value==0){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'您还没有爵位','Nobi_Money'=>0,'Nobi_Level'=>'无爵位');
					continue;
				}
				
				if(empty($Pro_Title_Level[$value]["Name"])){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'商家未设置该爵位奖','Nobi_Money'=>0,'Nobi_Level'=>'爵位有误');
					continue;
				}
				
				if($value<=$level_temp){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'你的下级爵位级别比你的高，无爵位奖金','Nobi_Money'=>0,'Nobi_Level'=>$Pro_Title_Level[$value]['Name']);
					continue;
				}
				
				$money_temp = $nobility*$Pro_Title_Level[$value]['Bonus']*0.01;
				$nobility_commission[$key] = array(
					'User_ID'=>$key,
					'Nobi_Description'=>$Pro_Title_Level[$value]['Name'].'奖金',
					'Nobi_Money'=>$money_temp > $bonus_temp ? ($money_temp-$bonus_temp) : 0,
					'Nobi_Level'=>$Pro_Title_Level[$value]['Name']
				);
				if($money_temp > $bonus_temp){
					$level_temp = $value;
					$bonus_temp = $money_temp;
				}
				
			}
			
			return $nobility_commission;
		}
	}