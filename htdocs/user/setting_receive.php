<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/shopconfig.class.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';

    if ($do == 'day') {
        $day = isset($_POST['day']) ? (int)$_POST['day'] : 0;

        $data = [
            'Biz_Account' => $BizAccount,
            'configData' => [
                'Confirm_Time' => $day * 86400,
            ],
        ];
        
        $result = shopconfig::updatecolumn($data);
        if (isset($result['errorCode']) && $result['errorCode'] == 0) {
            $res = ['errorCode' => 0, 'msg' => '更新成功'];
        } else {
            $res = ['errorCode' => 1, 'msg' => '更新失败'];
        }
        echo json_encode($res);
    }
    exit;
}

//获取配置信息
$result = shopconfig::getConfig(['Biz_Account' => $BizAccount]);
$config = $result['data'];

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>店铺配置</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<body>
<div class="w">
	<!-- 店铺名称  -->
	<div class="back_x">
    	<a href="javascript:history.back()" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>自动收货时间(天)
    </div>
    <div class="box_setting">
    	<input type="number" id="day" maxlength="2" value="<?php echo $config['Confirm_Time'] / 86400;?>">
    </div>
    <div class="sub_setting">
    	<input type="button" class="btnsubmit" value="保存">
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".btnsubmit").click(function(){
        var day = $.trim($("#day").val());
        var reg = new RegExp("^[0-9]*$");  
        if (day == '') {
            $('#day').attr('style', 'border: 1px solid red;');
        } else if (!reg.test(day)) {
            $('#day').removeAttr('style');
            layer.open({content:'只允许正数数字', time:2});
            return false; 
        } else {
            $('#day').removeAttr('style');
            $.post("?act=setting_receive&inajax=1&do=day", {day:day}, function(json){
                if (json.errorCode == 0) {
                    location.href="admin.php?act=setting";
                } else {
                    layer.open({content: json.msg, shadeClose: false, btn: '确定'});
                }
            },'json');
        }
    });
});
</script>

</body>
</html>
