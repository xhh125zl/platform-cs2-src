<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
set_time_limit(0);
ignore_user_abort(true);

Task::Run(function($flag){
    global $DB;
    if($flag == true)
    {
        $SalesPayment = new SalesPayment($DB,$v['Users_ID'],$flag);
        $SalesPayment->payfor();
    }
    else 
    {
        $result = $DB->Get("users_schedule","*","where Status<>1");
        $schedule = array();
        while($res = $DB->fetch_assoc($result))
        {
            $schedule[] = $res;
        }
        $curTime = time();
        $curTime = date("H:i:00",$curTime);
        if(!empty($schedule)){
            foreach ($schedule as $k =>$v)
            {
                if($v['StartRunTime']===$curTime){
                    $SalesPayment = new SalesPayment($DB,$v['Users_ID']);
                    $SalesPayment->payfor();
                }
            }
        }
    }
    
    
},true);
class Task
{
    public static function Run($callable, $flag = false)
    {
        if ($flag == true)
        {
            if($callable){
                call_user_func($callable, $flag);
            }
        }
        else
        {
            do{
                if($callable){
                    call_user_func($callable ,$flag);
                }
                sleep(10);
            }while(true);
        }
    }
} 

class SalesPayment
{
    private $DB;
    private $Users_ID;
    private $pay_order;
    private $flag;
    public function __construct($DB,$Users_ID,$flag = false)
    {
        $this->DB = $DB;
        $this->Users_ID = $Users_ID;
        $this->flag = $flag;
        $this->pay_order = new pay_order($DB, 0); 
    }
    
    //根据Users_ID 获取收款单列表
    private function getpayment()
    {
        $where = "";
        if($this->flag == true)
        {
            $where .= "WHERE Payment_Type=1 AND Status<>1";
        }
        else 
        {
            $where .= "WHERE Users_ID='{$this->Users_ID}' AND Payment_Type=1 AND Status<>1";
        }
        $res = $this->DB->Get("shop_sales_payment","Payment_ID,Payment_Type,Open_ID,Biz_ID,Total,Bonus,Web,Amount",$where);
        $list = array();
        while ($res = $this->DB->fetch_assoc($res))
        {
            $list[$res['Biz_ID']] = $res;
        }
        return $list;
    }

    public function payfor()
    {
        $list = $this->getpayment();
        if(!empty($list))
        {
            foreach ($list as $k => $v)
            {
                $Data = $this->pay_order->withdraws($this->Users_ID,$v["OpenID"],$v['Total']);
                if($Data["status"]==1){
                    $DB->Set("shop_sales_payment",array("Status"=>1),"where Payment_ID='{$v['Payment_ID']}'");
                    $DB->Set("shop_sales_record",array("Record_Status"=>1),"where Payment_ID='{$v['Payment_ID']}'");
                }else{
                    $DB->Set("shop_sales_payment",array("Msg"=>$Data["msg"]),"where Payment_ID='{$v['Payment_ID']}'");
                }
            }
        }
    }
}