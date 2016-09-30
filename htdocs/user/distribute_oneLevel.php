<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/distribute.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 2;
$level = 1;  //分销商等级  1、2、3级

$transfer = ['Biz_Account' => $BizAccount, 'pageSize' => $pageSize, 'level' => $level];
$result = distribute::getDistribute($transfer, $p);

if (isset($result['errorCode']) && $result['errorCode'] != 0) {
    $total = 0;
    $totalPage = 1;
    $distributes = [];
} else {
    $total = $result['totalCount'];
    $totalPage = ceil($result['totalCount'] / $pageSize);
    $distributes = $result['data'];
}

//分页
$page = new page();
$page->set($pageSize, $total, $p);

$infolist = [];
if (count($distributes) > 0) {
    foreach ($distributes as $row) {
        $row['level'] = $level;
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
<title>一级分销商_分销管理</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<script type="text/javascript" src="../static/user/js/jquery.SuperSlide.2.1.1.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a href="?act=store" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>分销管理
    </div>
    <div class="slideTxtBox">
			<div class="hd distribute_x">
				<ul>
                    <a href="?act=distribute_oneLevel"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'distribute_oneLevel') { echo 'on'; } ?>">一级分销商</li></a>
                    <a href="?act=distribute_twoLevel"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'distribute_twoLevel') { echo 'on'; } ?>">二级分销商</li></a>
                    <a href="?act=distribute_threeLevel"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'distribute_threeLevel') { echo 'on'; } ?>">三级分销商</li></a>
                </ul>
			</div>
			<div class="bd">
				<ul>
					<li>
                    	<div class="user_ls">
                            <ul style="margin:0"  class="distributeList">
                            <?php
                                if (isset($distributes) && count($distributes) > 0) {
                                    foreach ($distributes as $k => $v) {
                            ?>
                                <li><a href="?act=distribute_detail&distributeid=<?php echo $v['Account_ID']; ?>&level=<?php echo $v['level']; ?>">
                                    <span class="l"><img src="<?php echo $v['Shop_Logo']; ?>"></span>
                                    <span class="infor_x l" style="text-align:left"><?php echo $v['Shop_Name']  ; ?><p>手机号：<?php echo $v['Account_Mobile']; ?></p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                            <?php }} ?>
                            </ul>
                        </div>
                    </li>
				</ul>
			</div>
		</div>
        <script type="text/javascript">
            //jQuery(".slideTxtBox").slide();
        </script>
</div>
<div class="clear"></div>
<!-- 点击加载更多 -->
<script id="order-row" type="text/html">
{{each data as v i}}
    <li><a href="?act=distribute_detail&distributeid={{v.Account_ID}}&level={{v.level}}">
        <span class="l"><img src="{{v.Shop_Logo}}"></span>
        <span class="infor_x l" style="text-align:left">{{v.Shop_Name}}<p>手机号：{{v.Account_Mobile}}</p></span>
        <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
        <div class="clear"></div>
    </a></li>
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
</body>
</html>
<script type="text/javascript">
    $(function(){
        //加载更多
        $("#pagemore a").click(function(){
            var totalPage = <?php echo $totalPage;?>;
            var pageno = $(this).attr('data-next-pageno');
            var url = 'admin.php?act=distribute_oneLevel&p=' + pageno;

            var nextPageno = parseInt(pageno);
            if (nextPageno > totalPage) {
                $("#pagemore").html('已经没有了...');
                return true;
            }

            $.post(url, {ajax: 1}, function(json){
                if (parseInt(json.page.pagesize) > 0) {
                    var html = template('order-row', json);
                    $("ul.distributeList").append(html);
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