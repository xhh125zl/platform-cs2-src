<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/helper/page.class.php';

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>分销消息</title>
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
                <a href="?act=msg_system"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_system') { echo 'on'; } ?>">系统</li></a>
                <a href="?act=msg_order"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_order') { echo 'on'; } ?>">订单</li></a>
                <a href="?act=msg_distribute"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_distribute') { echo 'on'; } ?>">分销</li></a>
                <a href="?act=msg_withdraw"><li class="<?php if(isset($_GET['act']) && $_GET['act'] == 'msg_withdraw') { echo 'on'; } ?>">提现</li></a>
            </ul>
        </div>
        <div class="learn_list">
            <ul class="msgList">
                <?php
                if(!empty($announce_list)){  
                    foreach($announce_list as $k => $v){         
                ?>
                <li>
                    <a href='javascript:;' class="msgs" value="<?php echo $v['Announce_ID']; ?>" status="<?php echo $v['read_status']; ?>">
                        <p><span><?php if ($v['read_status'] == 0) {echo '【新消息】';} ?></span><?php echo $v['Announce_Title']; ?></p>
                        <p><?php echo date('Y-m-d H:i:s', $v['Announce_CreateTime']); ?></p>
                    </a>
                    <div class="msg_content" style="display:none;">
                        <div class="back_x">
                            <a class="l" href='javascript:layer.closeAll();'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>系统消息
                        </div>
                        <h3><?php echo $v['Announce_Title']; ?></h3>
                        <h5 style="text-align:center;"><?php echo date('Y-m-d H:i:s', $v['Announce_CreateTime']); ?></h5>
                        <div style="width: 90%; margin: 10px auto 0; line-height: 20px; "><?php echo htmlspecialchars_decode($v['Announce_Content']); ?></div>
                    </div>
                </li>
                <?php
                    }
                 } 
                ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    $(function(){
        $('.msgs').click(function(){
            var me = $(this);
            layer.open({
              type: 1
              ,content: me.next('.msg_content').html()
              ,anim: 'up'
              ,style: 'position:fixed; left:0; top:0; width:100%; height:100%; overflow:scroll; border: none; -webkit-animation-duration: .5s; animation-duration: .5s;'
              ,success: function(){
                $.ajax({
                    type: 'POST',
                    url: 'admin.php?act=msg_system',
                    data: 'Announce_ID='+me.attr('value')+'&read_status='+me.attr('status'),
                    success: function(data){
                        if (data.errorCode == 0) {
                            me.find('span').html('');
                        }
                    },
                    dataType: 'json'
                });
              }
            });
        });
    });
</script>