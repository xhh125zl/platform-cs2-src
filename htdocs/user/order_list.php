<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/ImplOrder.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 10;

$Order_Status = isset($_GET['status']) ? $_GET['status'] : 0;  //订单状态

$transfer = ['Biz_Account' => $BizAccount, 'pageSize' => $pageSize, 'Order_ID' => (isset($_POST['Order_ID']) ? (int)$_POST['Order_ID'] : ''), 'Order_Status' => $Order_Status];
$result = ImplOrder::getOrders($transfer, $p);

if (isset($result['errorCode']) && $result['errorCode'] != 0) {
    $total = 0;
    $totalPage = 1;
    $orders = [];
} else {
    $total = $result['totalCount'];
    $totalPage = ceil($result['totalCount'] / $pageSize);
    $orders = $result['data'];
}

//分页
$page = new page();
$page->set($pageSize, $total, $p);

$infolist = [];
if (count($orders) > 0) {
    foreach ($orders as $row) {
        $row['OrderCartList'] = json_decode($row['Order_CartList'], true);
        $row['OrderCreateTime'] = date('Y-m-d H:i:s', $row['Order_CreateTime']);
        unset($row['Order_CartList']);
        unset($row['Order_CreateTime']);
        $infolist[] = $row;
    }
}

$return = [
    'page' => [
        'pagesize' => count($infolist),
        'hasNextPage' => (count($infolist) >= $pageSize) ? 'true' : 'false',
        'total' => $total,
    ],
    'data' => $infolist,
];

if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    echo json_encode($return);
    exit();
}


?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>订单管理</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script type="text/javascript" src="../static/user/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
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
                <a href="?act=order_list&status=0"><li class="<?php if(isset($Order_Status) && $Order_Status == 0) { echo 'on'; } ?>">待确认</li></a>
                <a href="?act=order_list&status=1"><li class="<?php if(isset($Order_Status) && $Order_Status == 1) { echo 'on'; } ?>">未付款</li></a>
                <a href="?act=order_list&status=2"><li class="<?php if(isset($Order_Status) && $Order_Status == 2) { echo 'on'; } ?>">已付款</li></a>
                <a href="?act=order_list&status=3"><li class="<?php if(isset($Order_Status) && $Order_Status == 3) { echo 'on'; } ?>">已发货</li></a>
                <a href="?act=order_list&status=4"><li class="<?php if(isset($Order_Status) && $Order_Status == 4) { echo 'on'; } ?>">已完成</li></a>
            </ul>
        </div>
        <div class="bd">
            <ul class="orderList">
                <?php
                if (isset($infolist) && count($infolist) > 0) {
                    foreach ($infolist as $k => $v){
                ?>
                        <li>
                            <div class="line_x">
                                <span class="l">订单号：<a href="?act=order_details&orderid=<?=$v['Order_ID']?>"><?=$v['Order_ID']?></a></span>
                                <span class="state r">
                                    <?php
                                        switch ($v['Order_Status']) {
                                            case 0: echo '待确认';break;
                                            case 1: echo '未付款';break;
                                            case 2: echo '已付款';break;
                                            case 3: echo '已发货';break;
                                            case 4: echo '已完成';break;
                                        }
                                    ?>
                                </span>
                            </div>
                            <div class="clear"></div>
                            <?php
                            foreach ($v['OrderCartList'] as $key => $val) {
                                foreach ($val as $goodskey => $goodsval) {
                            ?>
                                    <div class="pro_xt">
                                        <div class="img_xt"><a href="#"><img src="<?=$goodsval['ImgPath']?>" height="90" width="90"></a></div>
                                        <dl class="info_xt">
                                            <dd class="name_xt"><a href="#"><?=$goodsval['ProductsName']?></a></dd>
                                            <dd>￥<?=$goodsval['ProductsPriceX']?>×<?=$goodsval['Qty']?>=￥<?=$goodsval['ProductsPriceX'] * $goodsval['Qty']?></dd>
                                            <dd>下单时间：<?=$v['OrderCreateTime']?></dd>
                                        </dl>
                                        <div class="clear"></div>
                                    </div>
                            <?php }} ?>
                        </li>
                <?php }} else { echo '<li style="text-align:center;color:#666;">暂无此类型订单</li>'; } ?>
            </ul>
        </div>
    </div>
