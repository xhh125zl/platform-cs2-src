<?php
//业务发放类
use Illuminate\Database\Capsule\Manager as Capsule;
			/* Capsule::enableQueryLog();
			$logs = Capsule::getQueryLog();
			var_dump($product); */
class Salesman_commission {

		
		//获取上级
		function get_parents($biz_code){
			$cc = Dis_Account::where('Invitation_Code',$biz_code)
			            ->first();
			return $cc;
		}
		
		//取分销级别信息
		function get_dis_level($Users_ID){
			$dis_level = Shop_Config::where('Users_ID',$Users_ID)->first();
			return $dis_level;
		}
		
		//业务提成发放记录
		/*
		*	$orderid 可为订单id 或订单内容
		*/
		function handout_salesman_commission($orderid,$products,$qty){
			if(is_array($orderid)){
				$cartlist = $orderid;//订单购物车
			}else{
				$order = Order::where('Order_ID',$orderid)->first();
				if(empty($order)){
					return 0;
				}
				$cartlist = json_decode($order->Order_CartList,true);//订单购物车
			}
			
			$dis_level = 3;//分销级别

                        $dis_ratio = $products['salesman_ratio']; //业务提成
                        $salesman_level_ratio = json_decode($products['salesman_level_ratio'],true);//业务分级提成
                        $profit = $products['ProductsProfit']; //产品利润
			$platForm = $products['platForm_Income_Reward']; //发送比例
			//取上级信息
			require_once($_SERVER["DOCUMENT_ROOT"].'/include/models/Biz.php');
			$biz_code = Biz::where('Biz_ID',$products['Biz_ID'])->first()->Invitation_Code;
			if(!$biz_code){
				return;
			}
			$sales = $this->get_parents($biz_code);//上级分销商
			
			if(empty($sales)){
				return;
			}
			
			$parents_arr = explode(',',$sales->Dis_Path);
			$psa = array_filter($parents_arr);
			$psars = array_reverse($psa);
			$result = array();
			//if($order->Owner_ID != $order->User_ID){
			array_unshift($psars,strval($sales->User_ID));
			//}
			
			
			/*-------------------------业务奖处理--------------------------*/
			$nobility_level_bak = Dis_Account::wherein('User_ID',$psars)->where('Users_ID',$order->Users_ID)->get(array('User_ID','Is_Salesman'))->toArray();
			$nobility_level = array();
			
			foreach($nobility_level_bak as $k=>$v){  //是否是业务员
				$nobility_level[$v['User_ID']] = $v['Is_Salesman'];
			}
			
			$nobility_commission = array();
			$t = 0;
			foreach($psars as $k=>$v){
				if($t > $dis_level){
					break;
				}
				if(!isset($nobility_level[$v])){
					$nobility_commission[$v]['Sales_Money'] = 0;
					$nobility_commission[$v]['Sales_Description'] = '该用户已不存在';
					$nobility_commission[$v]['Level'] = ($t+1);
				}else if($nobility_level[$v] == '0'){
					$nobility_commission[$v]['Sales_Money'] = 0;
					$nobility_commission[$v]['Sales_Description'] = '您还没成为创始人';
					$nobility_commission[$v]['Level'] = ($t+1);
				}else{
					//$nobility_commission[$v]['Sales_Money'] = $profit*$dis_ratio[$t]/100;
                                        $nobility_commission[$v]['Sales_Money'] = $profit*$platForm*$dis_ratio*$salesman_level_ratio[$t]/1000000;
					$nobility_commission[$v]['Sales_Description'] = ($t+1).'级，';
					$nobility_commission[$v]['Level'] = ($t+1);
				}
				$nobility_commission[$v]['Products_ID'] =  $products['Products_ID'];
				$nobility_commission[$v]['Users_ID'] =  $products['Users_ID'];
				$nobility_commission[$v]['Order_ID'] =  $orderid;
				$nobility_commission[$v]['Products_Price'] =  $products['Products_PriceX'];
				$nobility_commission[$v]['Products_Qty'] =  $qty;
				$nobility_commission[$v]['Biz_ID'] =  $products['Biz_ID'];
				$nobility_commission[$v]['create_time'] =  time();
				$nobility_commission[$v]['User_ID'] =  $v;
				$t++;
			}
			$flag = Capsule::table('distribute_sales_record')->insert($nobility_commission);
			return $flag;
		}
		
		//修改业务记录状态
		function up_sales_status($orderid,$status){
                        require_once($_SERVER["DOCUMENT_ROOT"].'/include/models/Distribute_Sales_Record.php');
			$flag = Distribute_Sales_Record::where('Order_ID',$orderid)->update(['Status'=>$status]);
			return $flag;
		}
		//业务记录状态
		function up_salesincome($orderid){
		
			$salesman_income = Distribute_Sales_Record::Multiwhere(['Order_ID'=>$orderid,'Status'=>2])->get()->toarray();
			if(empty($salesman_income)){
				return false;
			}
			$result = array();
			foreach($salesman_income as $k=>$v){
				$result[$v['User_ID']][] = $v['Sales_Money'];
			}
			$keys = array_keys($result);
			$i = 0;
			foreach($result as $k=>$v){
				$results[$i]['User_ID'] = $k;
				$results[$i]['Salesman_Income'] = array_sum($v);
				$results[$i]['balance'] = array_sum($v);
				$i++;
			}
			
			$flag = self::updateBatch('distribute_account',$results);
			
			return $flag;
			 
		}
		
		static function updateBatch($tableName = "", $multipleData = array()){

			if( $tableName && !empty($multipleData) ) {

				// column or fields to update
				$updateColumn = array_keys($multipleData[0]);
				$referenceColumn = $updateColumn[0]; //e.g id
				unset($updateColumn[0]);
				$whereIn = "";

				$q = "UPDATE ".$tableName." SET "; 
				foreach ( $updateColumn as $uColumn ) {
					$q .=  $uColumn." = CASE ".$referenceColumn;

					foreach( $multipleData as $data ) {
						$q .= " WHEN ".$data[$referenceColumn]." THEN ".$uColumn."+'".$data[$uColumn]."' ";
					}
					$q .= "ELSE ".$uColumn." END, ";
				}
				foreach( $multipleData as $data ) {
					$whereIn .= "'".$data[$referenceColumn]."', ";
				}
				$q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";

				// Update  
				 $flag = Capsule::update(Capsule::raw($q));
				
			} else {
				return false;
			}
		}
	}