<?php
//开团
function addTeam($orderid,$Users_ID)
{
    global $DB;
    if(!$orderid) return false;
    $orderInfo = $DB->GetRs("user_order","*","where Order_ID='{$orderid}' and Users_ID='{$Users_ID}'");
    $CartList = json_decode($orderInfo['Order_CartList'],true);
    $userid = $orderInfo["User_ID"];
    $productid = $CartList['Products_ID'];
    $data = [];
    $teamid = $CartList['TeamID'];
    if(empty($teamid)) {
        $flag = $DB->GetRs("pintuan_team","*","where id='{$teamid}'");
        if(!$flag){
            $data['productid'] = $CartList['Products_ID'];
            $data['users_id'] = $Users_ID;
            $data['userid'] = $userid;
            $data['starttime'] = $CartList['starttime'];
            $data['stoptime'] = $CartList['stoptime'];
            $data['teamstatus'] = 0;
            $data['teamnum'] = 0;
            $data['addtime'] = time();
            $num = 0;
            $DB->Add('pintuan_team', $data);
            $teamid = $flag1 = $DB->insert_id();
            if($flag1){
                $ef = enterTeam($flag1,$orderid,$Users_ID);
            }
        }
    }else{
        //参团
        $ef = enterTeam($teamid,$orderid,$Users_ID,$userid);
    }
    
    $return = false;
    if($ef){
        $flag = $DB->GetRs("pintuan_team","teamnum","where id='{$teamid}'");
        if($flag['teamnum']==$CartList['people_num']){
            $return =-1;   //拼团成功
        }else{
            $return = true;
        }
    }
    return $return;
}
//  参团
function enterTeam($pid,$order_id,$Users_ID,$userid)
{
    global $DB;
    $flag = $DB->GetRs("pintuan_teamdetail","*","where teamid='{$pid}' and userid='{$userid}'");
        if(!$flag){
            $data['teamid']=$pid;
            $data['userid']=$userid;
            $data['order_id']=$order_id;
            $data['addtime']=time();
            $flag1 = $DB->Add('pintuan_teamdetail', $data);
            if($flag1){
                $DB->Set("pintuan_team","teamnum=teamnum+1","where id='{$pid}'");
                //更新字段
                return true;
            }else{
                return false;
            }
        return false;
    }
}