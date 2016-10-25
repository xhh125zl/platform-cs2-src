<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/shopconfig.class.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';

    if ($do == 'wechat') {
        $Users_WechatAccount = isset($_POST['Users_WechatAccount']) ? $_POST['Users_WechatAccount'] : 0;

        $data = [
            'Biz_Account' => $BizAccount,
            'usersData' => [
                'Users_WechatAccount' => $Users_WechatAccount,
            ],
        ];
        
        $result = shopconfig::updatecolumn($data);

        echo json_encode($result);
    }

    exit();
}

//获取配置信息
$data = [
    'Biz_Account' => $BizAccount,
];
$result = shopconfig::getConfig($data);
$config = $result['data'];

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>微信号</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<body>
<div class="w">
	<!-- 店铺名称  -->
	<div class="back_x">
    	<a href="javascript:history.back()" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>微信号
    </div>
    <div class="box_setting">
    	<input type="text" id="Users_WechatAccount" maxlength="30" value="<?php echo $config['Users_WechatAccount'];?>">
    </div>
    <div class="sub_setting">
    	<input type="button" class="btnsubmit" value="保存">
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".btnsubmit").click(function(){
        var Users_WechatAccount = $("#Users_WechatAccount").val();
        $.post("?act=setting_wechat&inajax=1&do=wechat", {Users_WechatAccount:Users_WechatAccount}, function(json){
            if(json.errorCode == '0') {
                layer.open({content:json.msg, time:2, end:function() {
                    location.href="admin.php?act=setting";
                }});
            }
        },'json')
    })
})
</script>

</body>
</html>
