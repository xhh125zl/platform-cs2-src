<?php
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/pay/wxpay2/refund.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_pintuan.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/backup.class.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/api/pintuan/cart/PTBackup.php');
    ini_set("display_errors","On"); 
    //结束拼团活动

    pintuanend($DB);
    function pintuanend($DB) {
        global $DB;
    
        $list = array();
        $time = time();
        //结束10天之内的未完成的团
        $starttime = strtotime("-20 day",time());
        $starttime = date("Y-m-d",$starttime);
        $starttime = strtotime($starttime,time());

        $sql = "select * from pintuan_team t left join pintuan_products p on t.productid=p.products_id where p.stoptime >={$starttime} AND p.stoptime<={$time} AND t.teamstatus = 0";
        
        $result = $DB->query($sql);
        $list = $DB->toArray($result);
        if (count($list) == 0) return;
        foreach ($list as $item) {
            $status = $item['people_num'] == $item['teamnum'] ? 1 : 4;
            $sql_update_team = "update pintuan_team set teamstatus = '$status' where id = '{$item['id']}'";
            $DB->query($sql_update_team);
        }
    }
    
    //抽奖
    //db_teamstatus 0拼团中，1拼团成功，2已中奖，3未中奖，4拼团失败, 5已退款
    choujiang($DB);
    function choujiang($DB) {
        global $DB;
        $time = time();
        $starttime = strtotime("-20 day",time());
        $starttime = date("Y-m-d",$starttime);
        $starttime = strtotime($starttime,time());
        $teamproductidlist = array();
        $productlist = array();
        $orderidlist = array();
        $teamidlist = array();
        /** 新增加的 start **/
        $sql = "select * from pintuan_team as t left join pintuan_products as p on t.productid=p.Products_ID  where  p.stoptime >{$starttime} AND p.stoptime<{$time} AND t.teamstatus = 1 and p.is_draw = 0";
        $result = $DB->query($sql);
        if(!$result) return;
        while($res_all = $DB->fetch_assoc($result)){
            $teamidlist[] = $res_all['id'];
            $productlist[] = $res_all;
        }
        
        /** 新增加的 end **/

        if (count($teamidlist) == 0) return;

        $teamids = implode(',', $teamidlist);
        $sql_teamdetail = "select order_id,teamid from pintuan_teamdetail where teamid in ($teamids)";
        $result_teamdetail = $DB->query($sql_teamdetail);
        while ($res_teamdetail = $DB->fetch_assoc($result_teamdetail)) {
            $orderidlist[] = $res_teamdetail['order_id'];
            
        }

        if (count($orderidlist) == 0) return;
        
        /*  抽奖算法：
         *  首先计算参与抽奖的总团数
         *  其次计算中奖的团数
         * */
        
        $max = count($teamidlist);
        $num = rand(1,$max);
        $levelnum = intval($max*0.7);
        while($num<$levelnum)
        {
            $num = rand(1,$max);
        }
        
        if(!empty($teamidlist)){
            if($num == 1){
                $teamidlist1 = $teamidlist;
                $teamidlist2 = [];
            }else{
                $temp = array_rand($teamidlist, $num); //中奖团id列表
                $temp1 = array();
                foreach ($temp as $k => $v)
                {
                    $temp1[] = $teamidlist[$v];
                }
                if(!empty($temp1)){
                    $teamidlist1 = $temp1;
                    $teamidlist2 = array_diff($teamidlist, $teamidlist1);
                }else{
                    $teamidlist1 = array();
                    $teamidlist2 = array_diff($teamidlist, $teamidlist1);
                }
            }
            foreach ($productlist as $product) {
    
                if(!empty($teamidlist1)){
                    $teamids1 = implode(',', $teamidlist1);
                    $sql_update_team1 = "update pintuan_team set teamstatus = '2' where id in ($teamids1) and productid = '{$product['Products_ID']}'";
                    $DB->query($sql_update_team1);
                }
                
                if(!empty($teamidlist2)){
                    $teamids2 = implode(',', $teamidlist2);
                    $sql_update_team2 = "update pintuan_team set teamstatus = '3' where id in ($teamids2) and productid = '{$product['Products_ID']}'";
                    $DB->query($sql_update_team2);
                }
            }
            if(!empty($teamidlist1)){
                $teamidlist1 = implode(',', $teamidlist1);
                $result = $DB->query("select o.order_id as order_id,o.Users_ID as Users_ID,o.User_ID as User_ID from pintuan_teamdetail as t left join pintuan_order as o on t.order_id=o.order_id where t.teamid in ($teamidlist1) and o.is_ok is NULL");
                while($res_t= $DB->fetch_assoc($result))
                {
                    sendWXMessage($res_t['Users_ID'],$res_t['order_id'],'恭喜您，已中奖',$res_t['User_ID']);
                }
            }
            if(!empty($teamidlist2)){
                $teamidlist2 = implode(',', $teamidlist2);
                $result = $DB->query("select o.order_id as order_id,o.Users_ID as Users_ID,o.User_ID as User_ID from pintuan_teamdetail as t left join pintuan_order as o on t.order_id=o.order_id where t.teamid in ($teamidlist2) and o.is_ok is NULL");
                while($res_t= $DB->fetch_assoc($result))
                {
                    sendWXMessage($res_t['Users_ID'],$res_t['order_id'],'很遗憾，未中奖',$res_t['User_ID']);
                }
            }
        }
        
        $orderids = implode(',', $orderidlist);
        $sql_update_order = "update pintuan_order set is_ok='1' where order_id in ($orderids)";
        $DB->query($sql_update_order);
    }

	tuikuan($DB);
    function  tuikuan($DB){
        global $DB;
        //获取所有未中奖或者拼团失败的团
        $result = $DB->Get("pintuan_team","*","where teamstatus='3' or teamstatus='4'");
        $orderid = [];
        $ids = '';
        if($result){
            while($list = $DB->fetch_assoc($result)){
                $idlist[] = $list['id'];
            }
            if(!empty($idlist)){
                $ids = implode(',',$idlist);
            }else{
                return;
            }
            
            //获取拼团失败或者未中奖用户的orderid
            $result2 = $DB->Get("pintuan_teamdetail","*","where teamid in ($ids)");
            if($result2){
                while($list = $DB->fetch_assoc($result2)){
                    $orderid[] = $list;
                }
                
                mysql_query("SET AUTOCOMMIT=0");
                foreach($orderid as $v){
                    $order = $DB->query("select * from user_order as u left join pintuan_order as p on u.Order_ID=p.order_id where p.order_status >= 2 and u.Order_ID='{$v['order_id']}' ");
                    $order = $DB->fetch_assoc($order);
                    $product = json_decode($order['Order_CartList'],true);
                    $productid = $product['Products_ID'];
                    $goods = $DB->GetRs("pintuan_products","*","where Products_ID='{$productid}'");
                    
                    if(($order['Order_PaymentMethod'] && $goods['stoptime']<time())){
                            mysql_query("BEGIN");
                            $Users_ID = $order['Users_ID'];
                            if($order['Order_PaymentMethod']==='微支付'){
                                //微信支付退款
                                $weixinFlag = refund($v,$Users_ID);
                                
                                if($weixinFlag){
                                    $pflag = $DB->Set("pintuan_order","order_status='6'", "where order_id='{$v['order_id']}'");
                                    if(!$pflag){
                                        mysql_query("ROLLBACK");
                                    }
                                    $flag2=$DB->Set("pintuan_team", "teamstatus='5'","where id='{$v['teamid']}'");
                                    if(!$flag2){
                                        mysql_query("ROLLBACK");
                                    }
                                    $flag3=$DB->Set("user_order", "Order_Status='6'","where Order_ID='{$v['order_id']}'");
                                    if(!$flag3){
                                        mysql_query("ROLLBACK");
                                    }
                                }
                            }else{
                                $price = $order['Order_TotalPrice'];
                                $userid = $order['User_ID'];
                                //更改退款状态
                                //执行余额支付退款
                                $flag1=$DB->Set("user", "User_Money = User_Money + {$price}","where User_ID='{$userid}'");
                                if(!$flag1){
                                    mysql_query("ROLLBACK");
                                }
                                $flag2=$DB->Set("pintuan_order", "order_status='6'","where order_id='{$v['order_id']}'");
                                if(!$flag2){
                                    mysql_query("ROLLBACK");
                                }
                                
                                $flagr=$DB->Set("user_order", "Order_Status='6'","where Order_ID='{$v['order_id']}'");
                                if(!$flagr){
                                    mysql_query("ROLLBACK");
                                }
                                $flag3=$DB->Set("pintuan_team", "teamstatus='5'","where id='{$v['teamid']}'");
                                if(!$flag3){
                                    mysql_query("ROLLBACK");
                                }
                            }
                            $sellerid = $order['Users_ID'];
                            $goodsinfo = json_decode($order['Order_CartList'],true);
                            $userinfo = $DB->GetRs("user","*","where User_ID='{$order['User_ID']}'");
                            $flag = Stock($goodsinfo['Products_ID'], $Users_ID,'+');
                            if(!$flag){
                                mysql_query("ROLLBACK");
                            }
                            $PTBackup = new PTBackup($DB,$Users_ID);
                            $pbflag = $PTBackup->add($order, $goodsinfo['Products_ID'], $v['teamid'], "拼团失败退款记录", $userinfo['User_Name']?$userinfo['User_Name']:$userinfo['User_Mobile']);
                            if(in_array($order['Order_Status'],array(2,3,4,5))){
                                sendWXMessage($order['Users_ID'],$v['order_id'],'拼团没有成功已退款，订单号：'.$order['Order_Code']."，退款金额：".$order['Order_TotalPrice'],$order['User_ID']);
                            }
                            mysql_query("COMMIT");
                            
                    }
                }
            }
        }
        
    }


?>