<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/product.class.php';
require_once CMS_ROOT . '/include/api/b2cshopconfig.class.php';
require_once CMS_ROOT . '/include/api/product_category.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';
$fid = $sid = 0;

if (isset($_GET['fid'])) {
    $fid = intval($_GET['fid']);
}

if (isset($_GET['fid']) && isset($_GET['sid'])) {
    $sid = intval($_GET['sid']);
}

//判断当前页面是根据什么排序的,如果有排序的话,把对应的CSS样式加上
if (isset($_GET['sortby']) && in_array($_GET['sortby'], ['zonghe', 'price', 'sale', 'commission'])) {
    $sortby = $_GET['sortby'];
}else{
    $sortby = 'zonghe';
}
if (isset($_GET['sortMethod']) && in_array($_GET['sortMethod'], ['desc', 'asc'])) {
    $sortMethod = $_GET['sortMethod'];
}else{
    $sortMethod = 'desc';
}

//组装查询条件数组,传递到API进行查询返回数据
$condition = [];

if ($fid > 0) {
    $condition['fid'] = $fid;
}
if (isset($_GET['fid']) && isset($_GET['sid']) && $_GET['sid'] > 0) {
    $condition['sid'] = (int)$_GET['sid'];
}

if (isset($_GET['sortby']) && in_array($_GET['sortby'], ['zonghe', 'sale', 'commission', 'price'])) {
    $condition['sortby'] = $_GET['sortby'];
} else {
    unset($_GET['sortby']);
}

if (isset($_GET['sortMethod']) && in_array($_GET['sortMethod'], ['asc', 'desc'])) {
    $condition['sortMethod'] = $_GET['sortMethod'];
} else {
    unset($_GET['sortMethod']);
}

if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $condition['minprice'] = $_GET['min_price'];
}

if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $condition['maxprice'] = $_GET['max_price'];
}

if (isset($_GET['keyword']) && $_GET['keyword']) {
    $condition['keyword'] = trim($_GET['keyword']);
}


//排序方式过滤开始=================================================================
//综合
$zongheSortby = false;
if (isset($_GET['sortby']) && $_GET['sortby'] == 'zonghe' || !isset($_GET['sortby'])) {
    $zongheSortby = true;
    $sortMethod = isset($_GET['sortMethod']) ? $_GET['sortMethod'] : 'asc';
    if ($sortMethod == 'asc') {
        $sortMethod = 'desc';
    } else {
        $sortMethod = 'asc';
    }
}
//销售
$saleSortby = false;
if (isset($_GET['sortby']) && $_GET['sortby'] == 'sale') {
    $saleSortby = true;
    $sortMethod = isset($_GET['sortMethod']) ? $_GET['sortMethod'] : 'asc';
    if ($sortMethod == 'asc') {
        $sortMethod = 'desc';
    } else {
        $sortMethod = 'asc';
    }
}

//价格
$priceSortby = false;
if (isset($_GET['sortby']) && $_GET['sortby'] == 'price') {
    $priceSortby = true;
    $sortMethod = isset($_GET['sortMethod']) ? $_GET['sortMethod'] : 'asc';
    if ($sortMethod == 'asc') {
        $sortMethod = 'desc';
    } else {
        $sortMethod = 'asc';
    }
}

//佣金
$commissionSortby = false;
if (isset($_GET['sortby']) && $_GET['sortby'] == 'commission') {
    $commissionSortby = true;
    $sortMethod = isset($_GET['sortMethod']) ? $_GET['sortMethod'] : 'asc';
    if ($sortMethod == 'asc') {
        $sortMethod = 'desc';
    } else {
        $sortMethod = 'asc';
    }
}

//排序方式过滤结束=========================================================================================

//返回已分销的所有商品数组
$postdata = ['Biz_Account' => $BizAccount];
$isDistributeArr = product::getIsDistributeArr($postdata)['productData'];
if (count($isDistributeArr) > 0) {
    foreach($isDistributeArr as $k => $v) {
        $resArr[$k] = $v['Products_FromId'];
    }
}else{
    $resArr = [];
}
if (isset($_GET['isDistribute']) && $_GET['isDistribute'] == 1) {
    $condition['isDistribute'] = trim($_GET['isDistribute']);
}

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 5;

$data = ['pageSize' => $pageSize,'Users_Account' => $BizAccount];
$result = product::search($p ,$condition, $data);

if (isset($result['errorCode']) && $result['errorCode'] != 0) {
    $total = 0;
    $totalPage = 1;
    $products = [];
} else {
    $total = $result['totalCount'];
    $totalPage = ceil($result['totalCount'] / $pageSize);
    $products = $result['productData'];
}

