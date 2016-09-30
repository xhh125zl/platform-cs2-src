<?
require_once "lib/order.php";
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>未付款_订单管理</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/jquery.SuperSlide.2.1.1.js"></script>
<body>
<div class="w">
    <div class="bj_x">
        <form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
            <div class="box">
                <span class="l"><a href='?act=store'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a></span>
                <input type="text" name="Order_ID" class="sousuo_x" placeholder="请输入您要搜索的订单号">
                <a><span class="ss1_x">搜索</span></a>
            </div>
        </form>
    </div>
    <div class="slideTxtBox">
        <div class="hd">
            <ul>
                <a href="?act=order_confirm"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_confirm') { echo 'on'; } ?>">待确认</li></a>
                <a href="?act=order_unpaid"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_unpaid') { echo 'on'; } ?>">未付款</li></a>
                <a href="?act=order_paid"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_paid') { echo 'on'; } ?>">已付款</li></a>
                <a href="?act=order_delivered"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_delivered') { echo 'on'; } ?>">已发货</li></a>
                <a href="?act=order_completed"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_completed') { echo 'on'; } ?>">已完成</li></a>
            </ul>
        </div>
        <div class="bd">
            <ul>
                <?
                    if (isset($resArr[1]) && count($resArr[1]) > 0) {
                        foreach ($resArr[1] as $k => $v){
                ?>
                <li>
                    <div class="line_x"><span class="l">订单号：<a href="?act=order_details&orderid=<?=$v['Order_ID']?>"><?=$v['Order_ID']?></a></span><span class="state r">未付款</span></div>
                    <div class="clear"></div>
                    <?
                        foreach (json_decode($v['Order_CartList'], true) as $key => $val) {
                            foreach ($val as $goodskey => $goodsval) {
                    ?>
                            <div class="pro_xt">
                                <div class="img_xt"><a href="#"><img src="<?=$goodsval['ImgPath']?>" height="90" width="90"></a></div>
                                <dl class="info_xt">
                                    <dd class="name_xt"><a href="#"><?=$goodsval['ProductsName']?></a></dd>
                                    <dd>￥<?=$goodsval['ProductsPriceX']?>×<?=$goodsval['Qty']?>=￥<?=$goodsval['ProductsPriceX'] * $goodsval['Qty']?></dd>
                                    <dd>下单时间：<?=date('Y-m-d H:i:s', $v['Order_CreateTime'])?></dd>
                                </dl>
                                <div class="clear"></div>
                            </div>
                        <?}}?>
                </li>
                <?}} else {?>
                        <h3>暂无此状态订单</h3>
                    <?}?>
            </ul>
        </div>
    </div>
    <script type="text/javascript">
        //jQuery(".slideTxtBox").slide();
        $(function() {
            $(".ss1_x").click(function() {
                $("form").submit();
            })
        })
    </script>
</div>
</body>
</html>
