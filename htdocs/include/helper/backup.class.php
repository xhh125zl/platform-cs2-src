<?php
class backup{
	var $db;
	var $usersid;

	function __construct($DB,$usersid){
		$this->db = $DB;
		$this->usersid = $usersid;	
	}
	
	public function add_backup($rsOrder, $productid, $cartid, $qty, $reason, $account){
		$orderid = $rsOrder["Order_ID"];
		$CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
		$ShippingList=json_decode(htmlspecialchars_decode($rsOrder["Order_Shipping"]),true);
		$item = $CartList[$productid][$cartid];
		
		$product = $this->db->GetRs("shop_products","Products_IsVirtual","WHERE Products_ID=". intval($productid));
		//虚拟产品物流费用为0
		if ($product['Products_IsVirtual'] == 1) {
		 			$ShippingMoney = 0;
		} else {
		 			$ShippingMoney = $ShippingList['Price'];
		
		}
		unset($product);
		
		$item["Qty"] = $qty;
		if($rsOrder['Order_Status'] == 2){
		  $amount = $qty * $item["ProductsPriceX"];
		}else{
		  $amount = $qty * $item["ProductsPriceX"];
		  $amount = $amount + $ShippingMoney;
		}
		$time = time();
		$data = array(
			'Back_SN' => $this->build_order_no(),
			'Users_ID'=>$this->usersid,
			'Biz_ID'=>$rsOrder["Biz_ID"],
			'Order_ID'=>$orderid,
			'User_ID'=>$rsOrder["User_ID"],
			'Back_Type'=>'shop',
			'Back_Json'=>json_encode($item,JSON_UNESCAPED_UNICODE),
			'Back_Status'=>0,
			'Back_CreateTime'=>$time,
			'Back_Qty'=>$qty,
			'Back_CartID'=>$cartid,
			'Back_Amount'=>$amount,
			'Back_Account'=>$account,
			'ProductID'=>$productid
		);
		$this->db->add('user_back_order',$data);
		$detail = "买家申请退款，退款金额：".($amount)."，退款原因：".$reason;
		$recordid = $this->db->insert_id();
		//增加退款流程记录
		$this->add_record($recordid,0,$detail,$time);
		if(!empty($CartList)){
		    //更改订单
		    $CartList[$productid][$cartid]["Qty"] = $CartList[$productid][$cartid]["Qty"] - $qty;
		    $data = array(
    		            'Is_Backup'=>0,
    		            'Order_Status'=>5,
    		            'Order_CartList'=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
    		            'Back_Amount'=>$rsOrder["Back_Amount"]+$amount
    		);
		}
		
		$this->db->set("user_order",$data,"where Order_ID=".$rsOrder["Order_ID"]);
		/*
		if($rsOrder["Order_Status"]==2 && $rsOrder["Order_IsVirtual"]==0){//已付款,商家未发货订单退款
			$this->update_backup("seller_recieve",$recordid,$amount."||%$%已付款/商家未发货订单退款，系统自动完成");		
		}*/
		//增加退款佣金记录
		$this->update_distribute_money($rsOrder["Order_ID"],$productid,$cartid,$qty,0);
	}
	
