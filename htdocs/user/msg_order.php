<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/message.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

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
    foreach ($msgOrder as $row) {
        $row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
        $msglist[] = $row;
    }
}

$return = [
    'page' => [
        'pagesize' => count($msglist),
        'hasNextPage' => (count($msglist) >= $pageSize) ? 'true' : 'false',
        'total' => $total,
    ],
    'data' => $msglist,
];

if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    echo json_encode($return);
    exit();
}

//系统消息未读条数
//获取商家注册时间，以确认显示信息
$biz_info = $DB->GetRs('biz', 'Biz_CreateTime', 'where `Biz_ID` = '.$BizID);
$DB->Get("announce","announce.*,announce_record.Record_ID","left join `announce_record` on announce.Announce_ID = announce_record.Announce_ID and announce_record.Biz_ID = ".$BizID." where announce.Announce_Status = 1 and Announce_CreateTime > ".$biz_info['Biz_CreateTime']." order by announce_record.Record_ID,announce.Announce_CreateTime desc");
$unread_system_nums = 0;
while ($r=$DB->fetch_assoc()) {
    if (!(isset($r['Record_ID']) && $r['Record_ID'] > 0)) {
        $unread_system_nums++;
    }
}
//订单消息未读条数
$transfer = ['Biz_Account' => $BizAccount];
$result = message::getMsgOrder($transfer);
if ($result['errorCode'] == 0) {
    $unread_order_nums = $result['data']['unReadCount'];
} else {
    $unread_order_nums = 0;
}
//分销消息未读条数
$transfer = ['Biz_Account' => $BizAccount];
$result = message::getMsgDistribute($transfer);
if ($result['errorCode'] == 0) {
    $unread_distribute_nums = $result['data']['unReadCount'];
} else {
    $unread_distribute_nums = 0;
}
//提现消息未读条数
$transfer = ['Biz_Account' => $BizAccount];
$result = message::getMsgWithdraw($transfer);
if ($result['errorCode'] == 0) {
    $unread_withdraw_nums = $result['data']['unReadCount'];
} else {
    $unread_withdraw_nums = 0;
}

if ($_POST) {
    $msg_id = $_POST['msg_id'];
    $msg_status = $_POST['msg_status'];
    if ($msg_status == 1) {
        echo json_encode(['errorCode' => 0, 'msg' => '信息状态已为已读，不需更新']);die;
    } else if ($msg_status == 0) {
        $transData = ['msg_status' => 1, 'modify_time' => time()];
        $postData = ['id' => $msg_id, 'transData' => $transData];
        $result = message::updateMsgOrder($postData);
        if ($result['errorCode'] == 0) {
            echo json_encode(['errorCode' => 0, 'msg' => '信息状态更新成功']);die;
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '信息状态更新失败']);die;
        }
    } else {
        echo json_encode(['errorCode' => 1, 'msg' => '信息状态获取失败']);die;
    }
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
                 } else { echo '<h3>暂无消息</h3>'; }
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
        $("#pagemore a").click(function(){
            var totalPage = <?php echo $totalPage;?>;
            var pageno = $(this).attr('data-next-pageno');
            var url = 'admin.php?act=msg_order&p=' + pageno;

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