</div>
<div class="clear"></div>
<!-- 点击加载更多 -->
<script id="order-row" type="text/html">
{{each data as v i}}
    <li>
        <div class="line_x">
            <span class="l">订单号：<a href="?act=order_details&orderid={{v.Order_ID}}">{{v.Order_ID}}</a></span>
            <span class="state r">
                {{if v.Order_Status == 0}} 待确认
                {{else if v.Order_Status == 1}} 未付款
                {{else if v.Order_Status == 2}} 已付款
                {{else if v.Order_Status == 3}} 已发货
                {{else if v.Order_Status == 4}} 已完成
                {{/if}}
            </span>
        </div>
        <div class="clear"></div>
        {{each v.OrderCartList as val key}}
            {{each val as goodsval goodskey}}
                <div class="pro_xt">
                    <div class="img_xt"><a href="#"><img src="{{goodsval.ImgPath}}" height="90" width="90"></a></div>
                    <dl class="info_xt">
                        <dd class="name_xt"><a href="#">{{goodsval.ProductsName}}</a></dd>
                        <dd>￥{{goodsval.ProductsPriceX}}×{{goodsval.Qty}}=￥{{goodsval.ProductsPriceX * goodsval.Qty}}</dd>
                        <dd>下单时间：{{v.OrderCreateTime}}</dd>
                    </dl>
                    <div class="clear"></div>
                </div>
            {{/each}}
        {{/each}}
    </li>
{{/each}}
</script>
<style>
#pagemore{clear:both;text-align:center;  color:#666; padding-top: 5px; padding-bottom:5px;}
#pagemore a{ height:30px; line-height:30px; text-align:center;display:block; background-color:#ddd; border-radius: 2px;}
</style>
<div id="pagemore">
<?php
    if (isset($orders) && count($orders) > 0) {
        if ($return['page']['hasNextPage'] == 'true') {
            echo '<a href="javascript:;" data-next-pageno="2">点击加载更多...</a>';    
        } else {
            echo '已经没有了...';
        }
    }
?>
</div>
</body>
</html>
<script type="text/javascript">
    $(function(){
        //搜索
        $(".ss1_x").click(function() {
            $("form").submit();
        })

        //加载更多
        var last_pageno = 1;
        $("#pagemore a").click(function(){
            var totalPage = <?php echo $totalPage;?>;
            var pageno = $(this).attr('data-next-pageno');
            var url = 'admin.php?act=order_list&status=' + <?php echo $Order_Status; ?> + '&p=' + pageno;


            //防止一页多次加载
            if (pageno == last_pageno) {
                return false;
            } else {
                last_pageno = pageno;
            }

            var nextPageno = parseInt(pageno);
            if (nextPageno > totalPage) {
                $("#pagemore").html('已经没有了...');
                return true;
            }

            $.post(url, {ajax: 1}, function(json){
                if (parseInt(json.page.pagesize) > 0) {
                    var html = template('order-row', json);
                    $("ul.orderList").append(html);
                }
                if (json.page.hasNextPage == 'true') {
                    $("#pagemore a").attr('data-next-pageno', nextPageno + 1);
                } else {
                    $("#pagemore").html('已经没有了...');
                }
            },'json')
        });

        //瀑布流加载翻页
        $(window).bind('scroll',function () {
            // 当滚动到最底部以上100像素时， 加载新内容
            if ($(document).height() - $(this).scrollTop() - $(this).height() < 100) {
                //已无数据可加载
                if ($("#pagemore").html() == '已经没有了...') {
                    return false;
                } else {
                    //模拟点击
                    $("#pagemore a").trigger('click');
                }
            }
        });

    });
</script>