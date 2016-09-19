<?
require_once "lib/order.php";
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>订单详情</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/jquery.SuperSlide.2.1.1.js"></script>
<body>
<div class="w">
    <div class="slideTxtBox">
        <div class="hd">
            <ul><li>待处理</li><li>未付款</li><li>退款单</li><li>已完成</li><li>已关闭</li></ul>
        </div>
        <div class="bd order_x">
            <ul>
                <li>
                    <span class="left">订单编号：</span>
                    <span class="left"><?=$orderDetail['Order_ID']?></span>
                </li>
                <li>
                    <span class="left">订单时间：</span>
                    <span class="left"><?=date('Y-m-d H:i:s', $orderDetail['Order_CreateTime'])?></span>
                </li>
                <li>
                    <span class="left">订单状态：</span>
                    <span class="left" style="color:red">
                        <?
                        switch ($orderDetail['Order_Status'])
                        {
                            case 0:
                                echo "待处理";
                                break;
                            case 1:
                                echo "未付款";
                                break;
                            case 2:
                                echo "已付款";
                                break;
                            case 3:
                                echo "已发货";
                                break;
                            case 4:
                                echo "已完成";
                                break;
                            default:
                                echo "状态获取失败";
                        }
                        ?>
                    </span>
                </li>
                <li>
                    <span class="left">订单总价：</span>
                    <span class="left" style="color:red"><?=$orderDetail['Order_TotalPrice']?></span>
                </li>
                <li>
                    <span class="left">订单备注：</span>
                    <span class="left"><?=$orderDetail['Order_Remark']?></span>
                </li>
                <li>
                    <span class="left">收货地址：</span>
                    <span class="left"><?php echo $Province.$City.$Area.'【'.$orderDetail["Address_Name"].'，'.$orderDetail["Address_Mobile"].'】' ?></span>
                </li>
                <li>
                    <span class="left">配送方式：</span>
                    <span class="left">
                        <?php

                        if(empty($Shipping)){
                            echo "暂无信息";
                        }else{
                            if(empty($Shipping["Express"])){
                                echo "暂无信息";
                            }else{
                                echo $Shipping["Express"];
                            }
                        }
                        //echo empty($Shipping)?"":$Shipping["Express"]
                        ?><strong style="color:#FF0000;">
                            <?php if(empty($Shipping["Price"])){?>
                                免运费
                            <?php }else{
                                $fee = $Shipping["Price"];
                                ?>
                                ￥<?php echo $Shipping["Price"];?>
                            <?php }?>

                        </strong>
                    </span>
                </li>
                <?
                foreach (json_decode($orderDetail['Order_CartList'], true) as $key => $val) {
                    foreach ($val as $goodskey => $goodsval) {
                ?>
                <li>
                    <div class="pro_xt" style="padding:0">
                        <div class="img_xt"><a href="#"><img src="<?=$goodsval['ImgPath']?>" height="90" width="90"></a></div>
                        <dl class="info_xt">
                            <dd class="name_xt"><a href="#"><?=$goodsval['ProductsName']?></a></dd>
                            <dd>￥<?=$goodsval['ProductsPriceX']?>×<?=$goodsval['Qty']?>=￥<?=$goodsval['ProductsPriceX'] * $goodsval['Qty']?></dd>
                            <dd>下单时间：<?=date('Y-m-d H:i:s', $orderDetail['Order_CreateTime'])?></dd>
                        </dl>
                        <div class="clear"></div>
                    </div>
                </li>
                <?}}?>
                <li>
                    <span class="right">订单总价：<a style="color:red">￥<?=$orderDetail['Order_TotalPrice'] - $fee?><?if ($fee > 0) {?>+￥<?=$fee?>(运费)<?}?>=￥<?=$orderDetail['Order_TotalPrice']?></a></span>
                </li>
            </ul>
            <ul>
                <li></li>
            </ul>
            <ul>
                <li></li>
            </ul>
            <ul>
                <li></li>
            </ul>
            <ul>
                <li></li>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="kb"></div>
        <div class="bottom">
            <div class="footer">
                <ul style="margin-top: 5px;">
                    <li><a href="#">
                            <i class="fa  fa-home fa-2x" aria-hidden="true"></i><br> 首页
                        </a></li>
                    <li><a href="#">
                            <i class="fa fa-gift fa-2x" aria-hidden="true"></i><br>开店
                        </a></li>
                    <li><a href="#">
                            <i class="fa  fa-shopping-cart fa-2x" aria-hidden="true"></i><br> 购物
                        </a></li>
                    <li><a href="#">
                            <i class="fa  fa-user fa-2x" aria-hidden="true"></i><br> 我的
                        </a></li>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">jQuery(".slideTxtBox").slide();</script>
</div>
</body>
</html>