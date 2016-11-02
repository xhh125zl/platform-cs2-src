<?php
if (!defined('USER_PATH')) exit();

require_once "lib/message.php";

$status = isset($_GET['status']) && in_array((int)$_GET['status'], [0,2,5,6]) ? (int)$_GET['status'] : 0;

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 10;

$transData = ['Biz_Account' => $BizAccount, 'pageSize' => $pageSize];
$result = message::getMsgOrder($transData, $p);

if (isset($result['errorCode']) && $result['errorCode'] != 0) {
    $total = 0;
    $totalPage = 1;
    $msgOrder = [];
} else {
    $total = $result['totalCount'];
    $totalPage = ceil($result['totalCount'] / $pageSize);
    $msgOrder = $result['data']['myOrder'];
}

//分页
$page = new page();
$page->set($pageSize, $total, $p);

$msglist = [];
if (count($msgOrder) > 0) {
    foreach ($msgOrder as $k => $row) {
        if ($row['Order_Status'] == $status) {
            $row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
            $msglist[] = $row;
        } 
    }
}

$return = [
    'page' => [
        'pagesize' => count($msgOrder),
        'hasNextPage' => (count($msgOrder) >= $pageSize) ? 'true' : 'false',
        'total' => $total,
    ],
    'data' => $msglist,
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
    <title>订单消息</title>
</head>
<link href="../static/user/css/product.css?t=<?php echo time(); ?>" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l" href='?act=store'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>消息中心
        <a class="r" href='?act=msg_setting'><i class="fa  fa-cog fa-2x" aria-hidden="true"></i></a>
    </div>
    <div class="slideTxtBox">
        <div class="hd msg_x">
            <ul>
                <a href="?act=msg_system"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_system') { echo 'on'; } ?>"><span class="msg_mark"><?php if ($unread_system_nums > 0) {echo '● ';} ?></span>系统</li></a>
                <a href="?act=msg_order"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_order') { echo 'on'; } ?>"><span class="msg_mark"><?php if ($unread_order_nums > 0) {echo '● ';} ?></span>订单</li></a>
                <a href="?act=msg_distribute"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_distribute') { echo 'on'; } ?>"><span class="msg_mark"><?php if ($unread_distribute_nums > 0) {echo '● ';} ?></span>分销</li></a>
                <a href="?act=msg_withdraw"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_withdraw') { echo 'on'; } ?>"><span class="msg_mark"><?php if ($unread_withdraw_nums > 0) {echo '● ';} ?></span>提现</li></a>
            </ul>
        </div>
        <div class="hd msg_x">
            <ul>
                <a href="?act=msg_order&status=0"><li class="<?php if($status == 0) { echo 'on'; } ?>">待确认</li></a>
                <a href="?act=msg_order&status=2"><li class="<?php if($status == 2) { echo 'on'; } ?>">待发货</li></a>
                <a href="?act=msg_order&status=5"><li class="<?php if($status == 5) { echo 'on'; } ?>">待退款</li></a>
                <a href="?act=msg_order&status=6"><li class="<?php if($status == 6) { echo 'on'; } ?>">待退货</li></a>
            </ul>
        </div>
        <div class="msg_list">
            <ul class="msgList">
                <?php
                if(!empty($msglist)){
                    foreach($msglist as $k => $v){
                ?>
                <li>
                    <a href='javascript:;' class="msgs" msg_id="<?php echo $v['id']; ?>" msg_status="<?php echo $v['msg_status']; ?>" orderid="<?php echo $v['Order_ID']; ?>">
                        <p><span class="msg_mark"><?php if ($v['msg_status'] == 0) {echo '● ';} ?></span><?php echo $v['msg_title']; ?></p>
                        <p style="margin-left:5px;"><?php echo $v['create_time']; ?></p>
                    </a>
                </li>
                <?php
                    }
                } else { echo '<li style="text-align:center;color:#666;">暂无消息</li>'; }
                ?>
            </ul>
        </div>
    </div>
</div>
<div class="clear"></div>
<!-- 点击加载更多 -->
<script id="msg-row" type="text/html">
{{each data as v i}}
    <li>
        <a href='javascript:;' class="msgs" msg_id="{{v.id}}" msg_status="{{v.msg_status}}" orderid="{{v.Order_ID}}">
            <p><span class="msg_mark">{{if v.msg_status == 0 }}● {{/if}}</span>{{v.msg_title}}</p>
            <p style="margin-left:5px;">{{v.create_time}}</p>
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
    if (isset($msglist) && count($msglist) > 0) {
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
            var url = 'admin.php?act=msg_order&p=' + pageno;

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
                    var html = template('msg-row', json);
                    $("ul.msgList").append(html);
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

        $('.msgList').on('click','.msgs',function(){
            var me = $(this);
            var msg_status = me.attr('msg_status');
            var url = "?act=order_details&orderid="+me.attr('orderid');
            if (msg_status == 1) {
                //信息状态为已读，不需更新操作
                me.find('span').html('');
                window.location.href = url;
            } else {
                $.ajax({
                    type: 'POST',
                    url: '?act=msg_order',
                    data: 'msg_id='+me.attr('msg_id')+'&msg_status='+msg_status,
                    success: function(data){
                        if (data.errorCode == 0) {
                            me.find('span').html('');
                            window.location.href = url;
                        } else {
                            layer.open({
                                content: '读取状态修改失败',
                                btn: '确认'
                            });
                        }
                    },
                    dataType: 'json'
                });
            }  
        });
    });
</script>