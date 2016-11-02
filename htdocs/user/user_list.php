<?php
require_once "config.inc.php";
require_once CMS_ROOT . '/include/api/Myuser.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 10;

$transData = ['Biz_Account' => $BizAccount, 'pageSize' => $pageSize];
$result = Myuser::getMyUsers($transData, $p);

if (isset($result['errorCode']) && $result['errorCode'] != 0) {
    $total = 0;
    $totalPage = 1;
    $myUsers = [];
} else {
    $total = $result['totalCount'];
    $totalPage = ceil($result['totalCount'] / $pageSize);
    $myUsers = $result['data'];
}

//分页
$page = new page();
$page->set($pageSize, $total, $p);

$userlist = [];
if (count($myUsers) > 0) {
    foreach ($myUsers as $row) {
        $row['User_HeadImg'] = $row['User_HeadImg'] == '' ? '/static/api/images/user/face.jpg' : $row['User_HeadImg'];
        $row['User_NickName'] = $row['User_NickName'] == '' ? '暂无昵称' : $row['User_NickName'];
        $row['User_Mobile'] = $row['User_Mobile'] == '' ? '暂无手机号' : $row['User_Mobile'];
        $userlist[] = $row;
    }
}

$return = [
    'page' => [
        'pagesize' => count($userlist),
        'hasNextPage' => (count($userlist) >= $pageSize) ? 'true' : 'false',
        'total' => $total,
    ],
    'data' => $userlist,
];

if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    echo json_encode($return);
    exit;
}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>我的会员</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<body>
<div class="w">
    <div class="back_x">
        <a href="/user/admin.php?act=store" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>我的会员
    </div>
    <div class="user_ls">
        <ul class="userList">
            <?php
            if (count($userlist) > 0) {            
                foreach ($userlist as $k => $v) {
            ?>
            <li>
                <a href="?act=user_detail&User_ID=<?php echo $v['User_ID']; ?>">
                    <span class="l"><img src="<?php echo $v['User_HeadImg']; ?>"></span>
                    <span class="infor_x l"><?php echo $v['User_NickName']; ?><p>手机号：<?php echo $v['User_Mobile']; ?></p></span>
                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                    <div class="clear"></div>
                </a>
            </li>
            <?php
                }
            } else { echo '<li style="text-align:center;color:#666;">暂无会员</li>'; }
            ?>
        </ul>
    </div>
</div>
<div class="clear"></div>
<!-- 点击加载更多 -->
<script id="user-row" type="text/html">
{{each data as v i}}
    <li>
        <a href="?act=user_detail&User_ID={{v.User_ID}}">
            <span class="l"><img src="{{v.User_HeadImg}}"></span>
            <span class="infor_x l">{{v.User_NickName}}<p>手机号：{{v.User_Mobile}}</p></span>
            <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
            <div class="clear"></div>
        </a>
    </li>
{{/each}}
</script>
<style>
#pagemore{clear:both;text-align:center;  color:#666; padding-top: 5px; padding-bottom:5px;}
#pagemore a{ height:30px; line-height:30px; text-align:center;display:block; background-color:#ddd; border-radius: 2px;}
</style>
<div id="pagemore">
<?php
    if (isset($userlist) && count($userlist) > 0) {
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
        var last_pageno = 1;
        $("#pagemore a").click(function(){
            var totalPage = <?php echo $totalPage;?>;
            var pageno = $(this).attr('data-next-pageno');
            var url = 'admin.php?act=user_list&p=' + pageno;

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
                    var html = template('user-row', json);
                    $("ul.userList").append(html);
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