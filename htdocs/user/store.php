<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/product.class.php';
require_once CMS_ROOT . '/include/api/count.class.php';
require_once CMS_ROOT . '/include/api/shopconfig.class.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;
if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';
    if ($do == 'count') {
        $data = [
            'Biz_Account' => $BizAccount,
        ];
        $counter = count::countIncome($data);

        echo json_encode($counter);
    }

    exit();
}

//获取配置信息
$data = [
    'Biz_Account' => $BizAccount,
];
$result = shopconfig::getConfig($data);
$config = $result['data'];


?><!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>店铺管理</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<body>
<div class="w">
    <div class="head_bg">
        <span class="head_pho l"><a><img src="<?php echo IMG_SERVER . $config['ShopLogo'];?>"></a></span>
        <span class="head_name l"><a><?php echo $config['ShopName'];?></a></span>
        <span class="head_pho r"><a><i class="fa  fa-eye fa-x" aria-hidden="true"></i></a></span>
    </div>
    <div  class="clear"></div>
    <div class="income_x">
        <ul class="income_t">
            <li style="border-right:1px #fff solid;">
                <a>本月收入（元）<p class="price_t" id="monthIncome">0</p></a>
            </li>
            <li>
                <a>累计收入（元）<p class="price_t" id="totalIncome">0</p></a>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="income_today">
    	<span class="l">
        	<p class="in">今日开店收入（元）</p>
            <p class="price_in" id="dayIncome">0</p>
        </span>
        <span class="r"><a><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></a></span>
    </div>
    <div class="clear"></div>
    <div class="daily_x">
        <ul>
            <li>
                <p><strong>0</strong></p>
                <p>今日访客</p>
            </li>
            <li>
                <p><strong id="orderCount">0</strong></p>
                <p>本月订单</p>
            </li>
            <li>
                <p><strong id="allMoney">0</strong></p>
                <p>本月交易额</p>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="product_x">
        <ul>
            <li style="border-right:1px #eee solid;">
                <a id="pro_x"><i class="fa  fa-plus-square fa-x" aria-hidden="true"></i>&nbsp;产品发布</a>
            </li>
            <li>
                <a href='?act=products'><i class="fa  fa-check-square fa-x" aria-hidden="true"></i>&nbsp;产品管理</a>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="plate_x">
        <ul class="list_x">
            <li>
                <a href='?act=order_list'><img src="../static/user/images/ico_07.png" width="25" height="25"><p>订单管理</p></a>
            </li>
            <li>
                <a href='?act=user_list'><img src="../static/user/images/ico_02.png" width="25" height="25"><p>我的会员</p></a>
            </li>
            <li>
                <a href='?act=setting'><img src="../static/user/images/ico_04.png" width="25" height="25"><p>店铺配置</p></a>
            </li>
            <li>
                <a href='?act=distribute_list'><img src="../static/user/images/ico_01.png" width="25" height="25"><p>分销管理</p></a>
            </li>
            <li>
                <a href='?act=data_statistics'><img src="../static/user/images/ico_06.png" width="25" height="25"><p>数据统计</p></a>
            </li>
            <li>
                <a href='?act=financial_analysis'><img src="../static/user/images/ico_03.png" width="25" height="25"><p>财务分析</p></a>
            </li>
            <li>
                <a href='?act=learn'><img src="../static/user/images/ico_08.png" width="25" height="25"><p>学习中心</p></a>
            </li>
            <li>
                <a href='?act=web'><img src="../static/user/images/ico_05.png" width="25" height="25"><p>网页版</p></a>
            </li>
        </ul>
    </div>
    <div class="kb"></div>
    <!-- footer nav -->
<?php
$homeUrl = '/api/' . $UsersID . '/shop/';
$cartUrl = $homeUrl . 'allcategory/';
$ucenter = $homeUrl . 'member/';
?>    
<!--
    <div class="bottom" >
        <div class="footer">
            <ul style="margin-top: 5px;">
                <li><a href="<?php echo $homeUrl;?>">
                        <i class="fa  fa-home fa-2x" aria-hidden="true"></i><br> 首页
                    </a></li>
                <li><a href="/user/admin.php?act=store" style="color:#ff3600">
                        <i class="fa fa-gift fa-2x" aria-hidden="true"></i><br>开店
                    </a></li>
                <li><a href="<?php echo $cartUrl;?>">
                        <i class="fa  fa-shopping-cart fa-2x" aria-hidden="true"></i><br> 购物
                    </a></li>
                <li><a href="<?php echo $ucenter;?>">
                        <i class="fa  fa-user fa-2x" aria-hidden="true"></i><br> 我的
                    </a></li>
            </ul>
        </div>
    </div>
    -->
    <!--//footer nav -->
</div>
<script type="text/javascript">
$("#pro_x").click(function(){
    layer.open({
    content: '<div class="sto_bg"><ul><li><h3>产品库挑选</h3><p>没有产品，想开分销商城的用户，不能发布自己的产品，只能代销。</p><span class="r"><input type="button"value="立即进入" onclick="location.href=\'?act=search\'"></span><div class="clear"></div></li><li><h3>开通官方分销商城</h3><p>有产品，可绑定自己的微信公众号，独立运营，同时还可以把产品推荐到产品库，同其他代销。</p><span class="r"><input type="button"value="立即进入" onclick="location.href=\'?act=product_add\'"></span><div class="clear"></div></li><li><h3>供货商，只供货</h3><p>只向平台提供货源，不做自己的独立的店铺。</p><span class="r"><input type="button"value="立即进入" onclick="location.href=\'?act=product_supply\'"></span><div class="clear"></div></li></ul></div>'
    });	
})	

$(function(){
    $.get('?act=store&inajax=1&do=count', {}, function(json) {
        if (json.errorCode == '0') {
            var counter = json.count;
             $("#totalIncome").html(counter.totalCount.Amount);
             $("#monthIncome").html(counter.monthCount.Amount);
             $("#dayIncome").html(counter.dayCount.Amount);
             $('#orderCount').html(counter.monthCount.orderCount);
             $('#allMoney').html(counter.monthAllMoney.Amount);
        } else {
            alert('用户统计数据获取失败，请刷新此页面重试');
        }
    }, 'json')

})
<?php


?>

</script>
</body>
</html>
