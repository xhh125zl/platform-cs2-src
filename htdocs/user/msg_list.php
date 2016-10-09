<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/helper/page.class.php';

//echo $BizAccount.'###'.$UsersID.'###'.$BizID;die;
$msg_type = isset($_GET['type']) ? $_GET['type'] : 'order_msg';


?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>消息中心</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l" href='?act=store'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>消息中心
        <a class="r" href='?act=msg_setting'><i class="fa  fa-cog fa-2x" aria-hidden="true"></i></a>
    </div>
    <div class="slideTxtBox">
        <div class="hd msg_x">
            <ul>
                <a href="?act=msg_list&type=system_msg"><li class="<?php if(isset($msg_type) && $msg_type == 'system_msg') { echo 'on'; } ?>">系统</li></a>
                <a href="?act=msg_list&type=order_msg"><li class="<?php if(isset($msg_type) && $msg_type == 'order_msg') { echo 'on'; } ?>">订单</li></a>
                <a href="?act=msg_list&type=distribute_msg"><li class="<?php if(isset($msg_type) && $msg_type == 'distribute_msg') { echo 'on'; } ?>">分销</li></a>
                <a href="?act=msg_list&type=withdraw_msg"><li class="<?php if(isset($msg_type) && $msg_type == 'withdraw_msg') { echo 'on'; } ?>">提现</li></a>
            </ul>
        </div>
        <div class="learn_list">
            <ul class="msgList">
                <li>
                    <a href=''>某某分销商哈哈哈哈<p>2016-10-08 15:25:30</p></a>
                </li>
                <?php
                if(!empty($msg_list)){  
                    foreach($msg_list as $k => $v){         
                ?>
                <li>
                    <a href='?act=msg_detail&id=<?=$v['Msg_ID'] ?>'><?=$v['msg_title'] ?><p><?=$v['create_time'] ?></p></a>
                </li>
                <?php
                    }
                 } 
                ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>