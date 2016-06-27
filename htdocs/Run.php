<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/pay_order.class.php');
set_time_limit(0);
ignore_user_abort(true);
//ini_set("error_reporting",0);
$count = 1;
echo "自动脚本初始化运行开始...<br/>";
//Run首次运行，用于检测Run是否已经挂掉
$flag = $DB->show_tables();
$isMemory = in_array('memory',$flag);
$initalMemory = memory_get_usage();
if(!$isMemory){
    $sql = "CREATE TABLE `memory` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `Users_ID` varchar(30) NOT NULL,
  `name` varchar(20) DEFAULT NULL COMMENT '驻留程序名称',
  `status` tinyint(1) DEFAULT '0' COMMENT 'php内存驻留程序是否正在运行',
  `isblock` tinyint(1) DEFAULT '0' COMMENT '是否中断内存驻留程序的执行',
  `createTime` int(11) DEFAULT '0' COMMENT '内存驻留程序开始运行的时间',
  `memoryTotal` float(11,0) DEFAULT NULL COMMENT '内存驻留程序占用的内存',
   PRIMARY KEY (`id`,`Users_ID`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8";
   $DB->query($sql);
}
$flag = $DB->GetRs("memory","*","WHERE name='run'");
if(!$flag){
    $DB->Add("memory", array(
        'Users_ID' => $_SESSION['Users_ID'],
        'name' => 'run',
        'status' => 1,
        'isblock' =>0,
        'createTime' => time()
    ));
}else{
    $DB->Set("memory", array( 'status'=>1,'isblock'=>0 ), "WHERE name='run'");
}

//确保只能执行一次
if($flag['status']==1){
    die("内存驻留程序已经运行...");
}

echo "内存数据初始化...<br/>";
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
}, false);

class Task
{

    public static function Run($callable, $flag = false)
    {
        global $DB,$initalMemory;
        if ($flag == true) {
            if ($callable) {
 
                call_user_func($callable, $flag);
            }
        } else {
            echo "批处理脚本运行正式开始...<br/>";
            $count = 0;
            do {
                $result = $DB->GetRs("memory","*","WHERE name='run' and isblock=0");
                if(!$result){
                    $DB->Set("memory", array( 'status'=>0,'isblock'=>1 ), "WHERE name='run'");
                    echo "批处理脚本正常中断...<br/>";
                    break;
                }
                if ($callable) {
                    call_user_func($callable, $flag);
                }
                $currentMemory = memory_get_usage();
                $usageMemory = abs($currentMemory - $initalMemory);
                $DB->Set("memory", array( 'memoryTotal'=>$usageMemory ), "WHERE name='run'");
                $count ++;
                if($count==1){
                    $size=ob_get_length();
                    header("Content-Length: $size"); //告诉浏览器数据长度,浏览器接收到此长度数据后就不再接收数据
                    header("Connection: Close");
                    print str_pad("vvvv",10000);
                    ob_flush();
                    flush();
                }
                sleep(5);
                
            } while (true);
            echo "批处理脚本运行结束...<br/>";
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