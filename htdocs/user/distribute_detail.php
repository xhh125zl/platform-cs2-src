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
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l" href="javascript:history.back();"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>分销商详情
    </div>
    <div class="clear"></div>
    <div class="list_table">
    	<table width="96%" class="table"> 
            <tr> 
                <th>推荐人</th> 
                <td><?php echo $distribute_info['invite_Nickname']; ?></td> 
            </tr>
            <tr> 
                <th>店名</th> 
                <td><?php echo $distribute_info['Shop_Name']; ?></td> 
            </tr>
            <tr> 
                <th>微信名</th> 
                <td><?php echo $distribute_info['User_Nickname']; ?></td> 
            </tr>
            <tr> 
                <th>佣金余额</th> 
                <td>¥<?php echo $distribute_info['balance']; ?></td>
            </tr>
            <tr> 
                <th>审核状态</th> 
                <td><?php echo $distribute_info['Is_Audit'] ? '已通过' : '未通过'; ?></td> 
            </tr>
            <tr> 
                <th>总收入</th> 
                <td>¥<?php echo $distribute_info['Total_Income']; ?></td> 
            </tr>
            <tr> 
                <th>销售额</th> 
                <td>¥<?php echo $distribute_info['Total_Sales']; ?></td> 
            </tr>
            <tr> 
                <th>分销商等级</th> 
                <td><?php echo $distribute_info['dis_levelName']; ?></td>
            </tr>
            <!-- <tr> 
                <th>爵位</th> 
                <td>无</td> 
            </tr> -->
            <tr> 
                <th>加入时间</th> 
                <td><?php echo date('Y-m-d H:i:s', $distribute_info['Account_CreateTime']); ?></td>
            </tr>
            <tr> 
                <th>操作</th> 
                <td>
                <?php if ($distribute_info['status'] == 1) { ?>
                    <a id="disable">禁用</a><!--  | <a href="">下属</a> -->
                <?php } else { ?>
                    <a id="enable">启用</a>
                <?php } ?>
                </td> 
            </tr>
        </table> 
    </div>
</div>
</body>
<script type="text/javascript">
    $(function(){
        $('#disable').click(function(){
            $.ajax({
                type: 'POST',
                url: 'lib/distribute.php',
                data: 'action=disable&Account_ID='+<?php echo $distribute_info['Account_ID']; ?>,
                success: function(data){
                    if (data.errorCode == 0) {
                        layer.open({
                            content: '禁用成功'
                        });
                    } else {
                        layer.open({
                            content: '禁用失败',
                            btn: '确定'
                        });
                    }
                    window.location.reload();
                },
                dataType: 'json'
            });
        });
        $('#enable').click(function(){
            $.ajax({
                type: 'POST',
                url: 'lib/distribute.php',
                data: 'action=enable&Account_ID='+<?php echo $distribute_info['Account_ID']; ?>,
                success: function(data){
                    if (data.errorCode == 0) {
                        layer.open({
                            content: '启用成功'
                        });
                    } else {
                        layer.open({
                            content: '启用失败',
                            btn: '确定'
                        });
                    }
                    window.location.reload();
                },
                dataType: 'json'
            });
        });
    });
</script>
</html>
