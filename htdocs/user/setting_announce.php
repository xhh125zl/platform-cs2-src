<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/shopconfig.class.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';

    if ($do == 'save') {
        $content = isset($_POST['content']) ? $_POST['content'] : 0;

        $data = [
            'Biz_Account' => $BizAccount,
            'configData' => [
                'ShopAnnounce' => $content,
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
<title>店铺公告</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a href="javascript:history.back()" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>店铺公告
    </div>
	<div class="shop_share">
    	<img src="http://img0.bdstatic.com/img/image/shouye/xinshouye/toux.png">
        <h3><textarea name="content" id="content"><?php echo $config['ShopAnnounce'];?></textarea></h3>
    </div> 
        <div class="sub_setting">
    	<input type="button" class="btnsubmit" value="保存">
    </div>
</div>
<script type="text/javascript">
$(function(){
    $(".btnsubmit").click(function(){
        var content = $("#content").val();
        $.post("?act=setting_announce&inajax=1&do=save", {content:content}, function(json){
            if(json.errorCode == '0') {
                layer.open({content:json.msg, time:2, end:function() {
                    history.back();
                }});
            }
        },'json')
    })
})
</script>

</body>
</html>
