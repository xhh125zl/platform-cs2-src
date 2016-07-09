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
		
		//数组倒序排列键值不变
		function array_reverse_order($array){
 			$array_key = array_keys($array);
 			$array_value = array_values($array);

 			$array_return = array();
 			for($i=1, $size_of_array=sizeof($array_key);$i<=$size_of_array;$i++){
 			    $array_return[$array_key[$size_of_array-$i]] = $array_value[$size_of_array-$i];
 			}

 			return $array_return;
		}

		/**
		 * 获取“得到爵位佣金的”和“没有得到爵位佣金"的上级数组
		 * @param  array $array [description]
		 * @return array        [description]
		 */
		function deal_nobility_level($array) {
			$max = 0;
			$commission_array = array();

			foreach ($array as $key => $value) {
				if ($max >= $value) {
					$commission_array[$key] = $value;
					unset($array[$key]);
				} else {
					$max = $value;
				}
			}

			$result = array(
				'max' => $array,	//有爵位数组
				'min' => $commission_array,	//没有爵位数组
			);

			return $result;
		}

		//得到爵位佣金的上级数组
		function delete_commission($array){
 			$max = 0;
 			$commission_array = array();
 			foreach($array as $key=>$vv){
 			    $i = 1;
 			    if($i == 1){

 			        $conn = $vv;
 			    }else{
 			        $conn = $max;
 			    }
 			    $conn = $max;
 			    if($conn >= $vv){
 			        $commission_array[$key] = $array[$key];
 			        unset($array[$key]);
 			    }
 			    $max = $vv;
 			    $i++;
 			}
 			return $array;
		}
		
			
		//没有得到爵位佣金的上级数组
		function remove_commission($array){
 			$max = 0;
 			$commission_array = array();
 			foreach($array as $key=>$vv){
 			    $i = 1;
 			    if($i == 1){
 			        $conn = $vv;
 			    }else{
 			        $conn = $max;
 			    }
 			    $conn = $max;
 			    if($conn >= $vv){
 			        $commission_array[$key] = $array[$key];
 			        unset($array[$key]);
 			    }
 			    $max = $vv;
 			    $i++;
 			}
 			return $commission_array;
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
			
			$nobility_ziji = Dis_Account::wherein('User_ID',array($_SESSION[$UsersID.'User_ID']))->where('Users_ID',$UsersID)->get(array('User_ID','Professional_Title'))->toArray();
			$nobility_level = array();			
			foreach($nobility_level_bak as $k=>$v){
				$nobility_level[$v['User_ID']] = $v['Professional_Title'];
			}
            
			//$nobility_level = $this->array_reverse_order($nobility_level);
			$nobility_level = array_reverse($nobility_level, TRUE);
			
			//$nobility_level_max = $this->delete_commission($nobility_level);
			//$nobility_level_min = $this->remove_commission($nobility_level);
			$deal_nobility_level = $this->deal_nobility_level($nobility_level);
			$nobility_level_max = $deal_nobility_level['max'];
			$nobility_level_min = $deal_nobility_level['min'];
						
			$nobility_commission = array();
			//临时变量
			$level_temp = $bonus_temp = 0;
			foreach($nobility_level_max as $key=>$value){
				if($nobility==0){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'该商品无爵位奖','Nobi_Money'=>0,'Nobi_Level'=>'该商品无爵位奖');
					continue;
				}
				if($value==0){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'您还没有爵位','Nobi_Money'=>0,'Nobi_Level'=>'无爵位');
					continue;
				}
				if(empty($Pro_Title_Level[$value]['Name'])){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'商家未设置该爵位奖','Nobi_Money'=>0,'Nobi_Level'=>'爵位有误');
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


			foreach($nobility_level_min as $key=>$value){
				if($value==0){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'您还没有爵位','Nobi_Money'=>0,'Nobi_Level'=>'无爵位');
					continue;
				}
				
				if(empty($Pro_Title_Level[$value]['Name'])){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'商家未设置该爵位奖','Nobi_Money'=>0,'Nobi_Level'=>'爵位有误');
					continue;
				}
				
				if($value>0){
					$nobility_commission[$key] = array('User_ID'=>$key,'Nobi_Description'=>'你的下级爵位级别比你的高，无爵位奖金','Nobi_Money'=>0,'Nobi_Level'=>$Pro_Title_Level[$value]['Name']);
					continue;
				}
			}
			
			return $nobility_commission;
		}
	}