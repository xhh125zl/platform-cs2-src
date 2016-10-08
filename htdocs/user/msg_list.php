<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/helper/page.class.php';

//echo $BizAccount.'###'.$UsersID.'###'.$BizID;die;
$msg_status = isset($_GET['status']) ? $_GET['status'] : 'system_msg';

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>消息中心</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
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
                <a href="?act=msg_list&status=system_msg"><li class="<?php if(isset($msg_status) && $msg_status == 'system_msg') { echo 'on'; } ?>">系统</li></a>
                <a href="?act=msg_list&status=order_msg"><li class="<?php if(isset($msg_status) && $msg_status == 'order_msg') { echo 'on'; } ?>">订单</li></a>
                <a href="?act=msg_list&status=distribute_msg"><li class="<?php if(isset($msg_status) && $msg_status == 'distribute_msg') { echo 'on'; } ?>">分销</li></a>
                <a href="?act=msg_list&status=withdraw_msg"><li class="<?php if(isset($msg_status) && $msg_status == 'withdraw_msg') { echo 'on'; } ?>">提现</li></a>
            </ul>
        </div>
        <div class="learn_list">
            <ul class="msgList">
                <li>
                    <a href=''>某某分销商哈哈哈哈<p>2016-10-08 15:25:30</p></a>
                </li>
                <?php
                if(!empty($msg_list)){  
                    foreach($msg_list as $k => $v){         
                ?>
                <li>
                    <a href='?act=msg_detail&id=<?=$v['msg_ID'] ?>'><?=$v['msg_Title'] ?><p><?=$v['msg_CreateTime'] ?></p></a>
                </li>
                <?php
                    }
                 } 
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
        <a href='?act=msg_detail&id={{v.msg_ID}}'>{{v.msg_Title}}<p>{{v.msg_CreateTime}}</p></a>
    </li>
{{/each}}
</script>
<style>
#pagemore{clear:both;text-align:center;  color:#666; padding-top: 5px; padding-bottom:5px;}
#pagemore a{ height:30px; line-height:30px; text-align:center;display:block; background-color:#ddd; border-radius: 2px;}
</style>
<div id="pagemore">
<?php
    if (isset($msg_list) && count($msg_list) > 0) {
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
            var url = 'admin.php?act=msg_list&status=' + <?php echo $msg_status; ?> + '&p=' + pageno;

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
    });
</script>