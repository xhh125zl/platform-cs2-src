<?php
class PTBackup extends backup{
    
    public function __construct($DB,$usersid){
        parent::__construct($DB, $usersid);
    }
    
    public function add($rsOrder, $productid,$pid, $reason, $account,$Back_Type ='pintuan'){
        $orderid = $rsOrder["Order_ID"];
        $CartList=json_decode(htmlspecialchars_decode($rsOrder["Order_CartList"]),true);
        $item = $CartList;
        $qty = 1;
        $item["Qty"] = $qty;
        $time = time();
        $data = array(
            'Back_SN' => $this->build_order_no(),
            'Users_ID'=>$this->usersid,
            'Order_ID'=>$orderid,
            'User_ID'=>$rsOrder["User_ID"],
            'Back_Type'=>$Back_Type,
            'Back_Json'=>json_encode($item,JSON_UNESCAPED_UNICODE),
            'Back_Status'=>0,
            'Back_CreateTime'=>$time,
            'Back_Qty'=>$qty,
            'Back_CartID'=>0,
            'Back_Amount'=>$rsOrder['Order_TotalPrice'],
            'Back_Account'=>$account,
            'ProductID'=>$productid
        );
        $this->db->add('user_back_order',$data);
        if($rsOrder['Order_Type']==='dangou'){
            $amount = $item["ProductsPriceD"];
        }else if($rsOrder['Order_Type']==='pintuan'){
            $amount = $item["ProductsPriceT"];
        }
        $detail = "拼团失败买家申请退款，退款金额：".($rsOrder['Order_TotalPrice'])."，退款原因：".$reason;
        $recordid = $this->db->insert_id();
    
        //增加退款流程记录
        $this->add_record($recordid,0,$detail,$time);
    
        //更改订单
        $data = array(
            'Is_Backup'=>1,
            'Order_CartList'=>json_encode($CartList,JSON_UNESCAPED_UNICODE),
            'Back_Amount'=>$rsOrder['Order_TotalPrice']
        );
        $this->db->set("user_order",$data,"where Order_ID=".$rsOrder["Order_ID"]);
        
        //增加退款佣金记录
        $this->update_distribute_money($rsOrder["Order_ID"],$productid,0,$qty,0);
    }
}