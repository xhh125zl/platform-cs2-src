<?php
require_once "/config.inc.php";
require_once(CMS_ROOT . '/include/api/count.class.php');

//检查用户是否登录
if(empty($BizAccount)){
    header("location:/biz/login.php");
}

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
<title>财务分析</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<body style="background:#ff5500">
<div class="w">
    <div class="data_title">财务分析</div>
    <div class="Financial_list">
    	<ul>
        	<li>
				<span class="left">今日收入（￥）</span>
                <span class="right Financial_m"><?php if (isset($count['dayCount']['Amount'])) { echo $count['dayCount']['Amount']; } else { echo 0; } ?></span>
                <div class="clear"></div>
            </li>
            <li>
				<span class="left">本周收入（￥）</span>
                <span class="right Financial_m"><?php if (isset($count['weekCount']['Amount'])) { echo $count['weekCount']['Amount']; } else { echo 0; } ?></span>
                <div class="clear"></div>
            </li>
            <li>
				<span class="left">本月收入（￥）</span>
                <span class="right Financial_m"><?php if (isset($count['monthAllMoney']['Amount'])) { echo $count['monthAllMoney']['Amount']; } else { echo 0; } ?></span>
                <div class="clear"></div>
            </li>
            <li>
				<span class="left">累计收入（￥）</span>
                <span class="right Financial_m"><?php if (isset($count['totalCount']['Amount'])) { echo $count['totalCount']['Amount']; } else { echo 0; } ?></span>
                <div class="clear"></div>
            </li>
            <li>
				<span class="left">账户余额（￥）</span>
                <span class="right Financial_m">0</span>
                <div class="clear"></div>
            </li>
        </ul>
    </div>
</div>
</body>
</html>
