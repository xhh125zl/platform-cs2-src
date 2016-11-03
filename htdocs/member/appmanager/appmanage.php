<?php
if(empty($_SESSION["Users_Account"]))
{
    header("location:/member/login.php");
}

if (isset($_GET['act']) && $_GET['act'] == 'delete') {
    $file = $DB->GetRs('app_info', '*', "where id = " . (int)$_GET['id']);
    $flag = $DB->Del('app_info'," id = " . (int)$_GET['id']);
    if ($flag) {
        unlink(CMS_ROOT . '/data/app/' . $file['file_name']);
        echo '<script language="javascript">alert("删除成功");window.location.href="appmanage.php";</script>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>APK更新管理</title>
    <style>
        *{margin:0px; padding:0px;}
        ul, li, dt, dd, ol, dl{list-style-type:none;}
        .clear{clear:both;}
        img{border:none;vertical-align: middle;}
        body{-webkit-tap-highlight-color:transparent;font-size:14px; font-family:"微软雅黑"}
        table.tab_xx{ background:#f8f8f8; width:94%;margin:20px auto;overflow:hidden;text-align:center;    color: #333;border-width: 1px;border-color: #666666;border-collapse: collapse;}
        table.tab_xx tr th{  border: 1px #666666 solid; padding: 8px; background-color: #fff2cc;}
        table.tab_xx tr td{ border: 1px #666666 solid; padding: 8px; background-color: #ffffff;}
        table.tab_xx tr td a{text-decoration: none;}
    </style>
    <script type="text/javascript" src="../../static/user/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="../../static/js/plugin/layer/layer.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".update_intro").on('mouseover', function(){
                var me = $(this);
                layer.tips(me.attr('data-intro'), me, {
                    tips: [3, '#3595CC'],
                    time: 20000
                });
            }).on("mouseout", function(){
                layer.closeAll();
            });
        })
    </script>
</head>
<body>


<?
$lists = [];
$DB->getPage("app_info","*","where 1 order by id desc",20);

while($r=$DB->fetch_assoc()){
    $lists[] = $r;
}?>

<table  class="tab_xx">
    <tbody>
    <tr>
        <th>ID</th><th>包名</th><th>版本名称</th><th>版本号</th><th>文件名</th><th>上传时间</th><th>更新简介</th><th>操作</th>
    </tr>
    <?
    if (count($lists) > 0) {
        foreach($lists as $k=>$msg){
            ?>
            <tr>
                <td><?= $msg['id']?></td>
                <td><?=$msg['package_name']?></td>
                <td><?=$msg['version_name']?></td>
                <td><?=$msg['version_code']?></td>
                <td><?=$msg['file_name']?></td>
                <td><?=date('Y-m-d H:i:s', $msg['create_time'])?></td>
                <td class="update_intro" data-intro="<?=$msg['update_intro']?>"><?=mb_substr($msg['update_intro'], 0, 10, 'UTF-8')?></td>
                <td><a href="appedit.php?id=<?=$msg['id']?>" >编辑</a>&nbsp;&nbsp;<a href="?act=delete&id=<?=$msg['id']?>">删除</a></td>
            </tr>
            <?
        }
    }
    ?>

    </tbody>
</table>
<?
$DB->showPage();
?>
</body>
</html>
