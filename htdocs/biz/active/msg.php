<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$action = isset($_GET['action'])?$_GET['action']:"";

if(IS_AJAX && $action)
{
    $time = time();
    $sql = "SELECT * FROM active AS a LEFT JOIN active_type AS t ON a.Type_ID = t.Type_ID WHERE a.Users_ID='{$UsersID}' AND a.starttime<{$time} AND a.stoptime>{$time} AND a.Status=1 AND Active_ID NOT IN (SELECT Active_ID FROM biz_active WHERE Users_ID='{$UsersID}' AND Biz_ID={$BizID}) ORDER BY a.starttime ASC,a.Active_ID DESC";
    $result = $DB->query($sql);
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
        $msglist[$key]['title'] = "{$value['Type_Name']}活动——{$value['Active_Name']}{$msg} "; 
        $msglist[$key]['addtime'] = date("Y-m-d H:i",$value['addtime']);
        $msglist[$key]['id'] = $value['Active_ID'];
    }
    if(!empty($msglist)){
        die(json_encode([ 'data' => $msglist ,'status'=>1 ], JSON_UNESCAPED_UNICODE));
    }else{
        die(json_encode([ 'data' => $msglist ,'status'=>0 ], JSON_UNESCAPED_UNICODE));
    }
}