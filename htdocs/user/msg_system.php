<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/helper/page.class.php';

$DB->get("announce_category","*");
while ($r=$DB->fetch_assoc()) {
    $announce_category[$r['Category_ID']] = $r['Category_Name'];
}
$DB->get("announce_record","*","where Biz_ID='".$BizID."'");
while ($r=$DB->fetch_assoc()) {
    $announce_readStatus[$r['Announce_ID']] = 1;
}
$DB->get("announce","*"," order by Announce_CreateTime desc ");
$key = 0;
while ($r=$DB->fetch_assoc()) {
    if ($r['Announce_Status'] == 1) {
        $announce[$key] = $r;
        if ($r['Category_ID'] > 0) {
            $announce[$key]['Category_Name'] = $announce_category[$r['Category_ID']];
        } else {
            $announce[$key]['Category_Name'] = '';
        }
        $announce[$key]['read_status'] = isset($announce_readStatus[$r['Announce_ID']]) ? $announce_readStatus[$r['Announce_ID']] : 0;     //读取状态 1：已读取
        $key++;
    }  
}

//数据排序  新消息
$left_arr = array();
$right_arr = array();
foreach ($announce as $k => $v) {
    if ($v['read_status'] == 0) {     //未读
        $left_arr[$k] = $announce[$k];
    } else if ($v['read_status'] == 1) {
        $right_arr[$k] = $announce[$k];
    }
}
$announce_list = array_merge($left_arr, $right_arr);

//记录商家读取信息
if ($_POST) {
    $Announce_ID = $_POST['Announce_ID'];
    $read_status = $_POST['read_status'];
    //判断是否已为读取过，读取过就不写了
    if ($read_status == 0) {
        $data = [
                'Biz_ID' => $BizID,
                'Announce_ID' => $Announce_ID,
                'Record_CreateTime' => time()
            ];
        $res = $DB->Add("announce_record", $data);
        if ($res) {
            echo json_encode(['errorCode' => 0, 'msg' => '读取记录写入成功']);
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '读取记录写入失败']);
        }
        die;
    }   
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