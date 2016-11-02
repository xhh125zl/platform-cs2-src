<?php
require_once "config.inc.php";
require_once CMS_ROOT . '/include/api/message.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

if (!$_POST) {
    //系统消息未读条数
    //获取商家注册时间，以确认显示信息
    $biz_info = $DB->GetRs('biz', 'Biz_CreateTime', 'where `Biz_ID` = '.$BizID);
    $DB->Get("announce","announce.*,announce_record.Record_ID","left join `announce_record` on announce.Announce_ID = announce_record.Announce_ID and announce_record.Biz_ID = ".$BizID." where announce.Announce_Status = 1 and Announce_CreateTime > ".$biz_info['Biz_CreateTime']." order by announce_record.Record_ID,announce.Announce_CreateTime desc");
    $unread_system_nums = 0;
    while ($r=$DB->fetch_assoc()) {
        if (!(isset($r['Record_ID']) && $r['Record_ID'] > 0)) {
            $unread_system_nums++;
        }
    }
    //订单消息未读条数
    $result = message::getMsgOrder(['Biz_Account' => $BizAccount]);
    $unread_order_nums = 0;
    if ($result['errorCode'] == 0) {
        $unread_order_nums = $result['data']['unReadCount'];
    }
    //分销消息未读条数
    $result = message::getMsgDistribute(['Biz_Account' => $BizAccount]);
    $unread_distribute_nums = 0;
    if ($result['errorCode'] == 0) {
        $unread_distribute_nums = $result['data']['unReadCount'];
    }
    //提现消息未读条数
    $result = message::getMsgWithdraw(['Biz_Account' => $BizAccount]);
    $unread_withdraw_nums = 0;
    if ($result['errorCode'] == 0) {
        $unread_withdraw_nums = $result['data']['unReadCount'];
    }
}

if ($_POST && !isset($_POST['ajax'])) {
    $do = $_GET['act'];
    if ($do == 'msg_system') {      //记录商家读取信息
        $Announce_ID = $_POST['Announce_ID'];
        $read_status = $_POST['read_status'];
        //判断是否已为读取过，读取过就不写了
        if ($read_status == 0) {
            $data = [
                    'Biz_ID' => $BizID,
                    'Announce_ID' => $Announce_ID,
                    'Record_CreateTime' => time()
                ];
            $res = $DB->Add("announce_record", $data);
            if ($res) {
                echo json_encode(['errorCode' => 0, 'msg' => '读取记录写入成功']);die;
            } else {
                echo json_encode(['errorCode' => 1, 'msg' => '读取记录写入失败']);die;
            }
        }
    } else {
        $msg_id = $_POST['msg_id'];
        $msg_status = $_POST['msg_status'];
        if ($msg_status == 1) {
            echo json_encode(['errorCode' => 0, 'msg' => '信息状态已为已读，不需更新']);die;
        } else if ($msg_status == 0) {
            $transData = ['msg_status' => 1, 'modify_time' => time()];
            $postData = ['id' => $msg_id, 'transData' => $transData];
            if ($do == 'msg_order') {
                $result = message::updateMsgOrder($postData);
            } else if ($do == 'msg_distribute') {
                $result = message::updateMsgDistribute($postData);
            } else if ($do == 'msg_withdraw') {
                $result = message::updateMsgWithdraw($postData);
            }
            
            if ($result['errorCode'] == 0) {
                echo json_encode(['errorCode' => 0, 'msg' => '信息状态更新成功']);die;
            } else {
                echo json_encode(['errorCode' => 1, 'msg' => '信息状态更新失败']);die;
            }
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '信息状态获取失败']);die;
        }
    }
}   