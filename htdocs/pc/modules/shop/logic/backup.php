<?php
namespace shop\logic;
class backup{
	private $usersid;
	function __construct($usersid){
		$this->usersid = $usersid;	
	}
	
	public function add_backup($rsOrder, $productid, $cartid, $qty, $reason, $account){
		$orderid = $rsOrder['Order_ID'];
		$CartList = json_decode(htmlspecialchars_decode($rsOrder['Order_CartList']), true);
		$item = $CartList[$productid][$cartid];
		$item["Qty"] = $qty;
		$amount = $qty * $item["ProductsPriceX"];
		$time = time();
		$data = array(
			'Back_SN' => $this->build_order_no(),
			'Users_ID'=>$this->usersid,
			'Biz_ID'=>$rsOrder["Biz_ID"],
			'Order_ID'=>$orderid,
			'User_ID'=>$rsOrder["User_ID"],
			'Back_Type'=>'shop',
			'Back_Json'=>json_encode($item, JSON_UNESCAPED_UNICODE),
			'Back_Status'=>0,
			'Back_CreateTime' => $time,
			'Back_Qty'=>$qty,
			'Back_CartID'=>$cartid,
			'Back_Amount'=>$amount,
			'Back_Account'=>$account,
			'ProductID'=>$productid
		);
		$recordid = model('user_back_order')->insert($data);
		$detail = "买家申请退款，退款金额：".($qty * $item["ProductsPriceX"])."，退款原因：".$reason;
		//增加退款流程记录
		$this->add_record($recordid, 0, $detail, $time);
		
		//更改订单
		$CartList[$productid][$cartid]["Qty"] = $CartList[$productid][$cartid]["Qty"] - $qty;
		if($CartList[$productid][$cartid]["Qty"] == 0){
			unset($CartList[$productid][$cartid]);
		}
		if(count($CartList[$productid])==0){
			unset($CartList[$productid]);
		}
		
		$data = array(
			'Is_Backup'=>1,
			'Order_CartList'=>json_encode($CartList, JSON_UNESCAPED_UNICODE),
			'Back_Amount'=>$rsOrder["Back_Amount"] + $amount
		);
		model('user_order')->where(array('Order_ID'=>$rsOrder['Order_ID']))->update($data);
		if($rsOrder["Order_Status"]==2 && $rsOrder["Order_IsVirtual"]==0){//已付款,商家未发货订单退款
			$this->update_backup("seller_recieve",$recordid,"已付款/商家未发货订单退款，系统自动完成");		
		}
		//减少退款佣金记录
		$this->update_distribute_money($rsOrder["Order_ID"],$productid,$cartid,$qty,0);
	}
	
	public function update_backup($action,$backid,$reason=''){
		$time = time();
		switch($action){
			case 'seller_agree'://卖家同意
				$detail = '卖家同意退款，等待买家发货';
				
				//增加流程
				$this->add_record($backid,1,$detail,$time);
				
				//更新退款单
				$Data = array(
					"Back_Status"=>1,
					"Buyer_IsRead"=>0,
					"Back_UpdateTime"=>$time
				);
				model('user_back_order')->where(array('Back_ID'=>$backid))->update($Data);
			break;
			case 'seller_reject'://卖家驳回
				$detail = '卖家驳回退款申请，驳回理由：'.$reason;
				
				//增加退款流程
				$this->add_record($backid,1,$detail,$time);
				
				//更新退款单
				$Data = array(
					"Back_Status"=>5,
					"Buyer_IsRead"=>0,
					"Back_UpdateTime"=>$time
				);
				model('user_back_order')->where(array('Back_ID'=>$backid))->update($Data);
					
				$back = model('user_back_order')->field('*')->where(array('Back_ID'=>$backid))->find();
				$backjson = json_decode(htmlspecialchars_decode($back["Back_Json"]),true);
				$Order = model('user_order')->field('*')->where(array('Order_ID'=>$back["Order_ID"]))->find();
				//更新订单
				$CartList=json_decode(htmlspecialchars_decode($Order["Order_CartList"]), true);
				if(empty($CartList[$back["ProductID"]])){
					$CartList[$back["ProductID"]][] = $backjson;
				}else{
					if(empty($CartList[$back["ProductID"]][$back["Back_CartID"]])){
						$CartList[$back["ProductID"]][] = $backjson;
					}else{
						$CartList[$back["ProductID"]][$back["Back_CartID"]]["Qty"] = $CartList[$back["ProductID"]][$back["Back_CartID"]]["Qty"] + $backjson["Qty"];
					}
				}
				
				$Data = array(
					'Order_CartList'=>json_encode($CartList, JSON_UNESCAPED_UNICODE),
					'Back_Amount'=>$Order["Back_Amount"] - $back["Back_Amount"]
				);
				model('user_order')->where(array('Order_ID'=>$back["Order_ID"]))->update($Data);
				
				//增加退款佣金记录
				$bonus = $this->update_distribute_money($back["Order_ID"],$back["ProductID"],$back["Back_CartID"],$backjson["Qty"],1);
				if($Order["Order_Status"]==4){
					//增加销售记录
					$this->add_sales_record($back, $bonus);
				}
			break;
			case 'buyer_send':
				$arr = explode("||%$%",$reason);
				$detail = '买家已发货，物流方式：'.$arr[0]."，物流单号：".$arr[1];
				
				//增加流程
				$this->add_record($backid,1,$detail,$time);
				
				//更新退款单
				$Data = array(
					"Back_Status"=>2,
					"Biz_IsRead"=>0,
					"Back_Shipping"=>$arr[0],
					"Back_ShippingID"=>$arr[1],
					"Back_UpdateTime"=>$time
				);
				model('user_back_order')->where(array('Back_ID'=>$backid))->update($Data);
			break;
			case 'seller_recieve':
				$arr = explode("||%$%",$reason);
				$backinfo = model('user_back_order')->field('*')->where(array('Back_ID'=>$backid))->find();
				$orderinfo = model('user_order')->field('Back_Amount,Order_IsVirtual')->where(array('Order_ID'=>$backinfo["Order_ID"]))->find();
				if($orderinfo["Order_IsVirtual"]==1){
					$detail = '卖家已同意并确定了退款金额，退款金额为：'.$arr[0]."，理由：".$arr[1];
				}else{
					$detail = '卖家已收货并确定了退款金额，退款金额为：'.$arr[0]."，理由：".$arr[1];
				}
				
				//增加流程
				$this->add_record($backid,1,$detail,$time);
				
				//更新退款单
				$Data = array(
					"Back_Status"=>3,
					"Buyer_IsRead"=>0,
					"Back_Amount"=>$arr[0],
					"Back_UpdateTime"=>$time
				);
				model('user_back_order')->where(array('Back_ID'=>$backid))->update($Data);
				$amount = $orderinfo["Back_Amount"]+$arr[0]-$backinfo["Back_Amount"];
				$Data = array(
					"Back_Amount"=>$amount>0 ? $amount : 0
				);
				model('user_order')->where(array('Order_ID'=>$backinfo["Order_ID"]))->update($Data);
			break;
			case 'admin_backmoney'://卖家同意
				$detail = '管理员已退款给买家';
				
				//增加流程
				$this->add_record($backid,4,$detail,$time);
				
				//更新退款单
				$Data = array(
					"Back_Status"=>4,
					"Buyer_IsRead"=>0,
					"Back_IsCheck"=>1,
					"Back_UpdateTime"=>$time
				);
				model()->where(array('Back_ID'=>$backid))->update($Data);
			break;
		}
	}
	
