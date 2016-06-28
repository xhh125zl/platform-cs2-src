<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/pay_order.class.php');
set_time_limit(0);

// $flag 为 true 立即执行
Task::Run(function ($flag) {
    global $DB;
    if ($flag == true) {
        $result = $DB->Get("users_schedule", "*", "where Status=0");
        $schedule = array();
        while ($res = $DB->fetch_assoc($result)) {
            $schedule[] = $res;
        }
        $curTime = time();
        if (! empty($schedule)) {
            foreach ($schedule as $k => $v) {
                $SalesPayment = new SalesPayment($DB, $v['Users_ID'], $flag);
                $SalesPayment->payfor();
            }
        }
    } else {
        $result = $DB->Get("users_schedule", "*", "where Status=0");
        $schedule = array();
        while ($res = $DB->fetch_assoc($result)) {
            $schedule[] = $res;
        }
        $curTime = time();
        file_put_contents(__DIR__.'/log.txt', "执行\t\t\t".date("Y-m-d H:i:s")."\r\n",FILE_APPEND);
        if (! empty($schedule)) {

            foreach ($schedule as $k => $v) {
                $lastRunTime = $v['LastRunTime'] + strtotime(date("Y-m-d ").$v['StartRunTime']) +  86400 * $v['day']; // 上一次执行时间
                if ($v['RunType'] == 1) { // 按周
                    $lastRunTime = strtotime("+1 week", $v['LastRunTime']);
                } else {
                    if ($v['RunType'] == 3) {
                        $lastRunTime = strtotime("+1 month", $v['LastRunTime']);
                    } else {
                        $lastRunTime = strtotime("+{$v['day']} day", $v['LastRunTime']);
                    }
                }

                //按天数
                if ($curTime - $v['LastRunTime'] >= 86400 * $v['day']) {
                    $executeTime = strtotime(date("Y-m-d ") . $v['StartRunTime']);
                    if ($curTime - $executeTime >= 0) {
                        $SalesPayment = new SalesPayment($DB, $v['Users_ID']);
                        $SalesPayment->payfor();
                    }
                }
            }
        }
    }
}, true);

class Task
{
    public static function Run($callable, $flag = false)
    {
        global $DB,$initalMemory;
        if ($callable) {
            call_user_func($callable, $flag);
        }
        
    }
} 

class SalesPayment
{

    private $DB;

    private $Users_ID;

    private $pay_order;

    private $flag;

    public function __construct($DB, $Users_ID, $flag = false)
    {
        $this->DB = $DB;
        $this->Users_ID = $Users_ID;
        $this->flag = $flag;
        $this->pay_order = new pay_order($DB, 0);
    }
    
    // 根据Users_ID 获取收款单列表
    private function getpayment()
    {
        $where = "";
        if ($this->flag == true) {
            $where .= "WHERE Payment_Type=1 AND Status=0";
        } else {
            $where .= "WHERE Users_ID='{$this->Users_ID}' AND Payment_Type=1 AND Status=0";
        }
        $result = $this->DB->Get("shop_sales_payment", "Payment_ID,Payment_Type,OpenID,Biz_ID,Total,Bonus,Web,Amount", $where);
        $list = array();
        while ($res = $this->DB->fetch_assoc($result)) {
            $list[$res['Biz_ID']] = $res;
        }
        return $list;
    }

    public function payfor()
    {
        $list = $this->getpayment();
        $DB = $this->DB;
        if (! empty($list)) {
            foreach ($list as $k => $v) {
                $Data = $this->pay_order->withdraws($this->Users_ID, $v["OpenID"], $v['Total']);
                if ($Data["status"] == 1) {
                    $DB->Set("shop_sales_payment", array(
                        "Status" => 2
                    ), "where Payment_ID='{$v['Payment_ID']}'");
                    $DB->Set("shop_sales_record", array(
                        "Record_Status" => 2
                    ), "where Payment_ID='{$v['Payment_ID']}'");
                } else {
                    $DB->Set("shop_sales_payment", array(
                        "Msg" => $Data["msg"]
                    ), "where Payment_ID='{$v['Payment_ID']}'");
                }
            }
        }
    }
}