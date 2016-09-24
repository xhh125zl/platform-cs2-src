<?php
if (!defined('USER_PATH')) exit();

include CMS_ROOT . '/include/api/shopconfig.class.php';

$qrcodeUrl = shopconfig::getQrcode($UsersID);

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
    	<img src="<?php echo $qrcodeUrl;?>" border="0" width=300 height=300 />
    </div>

</div>


</body>
</html>
