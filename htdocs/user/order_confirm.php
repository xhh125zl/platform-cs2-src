<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/ImplOrder.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 2;
$Order_Status = 0;  //订单状态  待确定订单

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
    <title>已付款_订单管理</title>
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
                <a href="?act=order_confirm"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_confirm') { echo 'on'; } ?>">待确认</li></a>
                <a href="?act=order_unpaid"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_unpaid') { echo 'on'; } ?>">未付款</li></a>
                <a href="?act=order_paid"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_paid') { echo 'on'; } ?>">已付款</li></a>
                <a href="?act=order_delivered"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_delivered') { echo 'on'; } ?>">已发货</li></a>
                <a href="?act=order_completed"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'order_completed') { echo 'on'; } ?>">已完成</li></a>
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
                                <span class="state r">已付款</span>
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
                    <?php }} else { ?>
                    <h3>暂无此状态订单</h3>
                <?php } ?>
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
<div class="clear"></div>
<!-- 点击加载更多 -->
<script id="order-row" type="text/html">
{{each data as v i}}
    <li>
        <div class="line_x">
            <span class="l">订单号：<a href="?act=order_details&orderid={{v.Order_ID}}">{{v.Order_ID}}</a></span>
            <span class="state r">已付款</span>
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
        //加载更多
        $("#pagemore a").click(function(){
            var totalPage = <?php echo $totalPage;?>;
            var pageno = $(this).attr('data-next-pageno');
            var url = 'admin.php?act=order_confirm&p=' + pageno;

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
    });
</script>