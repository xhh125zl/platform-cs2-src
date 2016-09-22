<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/shopconfig.class.php';

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
<body>
<div class="w">
	<div class="back_x">
    	<a href='javascript:history.back()' class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>店铺配置
    </div>
    <!--<p class="function_x">基础设置</p>-->
    <div class="shop_list">
    	<ul>
        	<li>
            	<a><span class="l">店铺头像<span><img src='<?php echo $config['ShopLogo'];?>' width=30 height=30 /></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a><span class="l">店铺名称<span><?php echo $config['ShopName'];?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a><span class="l">微信号<span><?php echo $config['Users_WechatAccount'];?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li><!--
            <li>
            	<a><span class="l">独立域名<span>zzzzzzzzzzzzz</span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>-->
            <li>
            	<a><span class="l">店铺分享<span><?php
                    if (trim($config['ShareIntro']) != '') {
                        echo sub_str($config['ShareIntro'], 16);
                    }
                 ?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a><span class="l">店铺二维码<span>店铺二维码</span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
        	<li>
            	<a><span class="l">店铺公告<span><?php
                    if (trim($config['ShopAnnounce']) != '') {
                        echo sub_str($config['ShopAnnounce'], 16);
                    }
                 ?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>

            <li>
            	<a><span class="l">退货地址<span><?php echo $config['RecieveAddress'];?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a><span class="l">退货电话<span><?php echo $config['RecieveMobile'];?></span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<a><span class="l">自动收货时间<span><?php if ($config['Confirm_Time'] > 0) { echo $config['Confirm_Time'] / 86400;} else {echo 0;}?>天</span></span><span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span></a>
            </li>
            <li>
            	<span class="l">分销商是否可以代销其他产品</span><span class="r"><input class="toggle-switch" type="checkbox"<?php if ($config['allowDistributeB2c'] == 1) echo ' checked=""'?>></span>
            </li>

        </ul>
    </div>
</div>
</body>
</html>
