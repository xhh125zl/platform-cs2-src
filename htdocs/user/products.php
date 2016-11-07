<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/product.class.php';
require_once CMS_ROOT . '/include/api/ImplOrder.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $do = isset($_POST['do']) ? $_POST['do'] : '';
    $pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
    $is_Tj =  isset($_POST['is_Tj']) ? $_POST['is_Tj'] : 1;    //获取商品是否推荐到平台

    $res = product::getProductArr(['Biz_Account' => $BizAccount, 'Products_ID' => $pid, 'is_Tj' => $is_Tj]);
    if (isset($res['errorCode']) && $res['errorCode'] != 0) {
        echo json_encode(['errorCode' => 1, 'msg' => '获取商品详情错误']);
        die;
    }

    if ($do == 'shop' && $pid > 0) {    //商品上下架
        if ($res['data']['Products_SoldOut'] == 0) {      //自营商品下架
            $res['data']['Products_SoldOut'] = 1;
        } else if ($res['data']['Products_SoldOut'] == 1) {   //自营商品上架
            $res['data']['Products_SoldOut'] = 0;
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '非法操作']);
            die;
        }
        //图片路径处理
        $res['data']['Products_JSON'] = stripcslashes($res['data']['Products_JSON']);
        $res['data']['Products_JSON'] = str_replace(rtrim(SHOP_URL, '/'), '', $res['data']['Products_JSON']);

        unset($res['data']['isSolding']);
        unset($res['data']['Category401']);
        $result = product::editProductTo401(['productdata' => $res['data']]);
        if ($res['data']['is_Tj'] == 1) {
            $res['data']['B2CProducts_Category'] = $res['data']['b2cCategory']['B2CProducts_Category'][2];
            unset($res['data']['b2cCategory']);
            $b2c_result = product::edit(['productdata' => $res['data']]);
        }
        $tag_b2c = isset($b2c_result['errorCode']) ? $b2c_result['errorCode'] : 0;
        if ($result['errorCode'] == 0 && $tag_b2c == 0) {
            echo json_encode(['errorCode' => 0, 'msg' => '操作成功']);
            die;
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '操作失败']);
            die;
        }
        
    } else if ($do == 'del' && $pid > 0) {
        //判断是否有未完成订单
        $resOrders = ImplOrder::getOrders(['Biz_Account' => $BizAccount, 'Order_Status' => 'not in (4,5)']);
        $orderList = [];
        if (isset($resOrders['errorCode']) && $resOrders['errorCode'] == 0) {
            $orderList = $resOrders['data'];
        }
        if (count($orderList) > 0) {
            $proArr = [];
            foreach ($orderList as $k => $v) {
                foreach (json_decode($v['Order_CartList'], true) as $key => $val) {
                    $proArr[] = $key;
                    $proArr[] = $val[0]['Products_FromId'];
                }
            }
            $proArr = array_unique($proArr);
            if (in_array((int)$pid, $proArr)) {
                echo json_encode(['errorCode' => 1, 'msg' => '当前有客户订单中包含此商品,并且订单状态<br>不是已完成,不允许删除!']);
                die;
            }
        }
        //没有未完成订单，做删除处理
        $res_401 = product::delete(['Biz_Account' => $BizAccount, 'Products_ID' => $pid]);
        if ($res['data']['Products_FromId'] == 0 && $res['data']['is_Tj'] == 1) {       //自营商品,且推荐到商城平台的
            $res_b2c = product::b2cProductDelete(['Products_ID' => $pid]);
        }

        $flog_401 = isset($res_401['errorCode']) ? ($res_401['errorCode'] == 0 ? 1 : 0) : 0 ;
        $flog_b2c = isset($res_b2c['errorCode']) ? ($res_b2c['errorCode'] == 0 ? 1 : 0) : 1 ;

        if ($flog_401 && $flog_b2c) {
            echo json_encode(['errorCode' => 0, 'msg' => '删除成功']);
            die;
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '删除失败']);
            die;
        }

    } else {
        echo json_encode(['errorCode' => 1, 'msg' => '非法操作']);
        die;
    }
}


