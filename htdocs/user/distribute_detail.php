<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/distribute.class.php';

if (isset($_GET['distributeid'])) {
    $Account_ID =$_GET['distributeid'];
} else {
    echo '<script>history.back();</script>';
}

$transfer = ['Biz_Account' => $BizAccount, 'Account_ID' => $Account_ID, 'level' => $_GET['level']];
$res = distribute::getDistribute($transfer);

if (isset($res['errorCode']) && $res['errorCode'] == 0) {
    $distribute_info = $res['data'][0];
}

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>分销商详情</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>分销管理
    </div>
    <div class="clear"></div>
    <div class="list_table">
    	<table width="96%" class="table"> 
            <tr> 
                <th>推荐人</th> 
                <td>来自总店</td> 
            </tr>
            <tr> 
                <th>店名</th> 
                <td><?php echo $distribute_info['Shop_Name']; ?></td> 
            </tr>
            <tr> 
                <th>微信名</th> 
                <td>信息缺失</td> 
            </tr>
            <tr> 
                <th>佣金余额</th> 
                <td>¥0.00</td>
            </tr>
            <tr> 
                <th>审核状态</th> 
                <td>已通过</td> 
            </tr>
            <tr> 
                <th>总收入</th> 
                <td>¥0.00</td> 
            </tr>
            <tr> 
                <th>销售额</th> 
                <td>¥8019.00</td> 
            </tr>
            <tr> 
                <th>分销商等级</th> 
                <td>普通分销商</td>
            </tr>
            <tr> 
                <th>爵位</th> 
                <td>无</td> 
            </tr>
            <tr> 
                <th>加入时间</th> 
                <td><?php echo date('Y-m-d H:i:s', $distribute_info['Account_CreateTime']); ?></td>
            </tr>
            <tr> 
                <th>操作</th> 
                <td><a href="">禁用</a> | <a href="#">下属</a></td> 
            </tr>
        </table> 
    </div>
</div>
</body>
</html>
