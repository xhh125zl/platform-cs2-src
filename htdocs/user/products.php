<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/product.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $do = isset($_POST['do']) ? $_POST['do'] : '';
    $pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;

    if ($do == 'shop' && $pid > 0) {
        $state =  isset($_POST['state']) ? $_POST['state'] : 'shopdown';
        $state = $state == 'shopdown' ? 0 : 1;

        $map = [
            'Users_ID' => $UsersID,
            'User_ID' => $UserID,
            'Products_FromID' => $pid,
        ];

        $affectedRows = ShopDistProduct::Multiwhere($map)->update(['state' => $state]);

        $return = [
            'affectRows' => $affectedRows,
        ];
        
    } else if ($do == 'delete' && $pid > 0) {

        $map = [
            'Users_ID' => $UsersID,
            'User_ID' => $UserID,
            'Products_FromID' => $pid,
        ];

        $affectedRows = ShopDistProduct::Multiwhere($map)->delete();

        if ($affectedRows > 0) {
            //减少分销人数
            $data = [
                'Products_FromId' => $pid,
                'DisPerson_Qty' => -1,
            ];
            product::updatediscount($data);
        }

        $return = [
            'affectRows' => $affectedRows,
        ];
    } else if ($do =='top' && $pid > 0) {
        $state =  isset($_POST['state']) ? (int)$_POST['state'] : '0';
        $state = $state == '0' ? 1 : 0;

        $map = [
            'Users_ID' => $UsersID,
            'User_ID' => $UserID,
            'Products_FromID' => $pid,
        ];

        $affectedRows = ShopDistProduct::Multiwhere($map)->update(['istop' => $state]);

        $return = [
            'affectRows' => $affectedRows,
        ];
    } else {
        $return = [
            'status' => 0,
            'msg' => '非法操作',
        ];
    }

    echo json_encode($return);
    exit();
}


$state = isset($_GET['state']) ? (int)$_GET['state'] : 0;
$state = $state != 1 ? 0 : 1;

$sortby = $sortMethod = '';

$normalSortby = false;
$saleSortby = false;
$priceSortby = false;
$commissionSortby = false;

//销售
if (isset($_GET['sortby']) && $_GET['sortby'] == 'sale') {
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
}

$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;

$pageSize = 8;
$map = [];

$field = 'shop.Products_ID, shop.Products_Name, shop.Products_PriceY, shop.Products_PriceX, shop.Products_JSON, shop.Products_JSON,shop.Products_Count, shop.Products_Sales, shop.commission_ratio, db.Products_FromID AS pid, db.state, db.istop';
if ($sortby == 'commission') {
    $field .=",convert((Products_PriceX - Products_PriceS) * platForm_Income_Reward / 100 * commission_ratio /100, decimal(10,2)) AS commission";
    $sortbyField = "commission";
}

$sql = "SELECT $field FROM `shop_dist_product_db` db LEFT JOIN `shop_products` shop ON db.Products_FromID = shop.Products_ID WHERE shop.Products_ID>0";
//商城配置，查看是否允许分销所有商品信息
$shop_config = shop_config($UsersID);
if ($shop_config['allowDistributeB2c'] == 1) {
    //不允许分销所有商品的信息，只能分销自己商城的产品
    $map[] = "shop.Users_ID='" . $UsersID . "'";
}

//下架的商品
if ($state == 1) {
    $map[] = "db.state = 1";
}

if (isset($_GET['total'])) {
    $total = (int)$_GET['total'];
    if ($total < 0) {
        $total = 0;
    }
} else {
    $sqlTotal = "SELECT COUNT(*) AS total FROM `shop_products` shop LEFT JOIN `shop_dist_product_db` db ON shop.Products_ID = db.Products_FromID WHERE shop.Products_FromID>0";

    if (count($map) > 0) {
        $sqlTotal .= " AND " . implode(' AND ', $map);    
    }
    

   $DB->query($sqlTotal);
   $ret = $DB->fetch_assoc();
  
   $total = $ret['total'];
}

//分页
$page = new page();
$page->setvar([
        'sortby' => $sortby,
        'sortMethod' => $sortMethod,
        'search' => 1,
        'state' => $state,
    ]
);
$page->set($pageSize, $total, $p);

if (count($map) > 0) {
    $sql .= " AND " . implode(' AND ', $map);    
}
if ($sortbyField == 'Products_FromID') $sortbyField = 'db.Products_FromID';
$sql .= " ORDER BY " . $sortbyField . " " . ($sortMethod == 'asc' ? 'desc' : 'asc');
$sql .= " LIMIT " . $page->limit();