$state = isset($_GET['state']) ? (int)$_GET['state'] : 0;   //查看商品是否为上下架    0：上架  1：下架  暂时没用
$state = $state != 1 ? 0 : 1;

$sortby = $sortMethod = '';

$normalSortby = false;
$saleSortby = false;
$priceSortby = false;
$commissionSortby = false;

//销售
/*if (isset($_GET['sortby']) && $_GET['sortby'] == 'sale') {
	$sortby = 'sale';
    $sortbyField = 'Products_Sales';
	$saleSortby = true;

} else if (isset($_GET['sortby']) && $_GET['sortby'] == 'price') {
	//价格
	$sortby = 'price';
    $sortbyField = "Products_PriceX";
	$priceSortby = true;

} else if (isset($_GET['sortby']) && $_GET['sortby'] == 'commission') {
	//价格
//    $sortbyField = "Products_PriceX";
	$sortby = 'commission';	
	$commissionSortby = true;

} else {
	$normalSortby = true;
    $sortbyField = "Products_FromID";
	$sortby = 'normal';

}

$sortMethod = isset($_GET['sortMethod']) ? $_GET['sortMethod'] : 'asc';
if ($sortMethod == 'asc') {
    $sortMethodIcon = 'desc';
} else {
    $sortMethodIcon = 'asc';
}*/

$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;

$pageSize = 5;     //每页显示
$map = [];

$data = [
    'pageSize' => $pageSize,
    'Biz_Account' => $BizAccount,
];

$result = product::getProducts($p, $data);

