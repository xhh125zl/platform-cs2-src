<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$action = isset($_GET['action'])?$_GET['action']:"";

if(IS_AJAX && $action)
{
    $time = time();
    $result = $DB->Get("active", "*" ,"WHERE Users_ID='{$UsersID}' AND starttime<{$time} AND stoptime>{$time} AND Status=1 ORDER BY starttime ASC,Active_ID DESC");
    $list = $DB->toArray($result);
    $msglist = [];
    
    foreach ($list as $key => $value)
    {
        $msg = "";
        if($value['stoptime']<$time){
            $msg = "已结束";
        }else{
            $msg = "正在进行中";
        }
        $msglist[$key]['title'] = "{$ActiveType[$value['Type_ID']]}活动——{$value['Active_Name']}{$msg} "; 
        $msglist[$key]['addtime'] = date("Y-m-d H:i",$value['addtime']);
        $msglist[$key]['id'] = $value['Active_ID'];
    }
    if(!empty($msglist)){
        die(json_encode([ 'data' => $msglist ,'status'=>1 ], JSON_UNESCAPED_UNICODE));
    }else{
        die(json_encode([ 'data' => $msglist ,'status'=>0 ], JSON_UNESCAPED_UNICODE));
    }
}