$infolist = [];
$DB->query($sql);
while ($row = $DB->fetch_assoc()) {
    $img = json_decode($row['Products_JSON'], true);
    $row['thumb'] = $img['ImgPath'][0];
    unset($row['Products_JSON']);
    $infolist[] = $row;
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
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<script type='text/javascript' src='../static/js/plugin/layer_mobile/layer.js'></script>

<body>
<div class="w">
<!-- topnav -->
	<div style="text-align:center; line-height:30px; background:#fff; padding:5px 0">
    	<span class="l"><a><img src="../static/user/images/gdx.png" width="30" height="30" style=""></a></span>商品管理
    </div>
    <div class="clear"></div>
<!-- //topnav -->    
	<!--header -->
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
    <!--//header -->
    
    <div class="clear"></div>
<!-- filter -->
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
    <div class="clear"></div>
<input type="hidden" name="sortby" id="sortby" value='normal'>
<input type="hidden" name="sortMethod" id="sortMethod" value='desc'>
<script type="text/javascript">
$(function(){
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
})

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
            	<div style=" border-bottom:1px #eee solid; overflow:hidden;">
                	<a><span class="imgs l"><img src="<?php echo $product['thumb'];?>" width="90" height="90"></span>
                    <span class="main l">
                        <p><?php echo $product['Products_Name'];?> <?php echo $product['Products_ID'];?></p>
                        <span class="l" style="font-size:16px; line-height:25px; color:#333">￥<?php echo $product['Products_PriceX'];?></span>
                        <span class="r" style="line-height:25px;">佣金<sss style="color:#ff5000; font-size:16px;">￥<?php echo ($product['Products_PriceY'] - $product['Products_PriceX'])?><sss/></span>
                        <div class="clear"></div>
                        <span class="l">已售<?php echo $product['Products_Sales'];?></span>
                        <span class="r">
<?php
if (isset($product['Products_DistPersonCount'])) {
	echo $product['Products_DistPersonCount'] . '人在售';
}
?>
                       </span>
                        <div class="clear"></div>
                        <span class="l">库存:<?php echo $product['Products_Count'];?></span>
                        <span class="r" style="color:#ff5000"><i class="fa  fa-play-circle fa-x" aria-hidden="true"></i></span>
                    </span></a>
                </div>
            	<div class="clear"></div>
                <div class="">
                	<ul>
                    	<li><a class="preview">
                            <i class="fa  fa-eye fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>预览</p>
                        </a></li>
                        <li>
<?php
if ($product['state'] == 1) {
?>
<a class="shop shopdown" data-state="shopdown" data-product-id="<?php echo $product['Products_ID'];?>">
    <i class="fa  fa-download fa-x" aria-hidden="true" style="font-size:16px;"></i><p>下架</p>
</a>
<?php
} else {
?>
<a class="shop shopup" data-state="shopup" data-product-id="<?php echo $product['Products_ID'];?>">
    <i class="fa  fa-download fa-x" aria-hidden="true" style="font-size:16px;"></i><p>上架</p>
</a>
<?php
}
?>

                        </li>
                        <li><a class="delete" data-product-id="<?php echo $product['Products_ID'];?>">
                            <i class="fa  fa-trash-o fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>删除</p>
                        </a></li>

                        <li>


                        <a class="top"  data-state="<?php echo $product['istop'];?>" data-product-id="<?php echo $product['Products_ID'];?>">
                            <i class="fa  fa-upload fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>
<?php
if ($product['istop'] == 1) {
    echo '取消';
} else {
    echo '置顶';
}
?></p>
                        </a>




                        </li>

                    </ul>                      
                </div>
                <div class="clear"></div>
            </li>            
            <!--//li -->
<?php
	}
}
?>            
                     
        </ul>
    </div>
<!--//product list -->    
    <div class="clear"></div>