	public function update_backup($action,$backid,$reason=''){
		$time = time();
		$backinfo = $this->db->GetRs("user_back_order","*","where Back_ID=".$backid);
		switch($action){
			case 'seller_agree'://卖家同意
				$detail = '卖家同意退款，等待买家发货';
				
				//增加流程
				$this->add_record($backid,1,$detail,$time);
				
		        if($backinfo['Order_Status'] == 3){
 					$Data = array(
 						'Is_Backup'=>0,
 						'Order_Status'=>3,
 						'Order_CartList'=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
 						'Back_Amount'=>$Order["Back_Amount"]-$back["Back_Amount"]
 					);
 					
 				}else{
 					$Data = array(
	
 						'Is_Backup'=>0,
 						'Order_Status'=>2,
 						'Order_CartList'=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
 						'Back_Amount'=>$Order["Back_Amount"]-$back["Back_Amount"]
 					);
 				}
				$this->db->Set("user_back_order",$Data,"where Back_ID=".$backid);
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
				$this->db->Set("user_back_order",$Data,"where Back_ID=".$backid);
					
				$back = $this->db->GetRs("user_back_order","*","where Back_ID=".$backid);
				$backjson = json_decode(htmlspecialchars_decode($back["Back_Json"]),true);
				$Order = $this->db->GetRs("user_order","*","where Order_ID=".$back["Order_ID"]);
				//更新订单
				$CartList=json_decode(htmlspecialchars_decode($Order["Order_CartList"]),true);
				if(empty($CartList[$back["ProductID"]])){
					$CartList[$back["ProductID"]][] = $backjson;
				}else{
					if(empty($CartList[$back["ProductID"]][$back["Back_CartID"]])){
						$CartList[$back["ProductID"]][] = $backjson;
					}else{
						$CartList[$back["ProductID"]][$back["Back_CartID"]]["Qty"] = $CartList[$back["ProductID"]][$back["Back_CartID"]]["Qty"] + $backjson["Qty"];
					}
				}
				if($back['Back_Status'] == 5){
				    if($Order['Order_IsVirtual']==1){
				        $Data = array(
				            'Is_Backup'=>0,
				            'Order_Status'=>2,
				            'Order_CartList'=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
				            'Back_Amount'=>$Order["Back_Amount"]-$back["Back_Amount"]
				        );
				    }else{
    				    $Data = array(
    				        'Is_Backup'=>0,
    				        'Order_Status'=>3,
    				        'Order_CartList'=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
    				        'Back_Amount'=>$Order["Back_Amount"]-$back["Back_Amount"]
    				    );
				    }	
				}else{
				    $Data = array(
				        'Is_Backup'=>0,
				        'Order_Status'=>2,
				        'Order_CartList'=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
				        'Back_Amount'=>$Order["Back_Amount"]-$back["Back_Amount"]
				    );
				}

				$this->db->Set("user_order",$Data,"where Order_ID=".$back["Order_ID"]);
				
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
				$this->db->Set("user_back_order",$Data,"where Back_ID=".$backid);
			break;
			case 'seller_recieve':
				$arr = explode("||%$%",$reason);
				
				$backinfo = $this->db->GetRs("user_back_order","Back_Amount,Order_ID","where Back_ID=".$backid);
				$orderinfo = $this->db->GetRs("user_order","Back_Amount,Order_IsVirtual","where Order_ID=".$backinfo["Order_ID"]);
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
				$this->db->Set("user_back_order",$Data,"where Back_ID=".$backid);
				$amount = $orderinfo["Back_Amount"]+$arr[0]-$backinfo["Back_Amount"];
				$Data = array(
					"Back_Amount"=>$amount>0 ? $amount : 0
				);
				$this->db->Set("user_order",$Data,"where Order_ID=".$backinfo["Order_ID"]);
			break;
			case 'admin_backmoney'://卖家同意
			    $backinfo = $this->db->GetRs("user_back_order","Back_Amount,Order_ID","where Back_ID=".$backid);
			    $Order = $this->db->GetRs("user_order","*","where Order_ID=".$backinfo["Order_ID"]);
			    $detail = '管理员已退款给买家';
				$CartList=json_decode(htmlspecialchars_decode($Order["Order_CartList"]),true);
				//增加流程
				$this->add_record($backid,4,$detail,$time);
				
				//更新退款单
				$Data = array(
					"Back_Status"=>4,
					"Buyer_IsRead"=>0,
					"Back_IsCheck"=>1,
					"Back_UpdateTime"=>$time
				);
				$this->db->Set("user_back_order",$Data,"where Back_ID=".$backid);
		        if(empty($CartList)){
 					$Data1 = array(
 						"Order_Status"=>4
 					);
 					$this->db->Set("user_order",$Data1,"where Order_ID=".$backinfo["Order_ID"]);
 				}
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
		$this->db->Add("user_back_order_detail",$Data);
	}
	
	private function update_distribute_money($orderid,$productid,$cartid,$qty,$type=0){
		$bonus = array();
		if($type==0){//减少佣金
			$descrition = "订单退款，减少佣金。退款数量：".$qty;
		}else{//增加佣金
			$descrition = "订单退款被驳回，增加佣金。退款数量：".$qty;
		}
		$bonus_list = array();
		
		$this->db->query("select d.* from distribute_account_record as d LEFT JOIN distribute_record as r ON d.Ds_Record_ID=r.Record_ID where r.Order_ID=".$orderid." and r.Product_ID=".$productid." and d.CartID=".$cartid." group by d.level");
		while($r = $this->db->fetch_assoc()){
			$bonus_list[] = $r;
		}
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
				$data['Nobi_Money'] = 0;
				$this->db->Add('distribute_account_record', $data);
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
		$this->db->Add("shop_sales_record",$Data);
	}
	
	private function build_order_no(){
    	mt_srand((double) microtime() * 1000000);
   	 	return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}
?>
