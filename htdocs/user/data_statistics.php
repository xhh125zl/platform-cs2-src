<?php
require_once "config.inc.php";
require_once(CMS_ROOT . '/include/api/count.class.php');

$postdata['Biz_Account'] = $BizAccount;
$resArr = count::countIncome($postdata);
if (isset($resArr['errorCode']) && $resArr['errorCode'] == 0) {
    //获取统计数据
    $count = $resArr['count'];
} else {
    //获取数据失败
    echo '<script>history.back();</script>';
}

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>数据统计</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<body style="background:#1fb4f8">
<div class="w">
    <div class="data_title"><a class="l" href='?act=store' style="margin: -30px 0 0 20px;"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a><span style="margin-left: -20px;">数据统计</span></div>
    <div class="data_list">
    	<ul>
        	<li>
            	<dl>
                	<dt><span class="left">今日访客（人）</span><span class="right">0</span><div class="clear"></div></dt>
                    <dt><span class="left">今日订单（个）</span><span class="right"><?php if (isset($count['dayCount']['orderCount'])) { echo $count['dayCount']['orderCount']; } else { echo 0; } ?></span><div class="clear"></div></dt>
                    <dt><span class="left">今日销售额（￥）</span><span class="right"><?php if (isset($count['dayCount']['Amount'])) { echo $count['dayCount']['Amount']; } else { echo 0; } ?></span><div class="clear"></div></dt>
                </dl>
            </li>
            <li>
            	<dl>
                	<dt><span class="left">本周访客（人）</span><span class="right">0</span><div class="clear"></div></dt>
                    <dt><span class="left">本周订单（个）</span><span class="right"><?php if (isset($count['weekCount']['orderCount'])) { echo $count['weekCount']['orderCount']; } else { echo 0; } ?></span><div class="clear"></div></dt>
                    <dt><span class="left">本周销售额（￥）</span><span class="right"><?php if (isset($count['weekCount']['Amount'])) { echo $count['weekCount']['Amount']; } else { echo 0; } ?></span><div class="clear"></div></dt>
                </dl>
            </li>
            <li>
            	<dl>
                	<dt><span class="left">本月访客（人）</span><span class="right">0</span><div class="clear"></div></dt>
                    <dt><span class="left">本月订单（个）</span><span class="right"><?php if (isset($count['monthAllMoney']['orderCount'])) { echo $count['monthAllMoney']['orderCount']; } else { echo 0; } ?></span><div class="clear"></div></dt>
                    <dt><span class="left">本月销售额（￥）</span><span class="right"><?php if (isset($count['monthAllMoney']['Amount'])) { echo $count['monthAllMoney']['Amount']; } else { echo 0; } ?></span><div class="clear"></div></dt>
                </dl>
            </li>
        </ul>
    </div>
</div>
</body>
</html>