<!-- 点击加载更多 -->
<script id="product-row" type="text/html">
{{each data as product i}}
		<li>
            	<div style="border-bottom:1px #eee solid; overflow:hidden;">
                	<a><span class="imgs l"><img src="{{product.thumb}}" width="90" height="90"></span>
                    <span class="main l">
                        <p>{{product.Products_Name}}【{{product.Products_ID}}】</p>
                        <span class="l" style="font-size:16px; line-height:25px; color:#333">￥{{product.Products_PriceX}}</span>
                        <span class="r" style="line-height:25px;">佣金<sss style="color:#ff5000; font-size:16px;">￥{{product.Products_PriceY - product.Products_PriceX}}<sss/></span>
                        <div class="clear"></div>
                        <span class="l">已售{{product.Products_Sales}}</span>
                        <span class="r">500人在售</span>
                        <div class="clear"></div>
                        <span class="l">库存:{{product.Products_Count}}</span>
                        <span class="r" style="color:#ff5000"><i class="fa  fa-play-circle fa-x" aria-hidden="true"></i></span>
                    </span></a>
                </div>
            	<div class="clear"></div>
                <div class="">
                	<ul>
                    	<li><a class="preview">
                            <i class="fa  fa-eye fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>预览</p>
                        </a></li>
                        <li><a class="shop {{if (product.state == 1)}}shopdown{{else}}shopup{{/if}}"  data-state="{{if (product.state == 1)}}shopdown{{else}}shopup{{/if}}" data-product-id="{{product.Products_ID}}">
                            <i class="fa  fa-download fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>{{if (product.state == 1)}}下架{{else}}上架{{/if}}</p>
                        </a></li>
                        <li><a class="delete" data-product-id="{{product.Products_ID}}">
                            <i class="fa  fa-trash-o fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>删除</p>
                        </a></li>
                        <li><a class="top"  data-state="{{product.state}}" data-product-id="{{product.Products_ID}}">
                            <i class="fa  fa-upload fa-x" aria-hidden="true" style="font-size:16px;"></i>
                            <p>{{if product.state == 1}}取消{{else}}置顶{{/if}}</p>
                        </a></li>
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
    echo '<a href="javascript:;" data-next-pageno="2">点击加载更多...</a>';    
} else {
    echo '已经没有了...';
}
?>
</div>
<script type="text/javascript">
$(function(){
	//加载更多
	$("#pagemore a").click(function(){
        var state = "<?php echo $state;?>"
		var sortby = '<?php echo $sortby?>';
		var sortMethod = '<?php echo $sortMethod?>';
		var pageno = $(this).attr('data-next-pageno');
		var url = 'admin.php?act=products&state=' + state + '&sortby=' + sortby + "&sortMethod=" + sortMethod + '&p=' + pageno;

		$.post(url, {ajax: 1}, function(json){
            if (parseInt(json.page.pagesize) > 0) {
                var html = template('product-row', json);
                $("ul.productList").append(html);
            }
			if (json.page.hasNextPage == 'true') {
				$("#pagemore a").attr('data-next-pageno', parseInt(pageno) + 1);
			} else {
				$("#pagemore").html('已经没有了...');
			}
		},'json')
	})

    var url = '?act=products&ajax=1';
	//下架
	$(".productList").on('click', '.shop', function(){
        var pid = $(this).attr("data-product-id");
        var state = $(this).attr("data-state");
        var p = $(this);
        
        $.post(url,{do:'shop', state:state, pid:pid}, function(json){
            if (state == 'shopdown') {
                p.removeClass('shopdown').addClass('shopup');
                p.attr('data-state', 'shopup');
                p.find('p').html('上架');
                layer.open({
                    content:'下架成功',
                    time: 1
                });
            } else {
                p.removeClass('shopup').addClass('shopdown');
                p.attr('data-state', 'shopdown');
                p.find('p').html('下架');
                layer.open({
                    content:'上架成功',
                    time: 1
                });  
            }
            
        },'json')
	})

	//删除
	$(".productList").on('click', '.delete', function(){
        var pid = $(this).attr("data-product-id");
        var tr = $(this).parent().parent().parent().parent();
        $.post(url,{do:'delete', pid:pid}, function(json){
              if (json.affectRows == '0')  {
                  layer.open({
                      content: '删除失败',
                      time: 1
                  });
              } else {
                  layer.open({
                      content: '删除成功',
                      time: 1,
                      end: function() {
                          tr.remove();
                      }
                  });     
              }
        },'json')
	})

	//删除
	$(".productList").on('click', '.top', function(){
        var pid = $(this).attr("data-product-id");
        var state = $(this).attr("data-state");
        var p = $(this);

        $.post(url,{do:'top', state:state, pid:pid}, function(json){
            if (json.affectRows == '0')  {
                     layer.open({
                        content:'操作失败',
                        time: 1,
                        end: function() {
                            p.find('p').html('取消');        
                        }
                    });
            } else {
                if (state == '0') {
                    p.attr('data-state', '1');
                    
                     layer.open({
                        content:'置顶成功',
                        time: 1,
                        end: function() {
                            p.find('p').html('取消');        
                        }
                    });
                } else {
                    p.attr('data-state', '0');
                    
                    layer.open({
                        content:'取消成功',
                        time: 1,
                        end: function() {
                            p.find('p').html('置顶');
                        }
                    });
                }
            }
        },'json')
	})


})	
</script>
<!--//点击加载更多 -->    
    <div class="clear"></div>
    <div class="kb"></div>    
<!-- footer nav -->
<div class="bottom_x">
    <div class="foot_nav">
        <ul>
            <a href="?act=search&isDistribute=1"><li><i class="fa  fa-plus-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;产品库</li></a>
            <a href="?act=category"><li><i class="fa  fa-plus-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;商品分类</li></a>
            <li><i class="fa  fa-check-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;批量管理</li>
        </ul>
    </div>
</div>
<!--//footer nav -->    
       
</div>
</body>
</html>