if ($result['errorCode'] == 1) {
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
/*$page->setvar([
        'sortby' => $sortby,
        'sortMethod' => $sortMethod,
        'search' => 1,
        //'state' => $state,
    ]
);*/
$page->set($pageSize, $total, $p);

$infolist = [];
if (count($products) > 0) {
    foreach ($products as $k => $row) {
        /*if ($state == 0 && $row['Products_SoldOut'] == 1) {     //显示上架商品  默认显示
            unset($products[$k]);
            continue;
        } else if ($state == 1 && $row['Products_SoldOut'] == 0) {     //显示下架商品
            unset($products[$k]);
            continue;
        }*/
        $img = json_decode($row['Products_JSON'], true);
        $row['thumb'] = $img['ImgPath'][0];
        unset($row['Products_JSON']);
        $row['shop_url'] = rtrim(SHOP_URL, '/') . '/api/'.$row['Users_ID'].'/shop/products/'.$row['Products_ID'].'/';
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
<title>商品管理</title>
</head>
<link href="../static/user/css/product.css?t=<?php echo time(); ?>" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<script type='text/javascript' src='../static/js/plugin/layer_mobile/layer.js'></script>
<style>
.row{line-height:20px;}
a.preview p, a.delete p, a.edit p, a.shop p, a.top p{display:inline;}
</style>
<body>
<div class="w">
<!-- topnav -->
	<div style="text-align:center; line-height:30px; background:#fff; padding:5px 0">
    	<span class="l"><a href="?act=store"><img src="../static/user/images/gdx.png" width="30" height="30" style=""></a></span>商品管理
    </div>
<!-- //topnav -->    
	<!--header -->
    <!--
<div class="header">
    	<ul>
        	<li><a href="?act=products">
            	<img src="../static/user/images/pro_1.png" width="40" height="40">
                <p>出售中</p>
            </a></li>
            <li><a href="?act=products&state=1">
            	<img src="../static/user/images/pro_2.png" width="40" height="40">
                <p>已下架</p>
            </a></li>
            <li><a href="?act=my_cate">
            	<img src="../static/user/images/pro_3.png" width="40" height="40">
                <p>我的分类</p>
            </a></li>
        </ul>
    </div>    
    <div class="clear"></div>
-->      
    <!--//header -->

<!-- filter -->
<!--
    <div class="sorting">
    	<ul>
        	<li><a id="normal" href="javascript:;" data-sortby="normal" data-sortMethod="<?php 
			if ($normalSortby) {
				echo $sortMethodIcon;	
			} else {
				echo 'asc';
			} ;?>">综合&nbsp;<i class="fa  fa-caret-up fa-x" aria-hidden="true"></i></a></li>
            <li><a id="price" href="javascript:;" data-sortby="price" data-sortMethod="<?php 
			if ($priceSortby) {
				echo $sortMethodIcon;	
			} else {
				echo 'asc';
			} ;?>" >价格&nbsp;<i class="fa  fa-caret-up fa-x" aria-hidden="true"></i></a></li>
            <li><a id="sale" href="javascript:;" data-sortby="sale" data-sortMethod="<?php 
			if ($saleSortby) {
				echo $sortMethodIcon;	
			} else {
				echo 'desc';
			} ;?>"" >销量&nbsp;<i class="fa  fa-caret-up fa-x" aria-hidden="true"></i></a></li>
            <li><a id="commission" href="javascript:;" data-sortby="commission" data-sortMethod="<?php 
			if ($commissionSortby) {
				echo $sortMethodIcon;	
			} else {
				echo 'asc';
			} ;?>"" >利润&nbsp;<i class="fa  fa-caret-up fa-x" aria-hidden="true"></i></a></li>
        </ul> 
    </div>
   
-->
 <div class="clear"></div>    
<!-- <input type="hidden" name="sortby" id="sortby" value='normal'>
<input type="hidden" name="sortMethod" id="sortMethod" value='desc'> -->
<script type="text/javascript">
/*$(function(){
	var currentID = '#<?php echo $sortby;?>';
    var sortMethod = 'fa fa-x <?php if ($sortMethod == 'asc') echo 'fa-caret-down'; else echo 'fa-caret-up';?>';
	$(currentID).addClass('pro_asc');
    $(currentID).parent().find('i').attr('class', sortMethod);

	$(".sorting ul li a").click(function(){
		$(this).parent().parent().find("a").removeClass("pro_asc");
		$(this).addClass('pro_asc');
		$("#sortby").val($(this).attr('data-sortby'));
		$("#sortMethod").val($(this).attr("data-sortMethod"));

		filter();
	})
	function filter() {
        var state = "<?php echo $state;?>";
		var sortby = $("#sortby").val();
		var sortMethod =$("#sortMethod").val();

		url = '?act=products&state=' + state + '&sortby=' + sortby + "&sortMethod=" + sortMethod;
		location.href = url;
	}
})*/

</script>
<!--// -->
<!-- product list -->
<div class="product">
    <ul class="productList">
    	<?php
        if (count($infolist) > 0)        	 {
        	foreach ($infolist as $key => $product) {
        ?>
        <!-- li -->
		<li>
        	<div style=" border-bottom:1px #eee solid; overflow:hidden;min-height:110px;">
            	<a><span class="imgs l"><img src="<?php echo getImageUrl($product['thumb'], 0);?>" width="90" height="90"></span>
                <span class="main l">
                    <p><?php echo $product['Products_Name'];?></p>
                    <span class="l" style="font-size:16px; line-height:25px; color:#333">￥<?php echo $product['Products_PriceX'];?></span>
                    <!--<span class="r" style="line-height:25px;">佣金<sss style="color:#ff5000; font-size:16px;">￥<?php /*echo ($product['Products_PriceY'] - $product['Products_PriceX'])*/?></sss></span>-->
                    <div class="clear"></div>
                    <span class="l">已售<?php echo $product['Products_Sales'];?></span>
                    <span class="r">库存:<?php echo $product['Products_Count'];?></span>
                    
                </span></a>
            </div>
        	<div class="clear"></div>
            <div class="row">
            	<ul>
                	<li><a class="preview" href="<?php echo $product['shop_url']; ?>">
                        <i class="fa  fa-eye fa-x" aria-hidden="true" style="font-size:16px;"></i>
                        <p>预览</p>
                    </a></li>
                    <?php if ($product['Products_FromId'] == 0) { //自营商品 ?>
                        <li>
                            <a class="edit" href="?act=product_edit&product_id=<?php echo $product['Products_ID'];?>">
                            <i class="fa  fa-pencil fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>编辑</p></a></li>
                        <li><a class="delete" href="javascript:;" product-id="<?php echo $product['Products_ID'];?>" fromId="<?php echo $product['Products_FromId'];?>">
                            <i class="fa  fa-trash-o fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>删除</p></a></li>
                        <?php if ($product['Products_SoldOut'] == 0) { ?>
                        <li><a class="shop" href="javascript:;" product-id="<?php echo $product['Products_ID'];?>" is-Tj="<?php echo $product['is_Tj'];?>" SlodOut="<?php echo $product['Products_SoldOut'];?>">
                            <i class="fa  fa-arrow-down fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>下架</p>
                        </a></li>
                        <?php } else { ?>
                        <li><a class="shop" href="javascript:;" product-id="<?php echo $product['Products_ID'];?>" is-Tj="<?php echo $product['is_Tj'];?>" SlodOut="<?php echo $product['Products_SoldOut'];?>">
                            <i class="fa  fa-arrow-up fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>上架</p>
                        </a></li>
                        <?php } ?>
                    <?php } else { //非自营商品 ?>
                        <li></li>
                        <li></li>
                        <li><a class="delete" href="javascript:;" product-id="<?php echo $product['Products_ID'];?>" fromId="<?php echo $product['Products_FromId'];?>">
                            <i class="fa  fa-trash-o fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>删除</p></a></li>
                    <?php } ?>
                    <li></li>

                </ul>                      
            </div>
            <div class="clear"></div>
        </li>            
        <!--//li -->
        <?php
        	}
        } else {echo '<li style="text-align:center;color:#666;">暂无商品</li>'; }
        ?>
    </ul>
</div>
<!--//product list -->    
<div class="clear"></div>

<!-- 点击加载更多 -->
<script id="product-row" type="text/html">
{{each data as product i}}
	<li>
    	<div style="border-bottom:1px #eee solid; overflow:hidden;min-height:110px;">
        	<a><span class="imgs l"><img src="{{product.thumb}}" width="90" height="90"></span>
            <span class="main l">
                <p>{{product.Products_Name}}</p>
                <span class="l" style="font-size:16px; line-height:25px; color:#333">￥{{product.Products_PriceX}}</span>
                <!--<span class="r" style="line-height:25px;">佣金<sss style="color:#ff5000; font-size:16px;">￥{{product.Products_PriceY - product.Products_PriceX}}</sss></span>-->
                <div class="clear"></div>
                <span class="l">已售{{product.Products_Sales}}</span>
                <span class="r">库存:{{product.Products_Count}}</span>
            </span></a>
        </div>
    	<div class="clear"></div>
        <div class="row">
        	<ul>
            	<li><a class="preview" href="{{product.shop_url}}">
                    <i class="fa  fa-eye fa-x" aria-hidden="true" style="font-size:16px;"></i>
                    <p>预览</p>
                </a></li>
                {{if product.Products_FromId == 0}}
                    <li>
                        <a class="edit" href="?act=product_edit&product_id={{product.Products_ID}}">
                        <i class="fa  fa-pencil fa-x" aria-hidden="true" style="font-size:16px;"></i>
                        <p>编辑</p></a></li>
                    <li><a class="delete" href="javascript:;" product-id="{{product.Products_ID}}" formId="{{product.Products_FromId}}">
                        <i class="fa  fa-trash-o fa-x" aria-hidden="true" style="font-size:16px;"></i>
                        <p>删除</p></a></li>
                    {{if product.Products_SoldOut == 0}}
                    <li><a class="shop" href="javascript:;" product-id="{{product.Products_ID}}" is-Tj="{{product.is_Tj}}" soldout="{{product.Products_SoldOut}}">
                        <i class="fa  fa-arrow-down fa-x" aria-hidden="true" style="font-size:16px;"></i>
                        <p>下架</p>
                    </a></li>
                    {{else}}
                    <li><a class="shop" href="javascript:;" product-id="{{product.Products_ID}}" is-Tj="{{product.is_Tj}}" soldout="{{product.Products_SoldOut}}">
                        <i class="fa  fa-arrow-up fa-x" aria-hidden="true" style="font-size:16px;"></i>
                        <p>上架</p>
                    </a></li>
                    {{/if}}
                {{else}}
                    <li></li>
                    <li></li>
                    <li><a class="delete" href="javascript:;" product-id="{{product.Products_ID}}" formId="{{product.Products_FromId}}">
                        <i class="fa  fa-trash-o fa-x" aria-hidden="true" style="font-size:16px;"></i>
                        <p>删除</p></a></li>
                {{/if}}
                <li></li>
            </ul>                    
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
<script type="text/javascript">
$(function(){
	//加载更多
    var last_pageno = 1;
	$("#pagemore a").click(function(){
        var totalPage = <?php echo $totalPage;?>;
        var state = "<?php echo $state;?>"
		//var sortby = '<?php echo $sortby?>';
		//var sortMethod = '<?php echo $sortMethod?>';
		var pageno = $(this).attr('data-next-pageno');

        //防止一页多次加载
        if (pageno == last_pageno) {
            return false;
        } else {
            last_pageno = pageno;
        }
        
        //var url = 'admin.php?act=products&state=' + state + '&sortby=' + sortby + "&sortMethod=" + sortMethod + '&p=' + pageno;
		var url = 'admin.php?act=products&state=' + state + '&p=' + pageno;

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

    var url = '?act=products&ajax=1';
	//下架 上架
	$(".productList").on('click', '.shop', function(){
        var me = $(this);
        var pid = me.attr("product-id");
        var is_Tj = me.attr("is-Tj");
        var soldout = me.attr("soldout");     //商品上下架状态
        
        layer.open({type: 2, shadeClose: false});
        $.ajax({
            type: 'post',
            url: url,
            data: 'do=shop&is_Tj='+is_Tj+'&pid='+pid,
            success: function(json){
                layer.closeAll();
                if (json.errorCode == 0) {
                    if (soldout == 0) {
                        me.find('i').removeClass('fa-arrow-down').addClass('fa-arrow-up');
                        me.attr('soldout', '1');
                        me.find('p').html('上架');
                        layer.open({
                            content:'下架成功',
                            time: 1
                        });
                    } else {
                        me.find('i').removeClass('fa-arrow-up').addClass('fa-arrow-down');
                        me.attr('soldout', '0');
                        me.find('p').html('下架');
                        layer.open({
                            content:'上架成功',
                            time: 1
                        });  
                    }
                } else {
                    if (soldout == 0) {
                        layer.open({
                            content: json.msg,
                            shadeClose: false,
                            btn: '确定'
                        });
                    } else {
                        layer.open({
                            content: json.msg,
                            shadeClose: false,
                            btn: '确定'
                        });
                    }
                }
            },
            dataType: 'json'
        });
	});

	//删除
	$(".productList").on('click', '.delete', function(){
        var pid = $(this).attr("product-id");
        var fromId = $(this).attr("fromId");
        var tr = $(this).parent().parent().parent().parent();

        if (fromId == 0) {
            var title = '确定删除此商品吗？';
        } else {
            var title = '此商品为非自营商品<br>确定从自己商品库中删除吗？';
        }
        layer.open({
            content: title,
            btn: ['确定', '取消'],
            yes: function(){
                layer.closeAll();
                layer.open({type: 2, shadeClose: false});
                $.post(url,{do:'del', pid:pid}, function(json){
                    layer.closeAll();
                    if (json.errorCode == '0')  {
                        layer.open({
                            content: '删除成功',
                            time: 1,
                            end: function() {
                              tr.remove();
                            }
                        });
                    } else {
                        layer.open({
                            content: json.msg,
                            shadeClose: false,
                            btn: '确定'
                        });
                    }
                },'json');
            }
        });
	});

})	

</script>
<!--//点击加载更多 -->    
    <div class="clear"></div>
    <div class="kb"></div>    
<!-- footer nav -->
<div class="bottom_x">
    <div class="foot_nav">
        <ul>
            <a href="?act=search"><li><i class="fa  fa-plus-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;产品库</li></a>
            <a href="?act=category"><li><i class="fa  fa-plus-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;商品分类</li></a>
            <a href="?act=store"><li><i class="fa  fa-check-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;管理中心</li></a>
        </ul>
    </div>
</div>
<!--//footer nav -->    
       
</div>
</body>
</html>