	private function add_record($recordid,$status,$detail,$time){
		$Data = array(
			"backid"=>$recordid,
			"detail"=>$detail,
			"status"=>$status,
			"createtime"=>$time
		);
		model('user_back_order_detail')->insert($Data);
	}
	
	private function update_distribute_money($orderid,$productid,$cartid,$qty,$type=0){
		$bonus = array();
		if($type==0){//减少佣金
			$descrition = "订单退款，减少佣金。退款数量：".$qty;
		}else{//增加佣金
			$descrition = "订单退款被驳回，增加佣金。退款数量：".$qty;
		}
		$bonus_list = array();
		
		$bonus_list = model()->query("select d.* from distribute_account_record as d LEFT JOIN distribute_record as r ON d.Ds_Record_ID=r.Record_ID where r.Order_ID=".$orderid." and r.Product_ID=".$productid." and d.CartID=".$cartid." group by d.level", 'select');
		if(!empty($bonus_list)){
			foreach($bonus_list as $item){
				$data = $item;//复制数组
				unset($data["Record_ID"]);
				unset($data["deleted_at"]);
				unset($data["Owner_ID"]);
				$amount = $item["Record_Price"] * $qty;
				$data["Record_Money"] = $type==0 ? -$amount : $amount;
				$data["Record_Sn"] = $this->build_order_no();
				$data["Record_Qty"] = $qty;
				$data["Record_Description"] = $type==0 ? '用户退款，减少佣金'.$amount.'元' : '用户退款被驳回，增加佣金'.$amount.'元';
				$data["Record_CreateTime"] = time();
				$data["Owner_ID"] = 0;
				model('distribute_account_record')->insert($data);
			}
		}
	}
	
	private function add_sales_record($item,$bonus){
		$bonus_1 = empty($bonus[1]) ? 0 : $bonus[1];
		$bonus_2 = empty($bonus[2]) ? 0 : $bonus[2];
		$bonus_3 = empty($bonus[3]) ? 0 : $bonus[3];
		$CartList = array();
		$CartList[$item["ProductID"]][] = $item["Back_Json"];
		$Data = array(
			"Users_ID"=>$this->usersid,
			"Order_ID"=>$item["Order_ID"],
			"Order_Json"=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
			"Biz_ID"=>$item["Biz_ID"],
			"Bonus_1"=>$bonus_1,
			"Bonus_2"=>$bonus_2,
			"Bonus_3"=>$bonus_3,
			"Order_Amount"=>$item["Back_Amount"],
			"Order_Diff"=>0,
			"Order_Shipping"=>0,
			"Order_TotalPrice"=>$item["Back_Amount"],
			"Record_CreateTime"=>time()
		);
		model('shop_sales_record')->insert($Data);
	}
	
	private function build_order_no(){
    	mt_srand((double) microtime() * 1000000);
   	 	return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}
?>
