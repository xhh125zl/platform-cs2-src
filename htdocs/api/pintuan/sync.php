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
        //结束10天之内的未完成的团
        $time = time() - 86400;

        $sql = "select t.id,p.people_num,t.teamnum from pintuan_team t left join pintuan_products p on t.productid=p.products_id 
        where  p.stoptime <{$time} AND t.teamstatus = 0";
        $result = $DB->query($sql);
        $list = $DB->toArray($result);
        if (empty($list)) { return; }
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
        $time = time() - 86400;
        
        //根据所参加的团获取产品数
        $sql = "SELECT t.id,t.productid,t.users_id,p.Users_ID,p.people_num,p.Team_Count,p.starttime,p.stoptime FROM pintuan_team AS t LEFT JOIN pintuan_products as p ON t.productid=p.Products_ID WHERE teamstatus=1 AND t.addtime>=p.starttime AND t.addtime<=p.stoptime AND p.stoptime<{$time} AND p.is_draw=0";
        $result = $DB->query($sql);
        $list = $DB->toArray($result);
        //抽奖前统计信息，获取产品列表id 和开团列表id，并统计个数
        $goodslist = [];
        $teamlist = [];
        $orderlist = [];
        $award = [];    //获取每个产品所设置的中奖团数
        foreach($list as $k=>$v)
        {
            $goodslist[$v['productid']]=$v['productid'];
            $teamlist[] = $v['id'];
            $award[$v['productid']] = $v['Team_Count'];
        }
        // 允许抽奖并且拼团成功的开团总团数
        $teamTotal = count($teamlist);
        //开团里边的商品总数
        $goodsTotal = count($goodslist);
        //获取允许开奖且拼团成功的订单id
        if ($teamTotal == 0) return;

        $teamids = implode(',', $teamlist);
        $sql = "select order_id,teamid from pintuan_teamdetail where teamid in ($teamids)";
        $result = $DB->query($sql);
        $teamdetail = $DB->toArray($result);
        foreach($teamdetail as $k=>$v)
        {
            $orderlist[] = $v['order_id'];
        }
        $ordersTotal = count($orderlist);
        if ($ordersTotal == 0) return;
        
        /*  抽奖算法：
         *  首先计算参与抽奖的总团数
         *  其次计算中奖的团数
         * */
        $goodsjson = "";
        $orderids = "";
        $awordteamlistStr = "";
        $noneteamlistStr = "";
        $goodsjson = [];
        
        if(!empty($goodslist))
        {
            $awordteamlist = [];    //中奖团id列表
            $noneteamlist = [];     //未中奖团id列表
            foreach($goodslist as $k => $v)
            {
                $gTeamlist = [];
                $goodsAwordlist = [];
                foreach($list as $key => $value)
                {
                    if($v==$value['productid'])
                    {
                        $gTeamlist[]=$value['id'];
                    }
                }
                if(count($gTeamlist)>0 && count($gTeamlist)>$award[$v]){
                    for($i=0; $i<$award[$v]; $i++)
                    {
                        do{
                            $num = mt_rand(0,count($gTeamlist));
                            if(!in_array($gTeamlist[$num],$goodsAwordlist)){
                                $goodsAwordlist[] = $gTeamlist[$num];
                                break;
                            }
                        }while(true);
                    }
                }else{
                   $goodsAwordlist = array_merge($goodsAwordlist, $gTeamlist);
                }
                $noneAwordlist = array_diff($gTeamlist, $goodsAwordlist);
                $goodsjson [] = ['id' => $v ,'AllowCount' => $award[$v],'awordlist'=>implode(',', $goodsAwordlist),'noneAwordlist'=> implode(',', $noneAwordlist)];
                $awordteamlist = array_merge($awordteamlist, $goodsAwordlist);
                $noneteamlist = array_merge($noneteamlist, $noneAwordlist);
            }
            if(!empty($awordteamlist)){
                $awordteamlistStr = implode(',', $awordteamlist);
                $DB->Set("pintuan_team",['teamstatus'=>2],"WHERE id in ($awordteamlistStr)");
                $result = $DB->query("select o.order_id as order_id,o.Users_ID as Users_ID,o.User_ID as User_ID from pintuan_teamdetail as t left join pintuan_order as o on t.order_id=o.order_id where t.teamid in ($awordteamlistStr) and o.is_ok is NULL");
                while($res_t= $DB->fetch_assoc($result))
                {
                    sendWXMessage($res_t['Users_ID'],$res_t['order_id'],'恭喜您，已中奖',$res_t['User_ID']);
                }
            }
            if(!empty($noneteamlist)){
                $noneteamlistStr = implode(',', $noneteamlist);
                $DB->Set("pintuan_team",['teamstatus'=>3],"WHERE id in ($noneteamlistStr)");
                $result = $DB->query("select o.order_id as order_id,o.Users_ID as Users_ID,o.User_ID as User_ID from pintuan_teamdetail as t left join pintuan_order as o on t.order_id=o.order_id where t.teamid in ($noneteamlistStr) and o.is_ok is NULL");
                while($res_t= $DB->fetch_assoc($result))
                {
                    sendWXMessage($res_t['Users_ID'],$res_t['order_id'],'很遗憾，未中奖',$res_t['User_ID']);
                }
            }
        }
        if(!empty($orderlist)){
          $orderids = implode(',', $orderlist);
          $DB->Set("pintuan_order",['is_ok'=>1],"WHERE order_id in ($orderids)");
        }

        //将统计结果写入数据库
        $data = [
          'goodsConfig' => json_encode(['total' => $goodsTotal,'list'=>$goodsjson]),
          'orderlist' => $orderids,
          'orderTotal' => $ordersTotal,
          'teamTotal' => $teamTotal,
          'goodsTotal' => $goodsTotal,
          'awordTeamlist' => $awordteamlistStr,
          'noneAwordTeamlist' => $noneteamlistStr,
          'addtime' => time(),
          'Users_ID' =>isset($_SESSION['Users_ID'])?$_SESSION['Users_ID']:''
        ];
        $DB->add("pintuan_aword",$data);
        
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