//分页
$page = new page();
$page->setvar([
        'sortby' => $sortby,
        'sortMethod' => $sortMethod,
        'search' => 1,
        //'state' => $state,
    ]
);
$page->set($pageSize, $total, $p);


$infolist = [];
if (count($products) > 0) {
    foreach ($products as $row) {
        $img = json_decode($row['Products_JSON'], true);
        $row['thumb'] = $img['ImgPath'][0];
        unset($row['Products_JSON']);
        //判断是否为已上架
        if (in_array($row['Products_FromId'], $resArr)) {
            $row['is_sj'] = '1';
        } else {
            $row['is_sj'] = '0';
        }
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
    <title>商品库</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/page_media.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<!-- <script type="text/javascript" src="../static/js/jquery-1.11.1.min.js"></script> -->
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<body>
<?php
//检查用户是否已经交过费用
$users = b2cshopconfig::getConfig(array('Users_Account' => $BizAccount));
if ($users['errorCode'] == 0) {
    $bizData = $users['configData'];
    if ($bizData['expiresTime'] != 0 && $bizData['expiresTime'] < time()) {
        if ($bizData['need_charg'] == 1) {
            echo '<script>layer.open({content: "此项功能已到期,必须先交费才可以使用", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
            exit();
        }
    }
    $rsBiz = b2cshopconfig::getVerifyconfig(['Biz_Account' => $BizAccount]);
    if (empty($rsBiz['bizData'])) {
        echo '<script>layer.open({content: "商家不存在", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
        exit;
    }
} else {
    echo '<script>layer.open({content: "服务器网络异常,数据通信失败", shadeClose: false, btn: "确定", yes: function(){history.back();}});</script>';
    exit;
}

//获取商家自己的分类
$res = product_category::getDev401firstCate($BizAccount);
$Category = [];
if (isset($res['errorCode']) && $res['errorCode'] == 0) {
    foreach ($res['cateData'] as $k => $v) {
        $result = product_category::getDev401SecondCate(['Biz_Account' => $BizAccount, 'firstCateID' => $v['Category_ID']]);
        if (isset($result['errorCode']) && $result['errorCode'] == 0 && isset($result['cateData']) && count($result['cateData']) > 0) {
            $res['cateData'][$k]['child'] = $result['cateData'];
        } else {
            unset($res['cateData'][$k]);
        }
    }
    $Category = $res['cateData'];
} else {
    echo '<script>layer.open({content: "你还没有设置分类", shadeClose: false, btn: ["去添加","返回"], yes: function(){window.location.href="?act=my_cate";}, no: function(){history.back();}});</script>';
    exit;
}

?>
<div class="w">
    <div class="bj_x">
        <div class="box">
            <span class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true" id="goback"></i></span>
            <input type="text" class="sousuo_x" placeholder="请输入您要搜索的内容">
            <a><span class="ss1_x" id="btn_so">搜索</span></a>
        </div>
    </div>
    <div class="sorting">
        <ul>
            <li><a class="pro_asc" id="zonghe" data-sortMethod="<?=$zongheSortby ? $sortMethod : 'asc'?>">综合&nbsp;<i class="fa fa-caret-down fa-x" aria-hidden="true"></i></a></li>
            <li><a id="price" data-sortMethod="<?=$priceSortby ? $sortMethod : 'asc'?>">价格&nbsp;<i class="fa fa-caret-up fa-x" aria-hidden="true"></i></a></li>
            <li><a id="sale" data-sortMethod="<?=$saleSortby ? $sortMethod : 'asc'?>">销量&nbsp;<i class="fa fa-caret-up fa-x" aria-hidden="true"></i></a></li>
            <li><a id="commission" data-sortMethod="<?=$commissionSortby ? $sortMethod : 'asc'?>">利润&nbsp;<i class="fa fa-caret-up fa-x" aria-hidden="true"></i></a></li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="product">

        <input type="hidden" name="fid" id="fid" value=<?php echo isset($_GET['fid']) ? (int)$_GET['fid'] : 0;?>>
        <input type="hidden" name="sid" id="sid" value=<?php echo isset($_GET['sid']) ? (int)$_GET['sid'] : 0;?>>
        <input type="hidden" name="sortby" id="sortby" value="<?php echo isset($_GET['sortby']) ? $_GET['sortby'] : '' ?>">
        <input type="hidden" name="sortMethod" id="sortMethod" value="<?php echo isset($_GET['sortMethod']) ? $_GET['sortMethod'] : '' ?>">
        <input type="hidden" name="keyword" id="keyword" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
        <ul class="productList">
            <?php
            if (count($infolist) > 0) {
                foreach ($infolist as $product) {
                    ?>
                    <li style="margin-top: 5px;height:100px;padding:8px 0;">
                        <div style="overflow:hidden;">
                            <a><span class="imgs l"><img src="<?php echo $product['thumb'];?>" width="90" height="90"></span>
                    <span class="main l">
                        <p style="max-height: 30px;"><?php echo $product['Products_Name'];?></p>
                        <span class="l" style="font-size:16px; line-height:25px; color:#333">￥<?=$product['Products_PriceX']?></span>
                        <span class="r" style="line-height:25px;">三级收益<span style="color:#ff5000; font-size:16px;">￥<?php echo $product['commission'];?></span></span>
                        <div class="clear"></div>
                        <span class="l"><?php echo $product['Products_DistPersonCount'];?>人在售<br>
                        库存<?php echo $product['Products_Count'];?></span>
                        <span class="r" id="pro<?=$product['Products_FromId']?>">
                            <?php if ($product['is_sj']) { ?>
                                <div class="up_yy">已上架</div>
                            <?php } else { ?><input type="button" value="一键上架" class="up_xx" data-FromID="<?=$product['Products_FromId']?>">
                            <?php } ?>
                        </span>
                        <div class="clear"></div>
                    </span></a>
                        </div>
                        <div class="clear"></div>
                    </li>
            <?php }
            } ?>
        </ul>
    </div>

    <!-- 隐藏的401分类列表 -->
    <div id="cate" style="display:none;">
        <div class="select_containers">
            请选择一级分类：
            <select name="firstCate" id="firstCate" style="font-size:15px; width:150px; height:30px; border:1px solid #ccc;">
                <option value="0">请选择一级分类</option>
                <?php
                    if (!empty($Category)) {
                        foreach ($Category as $k => $v) {
                            echo '<option value="' . $v['Category_ID'].'">' . $v['Category_Name'] . '</option>';
                        }
                    }
                ?>
            </select><br/>
            请选择二级分类：
            <select name="secondCate" id="secondCate" style="font-size:15px; width:150px; height:30px; border:1px solid #ccc;">
                <option value="0">请选择二级分类</option>
            </select>
            <?php
                if (!empty($Category)) {
                    foreach ($Category as $k => $v) {
                        //无子分类的不显示
                        if (isset($v['child']) && count($v['child']) > 0) {
                            echo '<div class="first_cate_'.$v['Category_ID'].'" style="display:none;">';
                            foreach ($v['child'] as $kk => $vv) {
                                echo '<option value="' . $vv['Category_ID'].'">' . $vv['Category_Name'] . '</option>';
                            }
                            echo '</div>';
                        }
                    }
                }
            ?>
        </div>
    </div>

</div>

<div class="clear"></div>
<!-- 点击加载更多 -->
<script id="product-row" type="text/html">
{{each data as product i}}
        <li style="margin-top: 5px;height:100px;padding:8px 0;">
            <div style="overflow:hidden;">
                <a><span class="imgs l"><img src="{{product.thumb}}" width="90" height="90"></span>
                <span class="main l">
                    <p style="max-height: 30px;">{{product.Products_Name}}</p>
                    <span class="l" style="font-size:16px; line-height:25px; color:#333">￥{{product.Products_PriceX}}</span>
                    <span class="r" style="line-height:25px;">三级收益<span style="color:#ff5000; font-size:16px;">￥{{product.commission}}</span></span>
                    <div class="clear"></div>
                    <span class="l">{{product.Products_DistPersonCount}}人在售<br>
                    库存{{product.Products_Count}}</span>
                    <span class="r" id="pro{{product.Products_FromId}}">
                        {{if (product.is_sj == 1)}}
                            <div class="up_yy">已上架</div>
                        {{else}}
                            <input type="button" value="一键上架" class="up_xx" data-FromID="{{product.Products_FromId}}">
                        {{/if}}
                    </span>
                    <div class="clear"></div>
                </span></a>
            </div>
            <div class="clear"></div>
        </li>
 {{/each}}
</script>
<style>
#pagemore{clear:both;text-align:center;  color:#666; padding-top: 5px; padding-bottom:5px;}
#pagemore a{ height:30px; line-height:30px; text-align:center;display:block; background-color:#ddd; border-radius: 2px;}
</style>
<div id="pagemore">
<?php
if ($return['page']['hasNextPage'] == 'true') {
    echo '<a href="javascript:;" data-next-pageno="2">正在加载中...</a>';    
} else {
    echo '已经没有了...';
}
?>
</div>

</body>
<script type="text/javascript">
    //生成跳转的链接
    function getfilter() {
        var fid = $("#fid").val();
        var sid = $("#sid").val();
        var sortby = $("#sortby").val();
        var sortMethod = $("#sortMethod").val();
        var keyword = $("#keyword").val();
        if (keyword.length > 0) {
            return '?act=search&fid=' + fid + "&sid=" + sid + '&sortby=' + sortby + "&sortMethod=" + sortMethod + "&keyword=" + keyword;
        } else {
            return '?act=search&fid=' + fid + "&sid=" + sid + '&sortby=' + sortby + "&sortMethod=" + sortMethod;
        }
    }
    //排序跳转
    $(".sorting ul li a").click(function(){
        $("#sortby").val($(this).attr("id"));
        $("#sortMethod").val($(this).attr("data-sortMethod"));
        var url = getfilter();
        location.href = url;
    });
    $(function(){
        //加载更多
        var last_pageno = 1;        
        $("#pagemore a").click(function(){
            var totalPage = <?php echo $totalPage;?>;
            var fid = <?php echo $fid;?>;
            var sid = <?php echo $sid;?>;
            var sortby = '<?php echo $sortby?>';
            var sortMethod = '<?php echo $sortMethod?>';
            var pageno = $(this).attr('data-next-pageno');
            var url = 'admin.php?act=search&fid='+fid+'&sid='+sid+'&sortby=' + sortby + "&sortMethod=" + sortMethod + '&p=' + pageno;

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
                    var html = template('product-row', json);
                    $("ul.productList").append(html);
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

        //点击一键分销按钮进行的操作
        $(".productList").on('click', '.up_xx', function(){
            var me = $(this);
            layer.open({
                type:1,
                content: $('#cate').html(),
                title:[
                    '<span style="float:left">请选择要添加到的分类</span>',
                    'background-color:#f0f0f0;font-weight:bold;'
                ],
                style: 'width:100%;position:fixed;bottom:0;left:0;border-radius:8px;',
                btn: ['上架分销','取消'],
                success: function(){
                    //分类联动菜单第二级
                    $(document).on('change', '#firstCate', function(){
                        $(this).nextAll('#secondCate').html('');
                        var first_cate_id = $(this).val();
                        var second_cate = $(this).nextAll('.first_cate_'+first_cate_id).html();
                        $(this).nextAll('#secondCate').html(second_cate);
                    });
                },
                yes:function(index){
                    var firstCate = $('.layui-m-layercont #firstCate').val();
                    var secondCate = $('.layui-m-layercont #secondCate').val();
                    if (firstCate > 0 && secondCate > 0) {
                        layer.open({type:2, shadeClose:false});
                        $.ajax({
                            url:"/user/lib/products.php",
                            type:"get",
                            timeout:6000,
                            data:{"action":"addProducts", "Products_FromID":me.attr("data-FromID"), "firstCate":firstCate, "secondCate":secondCate},
                            dataType:"json",
                            success:function(data) {
                                layer.closeAll();
                                if (data.errorCode == 0) {
                                    layer.open({
                                        type:0,
                                        content:data.msg,
                                        time:1,
                                        end:function(){
                                            $("#pro" + me.attr("data-FromID")).html("<div class=\"up_yy\">已上架</div>");
                                        }
                                    });
                                } else {
                                    layer.open({
                                        type:0,
                                        content:data.msg,
                                        time:2
                                    });
                                }
                            }
                        });
                    }else{
                        layer.open({
                            content: '没有完成分类选择，请重试',
                            btn: '确定'
                        });
                    }
                }
            });
        });

        //加载页面完毕首先添加默认样式
        var current = $("#<?=$sortby?>");
        current.addClass("pro_asc").parent('li').siblings().find("a").removeClass("pro_asc");
        if ("<?=$sortMethod?>" == 'desc') {
            current.find("i").removeClass();
            current.find("i").addClass("fa fa-caret-up fa-x");
        }else if ("<?=$sortMethod?>" == 'asc') {
            current.find("i").removeClass();
            current.find("i").addClass("fa fa-caret-down fa-x");
        }

        $("#btn_so").click(function(){
            getfilter_so();
        });
        //关键字搜索
        function getfilter_so() {
            var kw = $(".sousuo_x").val();
            var url = getfilter();
            if (kw != '') {
                url += '&keyword=' + kw;
            }
            location.href = url;

        }

        //监听回车键,用于搜索
        $('html').bind('keydown',function(e){
            if(e.keyCode==13){
                getfilter_so();
            }
        });
        //返回按钮
        $("#goback").click(function() {
            location.href = "/user/admin.php?act=products";
        });


    });
</script>
</html>