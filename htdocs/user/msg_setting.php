<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";

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
    if ($do == 'delivery_order') {
        $Data = ['delivery_order_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
    } else if ($do == 'return_order') {
        $Data = ['return_order_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
    } else if ($do == 'refund_order') {
        $Data = ['refund_order_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
    } else if ($do == 'order_distribute') {
        $Data = ['order_distribute_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
    } else if ($do == 'join_distribute') {
        $Data = ['join_distribute_msg' => $status, 'setting_lastTime' => time()];
        $result = set_msg_config($Data);
    }
    echo json_encode($result);
    die;
}

$msg_config = $DB->GetRS("biz_msg_config", "*", "WHERE Biz_ID=".$BizID);

if (empty($msg_config)) {
    echo '<script>alert("获取设置参数失败");history.back();</script>';
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
                <span class="l">发货订单信息是否显示</span><span class="r"><input id="delivery_order" class="toggle-switch" type="checkbox" <?php if ($msg_config['delivery_order_msg']) {echo 'checked=""';} ?>></span>
            </li>
            <li>
                <span class="l">退货订单信息是否显示</span><span class="r"><input id="return_order" class="toggle-switch" type="checkbox" <?php if ($msg_config['return_order_msg']) {echo 'checked=""';} ?>></span>
            </li>
            <li>
                <span class="l">退款订单信息是否显示</span><span class="r"><input id="refund_order" class="toggle-switch" type="checkbox" <?php if ($msg_config['refund_order_msg']) {echo 'checked=""';} ?>></span>
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
                    time: 1
                });
            }
        }, 'json');
    });
})
</script>