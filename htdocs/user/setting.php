<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/shopconfig.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;
if ($inajax == 1) {

    $do = isset($_GET['do']) ? $_GET['do'] : '';

    if ($do == 'allowDistributeB2c') {
        $state = isset($_POST['state']) ? (int)$_POST['state'] : 0;
        $data = [
            'Biz_Account' => $BizAccount,
            'configData' => [
                'allowDistributeB2c' => $state,
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
<title>店铺配置</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<style>
img{border:none;vertical-align: middle;}
.tcd_x {
    position: fixed;
    width: 100%;
    bottom: 0px;
    left: 0;
    overflow: hidden;
    z-index: 100;
    background-color: red;
    text-align: center;
    line-height: 40px;
    color: #fff;
}
</style>
<body>
<div class="w">
	<div class="back_x">
    	<a href='?act=store' class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>店铺配置
    </div>
    <!--<p class="function_x">基础设置</p>-->
    <div class="shop_list">
    	<ul>
        	<li>
            	<a href='?act=setting_avatar'><span class="l">店铺头像<span><img style="border-radius:17px" src='<?php echo IMG_SERVER . getImageUrl($config['ShopLogo'], 1);?>' width=35 height=35 /></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a href="?act=setting_shopname"><span class="l">店铺名称<span><?php echo $config['ShopName'];?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a href="?act=setting_wechat"><span class="l">微信号<span><?php echo $config['Users_WechatAccount'];?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li><!--
            <li>
            	<a><span class="l">独立域名<span>zzzzzzzzzzzzz</span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>-->
            <li>
            	<a href='?act=setting_share'><span class="l">店铺分享<span><?php
                    if (trim($config['ShareIntro']) != '') {
                        echo sub_str($config['ShareIntro'], 16);
                    }
                 ?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a href='?act=setting_qrcode'><span class="l">店铺二维码<span><img src="../static/user/images/ewm.png" width="20" height="20"></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
        	<li>
            	<a href='?act=setting_announce'><span class="l">店铺公告<span><?php
                    if (trim($config['ShopAnnounce']) != '') {
                        echo sub_str($config['ShopAnnounce'], 16);
                    }
                 ?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a href='?act=setting_backgoods'><span class="l">退货地址<span><?php echo $config['RecieveAddress'];?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>

            <li>
            	<a href='?act=setting_receive'><span class="l">自动收货时间<span><?php if ($config['Confirm_Time'] > 0) { echo $config['Confirm_Time'] / 86400;} else {echo 0;}?>天</span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<span class="l">分销商是否可以代销其他产品</span><span class="r"><input id="allowDistributeB2c" class="toggle-switch" type="checkbox"<?php if ($config['allowDistributeB2c'] == 1) echo ' checked=""'?>></span>
            </li>

        </ul>
    </div>

<!-- logout -->
<div>
	<a class="tcd_x" href="login.php?do=logout">退出登录</a>
</div>
<!-- //logout -->

</div>
<script type="text/javascript">
$(function() {
    $("#allowDistributeB2c").click(function(){
       if($('#allowDistributeB2c').is(':checked')) {
           var state = 1;
       } else {
           var state= 0;
       }

       $.post('?act=setting&inajax=1&do=allowDistributeB2c', {state: state},function(json){

       }, 'json')
    })
})
</script>

</body>
</html>
