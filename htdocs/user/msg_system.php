<?php
if (!defined('USER_PATH')) exit();

require_once "lib/message.php";

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 10;

//获取商家注册时间，以确认显示信息
$biz_info = $DB->GetRs('biz', 'Biz_CreateTime', 'where `Biz_ID` = '.$BizID);
//获取信息的类型
$DB->Get("announce_category","*");
while ($r=$DB->fetch_assoc()) {
    $announce_category[$r['Category_ID']] = $r['Category_Name'];
}
//获取信息总条数
$DB->Get('announce', "*", "where Announce_Status = 1 and Announce_CreateTime > ".$biz_info['Biz_CreateTime']);
$totalCount = $DB->num_rows();

$start = ($p-1)*$pageSize;
$DB->Get("announce","announce.*,announce_record.Record_ID","left join `announce_record` on announce.Announce_ID = announce_record.Announce_ID and announce_record.Biz_ID = ".$BizID." where announce.Announce_Status = 1 and announce.Announce_CreateTime > ".$biz_info['Biz_CreateTime']." order by announce_record.Record_ID,announce.Announce_CreateTime desc limit ".$start.",".$pageSize);
$key = 0;
while ($r=$DB->fetch_assoc()) {
    $announce[$key] = $r;
    if ($r['Category_ID'] > 0) {
        $announce[$key]['Category_Name'] = $announce_category[$r['Category_ID']];
    } else {
        $announce[$key]['Category_Name'] = '';
    }
    $announce[$key]['read_status'] = (isset($r['Record_ID']) && $r['Record_ID'] > 0) ? 1 : 0;     //读取状态 1：已读取
    $key++;
}

if (isset($announce) && count($announce) > 0) {
    $total = $totalCount;
    $totalPage = ceil($totalCount / $pageSize);
    $announce_list = $announce;
} else {
    $total = 0;
    $totalPage = 1;
    $announce_list = [];
}

//分页
$page = new page();
$page->set($pageSize, $total, $p);

$msglist = [];
if (count($announce_list) > 0) {
    foreach ($announce_list as $row) {
        $row['Announce_CreateTime'] = date('Y-m-d H:i:s', $row['Announce_CreateTime']);
        $row['Announce_Content'] = htmlspecialchars_decode($row['Announce_Content']);
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

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>系统消息</title>
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
                    <a href='javascript:;' class="msgs" value="<?php echo $v['Announce_ID']; ?>" status="<?php echo $v['read_status']; ?>">
                        <p><span class="msg_mark"><?php if ($v['read_status'] == 0) {echo '● ';} ?></span><?php if (!empty($v['Category_Name'])) {echo '【'.$v['Category_Name'].'】'; } ?><?php echo $v['Announce_Title']; ?></p>
                        <p><?php echo $v['Announce_CreateTime']; ?></p>
                    </a>
                    <div class="msg_content" style="display:none;">
                        <div class="back_x">
                            <a class="l" href='javascript:layer.closeAll();'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>系统消息
                        </div>
                        <h3><?php echo $v['Announce_Title']; ?></h3>
                        <h5 style="text-align:center;"><?php echo $v['Announce_CreateTime']; ?></h5>
                        <div style="width: 90%; margin: 10px auto 0; line-height: 20px; "><?php echo $v['Announce_Content']; ?></div>
                    </div>
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
        <a href='javascript:;' class="msgs" value="{{v.Announce_ID}}" status="{{v.read_status}}">
            <p><span class="msg_mark">{{if v.read_status == 0}}● {{/if}}</span>{{if v.Category_Name != ''}}【{{v.Category_Name}}】{{/if}}{{v.Announce_Title}}</p>
            <p>{{v.Announce_CreateTime}}</p>
        </a>
        <div class="msg_content" style="display:none;">
            <div class="back_x">
                <a class="l" href='javascript:layer.closeAll();'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>系统消息
            </div>
            <h3>{{v.Announce_Title}}</h3>
            <h5 style="text-align:center;">{{v.Announce_CreateTime}}</h5>
            <div style="width: 90%; margin: 10px auto 0; line-height: 20px; ">{{v.Announce_Content}}</div>
        </div>
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
            var url = 'admin.php?act=msg_system&p=' + pageno;

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
            var status = me.attr('status');
            layer.open({
                type: 1
                ,content: me.next('.msg_content').html()
                ,anim: 'up'
                ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; overflow:scroll; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
                ,success: function(){
                    if (status == 1) {
                        //消息已读，不用改变状态
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: '?act=msg_system',
                            data: 'Announce_ID='+me.attr('value')+'&read_status='+status,
                            success: function(data){
                                if (data.errorCode == 0) {
                                    me.find('span').html('');
                                } else {
                                    layer.open({content: data.msg, time: 1});
                                }
                            },
                            dataType: 'json'
                        });
                    }
                }
            });
        });
    });
</script>