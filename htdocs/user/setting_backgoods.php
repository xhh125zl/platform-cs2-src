<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/shopconfig.class.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';

    if ($do == 'save') {
        $Province = isset($_POST['Province']) ? (int)$_POST['Province'] : 0;
        $City = isset($_POST['City']) ? (int)$_POST['City'] : 0;
        $Area = isset($_POST['Area']) ? (int)$_POST['Area'] : 0;
        $RecieveAddress = cleanJsCss($_POST['RecieveAddress']);
        $RecieveName = cleanJsCss($_POST['RecieveName']);
        $RecieveMobile = trim($_POST['RecieveMobile']);
        
        $data = [
            'Biz_Account' => $BizAccount,
            'addressData' => [
                'RecieveProvince' => $Province,
                'RecieveCity' => $City,
                'RecieveArea' => $Area,
                'RecieveAddress' => $RecieveAddress,
                'RecieveName' => $RecieveName,
                'RecieveMobile' => $RecieveMobile
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

//print_r($config);
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>退货配置</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<link href="../static/css/select2.css" rel="stylesheet"/>
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<script type='text/javascript' src='../static/member/js/global.js'></script>
<script type='text/javascript' src='../static/member/js/shop.js'></script>
<script type='text/javascript' src="../static/js/select2.js"></script>
<script type="text/javascript" src="../static/js/location.js"></script>
<script type="text/javascript" src="../static/js/area.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
		showLocation(<?php echo $config["RecieveProvince"];?>,<?php echo $config["RecieveCity"];?>,<?php echo $config["RecieveArea"];?>);
	//shop_obj.recieve_init();
});   
</script>

<body>
<div class="w">
	<div class="back_x">
    	<a href="javascript:history.back()" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>退货地址
    </div>

<div class="blank20"></div>

<form id="form1" name="form1" method="post">
    <div class="box_adr">

			<select name="Province"  id="loc_province">
				<option>选择省份</option>
			</select>
			<select name="City" id="loc_city">
				<option>选择城市</option>
			</select>
			<select name="Area"  id="loc_town">
				<option>选择区县</option>
			</select>

          <div class="clear"></div>

    	<input type="text" name="RecieveAddress" id="RecieveAddress" placeholder="收货地址" maxlength="30" value="<?php echo input_output($config['RecieveAddress']); ?>">
        <input type="text" name="RecieveName" placeholder="联系人"  maxlength="30" value="<?php echo input_output($config['RecieveName']); ?>">
        <input type="tel" name="RecieveMobile" placeholder="手机号" maxlength="11" value="<?php echo $config['RecieveMobile'];?>">
    </div>
        <div class="sub_setting">
    	<input type="button" class="btnsubmit" value="保存">
        </div>
    </form>
</div>
<script type="text/javascript">
$(function(){
    $(".btnsubmit").click(function(){
        $.post("?act=setting_backgoods&inajax=1&do=save", $("#form1").serialize(), function(json){
            if(json.errorCode == '0') {
                layer.open({content:json.msg, time:2, end:function() {
                     location.href="admin.php?act=setting";
                }});
            }
        },'json');
    });
});
</script>

</body>
</html>
