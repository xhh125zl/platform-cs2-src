<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/message.class.php';

function set_msg_config ($Data) {
    global $DB;
    global $BizID;
    $res = $DB->Set("biz_msg_config", $Data, "where Biz_ID=" . $BizID);
    if ($res) {
        return ['errorCode' => 0, 'msg' => '设置成功'];
    } else {
        return ['errorCode' => 1, 'msg' => '设置失败'];
    }
}

if ($_POST) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
    //获取全部的信息
    $transData = ['Biz_Account' => $BizAccount];
    if ($do == 'order_distribute_msg' || $do == 'join_distribute_msg') {
        //获取分销信息
        $res = message::getMsgDistribute($transData);
        if ($res['errorCode'] == 0) {
            $msg_distribute = $res['data']['myDistribute'];
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '获取分销信息失败']);die;
        }
    } else {
        //获取订单信息
        $res = message::getMsgOrder($transData);
        if ($res['errorCode'] == 0) {
            $msg_order = $res['data']['myOrder'];
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '获取订单信息失败']);die;
        }
    }

    if ($do == 'confirm_order') {       //待确认订单信息设置
        $Data = ['confirm_order_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
        //设置不查看时将原来的信息改为已读状态
        if (!empty($msg_order) && $result['errorCode'] == 0 && $status == 0) {
            $tag = 0;
            foreach ($msg_order as $k => $v) {
                if ($v['Order_Status'] == 0 && $v['msg_status'] == 0) {
                    $postData = ['id' => $v['id'], 'transData' => ['msg_status' => 1, 'modify_time' => time()]];
                    $status_res = message::updateMsgOrder($postData);
                    if ($status_res['errorCode'] != 0) {
                        $tag += 1;
                    }
                }
            }
            if ($tag == 0) {
                $result = ['errorCode' => 0, 'msg' => '待确认订单信息读取状态修改成功'];
            } else {
                $result = ['errorCode' => 1, 'msg' => '待确认订单信息读取状态修改失败'];
            }
        }
    } else if ($do == 'delivery_order') {       //待发货订单信息设置
        $Data = ['delivery_order_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
        //设置不查看时将原来的信息改为已读状态
        if (!empty($msg_order) && $result['errorCode'] == 0 && $status == 0) {
            $tag = 0;
            foreach ($msg_order as $k => $v) {
                if ($v['Order_Status'] == 2 && $v['msg_status'] == 0) {
                    $postData = ['id' => $v['id'], 'transData' => ['msg_status' => 1, 'modify_time' => time()]];
                    $status_res = message::updateMsgOrder($postData);
                    if ($status_res['errorCode'] != 0) {
                        $tag += 1;
                    }
                }
            }
            if ($tag == 0) {
                $result = ['errorCode' => 0, 'msg' => '待发货订单信息读取状态修改成功'];
            } else {
                $result = ['errorCode' => 1, 'msg' => '待发货订单信息读取状态修改失败'];
            }
        }
    } else if ($do == 'refund_order') {       //待退款订单信息设置
        $Data = ['refund_order_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
        //设置不查看时将原来的信息改为已读状态
        if (!empty($msg_order) && $result['errorCode'] == 0 && $status == 0) {
            $tag = 0;
            foreach ($msg_order as $k => $v) {
                if ($v['Order_Status'] == 5 && $v['msg_status'] == 0) {
                    $postData = ['id' => $v['id'], 'transData' => ['msg_status' => 1, 'modify_time' => time()]];
                    $status_res = message::updateMsgOrder($postData);
                    if ($status_res['errorCode'] != 0) {
                        $tag += 1;
                    }
                }
            }
            if ($tag == 0) {
                $result = ['errorCode' => 0, 'msg' => '待退款订单信息读取状态修改成功'];
            } else {
                $result = ['errorCode' => 1, 'msg' => '待退款订单信息读取状态修改失败'];
            }
        }
    } else if ($do == 'return_order') {       //待退货订单信息设置
        $Data = ['return_order_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
        //设置不查看时将原来的信息改为已读状态
        if (!empty($msg_order) && $result['errorCode'] == 0 && $status == 0) {
            $tag = 0;
            foreach ($msg_order as $k => $v) {
                if ($v['Order_Status'] == 6 && $v['msg_status'] == 0) {
                    $postData = ['id' => $v['id'], 'transData' => ['msg_status' => 1, 'modify_time' => time()]];
                    $status_res = message::updateMsgOrder($postData);
                    if ($status_res['errorCode'] != 0) {
                        $tag += 1;
                    }
                }
            }
            if ($tag == 0) {
                $result = ['errorCode' => 0, 'msg' => '待退货订单信息读取状态修改成功'];
            } else {
                $result = ['errorCode' => 1, 'msg' => '待退货订单信息读取状态修改失败'];
            }
        }
    } else if ($do == 'order_distribute') {       //成为分销商订单信息设置
        $Data = ['order_distribute_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
        //设置不查看时将原来的信息改为已读状态
        if (!empty($msg_distribute) && $result['errorCode'] == 0 && $status == 0) {
            $tag = 0;
            foreach ($msg_distribute as $k => $v) {
                if ($v['Account_ID'] == 0 && $v['msg_status'] == 0) {
                    $postData = ['id' => $v['id'], 'transData' => ['msg_status' => 1, 'modify_time' => time()]];
                    $status_res = message::updateMsgDistribute($postData);
                    if ($status_res['errorCode'] != 0) {
                        $tag += 1;
                    }
                }
            }
            if ($tag == 0) {
                $result = ['errorCode' => 0, 'msg' => '下单成为分销商信息读取状态修改成功'];
            } else {
                $result = ['errorCode' => 1, 'msg' => '下单成为分销商信息读取状态修改失败'];
            }
        }
    } else if ($do == 'join_distribute') {       //会员加入分销商信息设置
        $Data = ['join_distribute_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
        //设置不查看时将原来的信息改为已读状态
        if (!empty($msg_distribute) && $result['errorCode'] == 0 && $status == 0) {
            $tag = 0;
            foreach ($msg_distribute as $k => $v) {
                if ($v['Account_ID'] > 0 && $v['msg_status'] == 0) {
                    $postData = ['id' => $v['id'], 'transData' => ['msg_status' => 1, 'modify_time' => time()]];
                    $status_res = message::updateMsgDistribute($postData);
                    if ($status_res['errorCode'] != 0) {
                        $tag += 1;
                    }
                }
            }
            if ($tag == 0) {
                $result = ['errorCode' => 0, 'msg' => '成为分销商信息读取状态修改成功'];
            } else {
                $result = ['errorCode' => 1, 'msg' => '成为分销商信息读取状态修改失败'];
            }
        }
    }
    
    if ($result['errorCode'] == 0) {    //改变配置，删除缓存
        $url = SHOP_URL.'api/update_cache.php?cacheType=msgconfig&bizAccount='.$BizAccount;
        curlInterFace($url);
    }
    
    echo json_encode($result);
    die;
}

//读取信息配置
$msg_config = $DB->GetRs("biz_msg_config", "*", "WHERE Biz_ID=".$BizID);

if (empty($msg_config)) {
    //没有配置信息时，自动生成配置数据，默认信息全部显示
    $res = $DB->Add('biz_msg_config',['Biz_ID' => $BizID]);
    if ($res) {
        $config_id = $DB->insert_id();
        $msg_config = $DB->GetRs("biz_msg_config", "*", "WHERE id=".$config_id);
    } else {
        echo '<script>alert("没有信息配置，生成配置数据失败。");history.back();</script>';
        exit;
    }
}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>消息设置</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l" href='javascript:self.location=document.referrer;'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>消息设置
    </div>
    <div class="shop_list">
        <ul>
            <li>订单设置</li>
            <li>
                <span class="l">待确认订单信息是否显示</span><span class="r"><input id="confirm_order" class="toggle-switch" type="checkbox" <?php if ($msg_config['confirm_order_msg']) {echo 'checked=""';} ?>></span>
            </li>
            <li>
                <span class="l">待发货订单信息是否显示</span><span class="r"><input id="delivery_order" class="toggle-switch" type="checkbox" <?php if ($msg_config['delivery_order_msg']) {echo 'checked=""';} ?>></span>
            </li>
            <li>
                <span class="l">待退款订单信息是否显示</span><span class="r"><input id="refund_order" class="toggle-switch" type="checkbox" <?php if ($msg_config['refund_order_msg']) {echo 'checked=""';} ?>></span>
            </li>
            <li>
                <span class="l">待退货订单信息是否显示</span><span class="r"><input id="return_order" class="toggle-switch" type="checkbox" <?php if ($msg_config['return_order_msg']) {echo 'checked=""';} ?>></span>
            </li>
            <li>分销设置</li>
            <li>
                <span class="l">分销下单信息是否显示</span><span class="r"><input id="order_distribute" class="toggle-switch" type="checkbox" <?php if ($msg_config['order_distribute_msg']) {echo 'checked=""';} ?>></span>
            </li>
            <li>
                <span class="l">加入分销信息是否显示</span><span class="r"><input id="join_distribute" class="toggle-switch" type="checkbox" <?php if ($msg_config['join_distribute_msg']) {echo 'checked=""';} ?>></span>
            </li>
        </ul>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
$(function() {
    $('input[type="checkbox"]').click(function(){
        if($(this).is(':checked')) {
           var status = 1;
        } else {
           var status = 0;
        }
        $.post('?act=msg_setting&do='+$(this).attr('id'), {status: status},function(data){
            if (data.errorCode != 0) {
                window.location.reload();
                layer.open({
                    content: data.msg,
                    time: 1,
                });
            }
        }, 'json');
    });
})
</script>