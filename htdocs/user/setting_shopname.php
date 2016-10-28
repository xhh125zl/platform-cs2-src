<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/shopconfig.class.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';

    if ($do == 'shopname') {
        $shopname = isset($_POST['shopname']) ? cleanJsCss($_POST['shopname']) : '';

        $data = [
            'Biz_Account' => $BizAccount,
            'configData' => [
                'ShopName' => $shopname,
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
    exit();
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
    	<a href="javascript:history.back()" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>店铺名称
    </div>
    <div class="box_setting">
    	<input type="text" id="shopname" maxlength="30" value="<?php echo input_output($config['ShopName']); ?>">
    </div>
    <div class="sub_setting">
    	<input type="button" class="btnsubmit" value="保存">
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".btnsubmit").click(function(){
        var shopname = $.trim($("#shopname").val());
        if (shopname == '') {
            $('#shopname').attr('style','border: 1px solid red;');
        } else {
            $('#shopname').removeAttr('style');
            $.post("?act=setting_shopname&inajax=1&do=shopname", {shopname:shopname}, function(json){
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
