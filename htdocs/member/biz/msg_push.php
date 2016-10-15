<?php
if(empty($_SESSION["Users_Account"]))
{
    header("location:/member/login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>推送消息管理</title>
    <style>
        *{margin:0px; padding:0px;}
        ul, li, dt, dd, ol, dl{list-style-type:none;}
        .clear{clear:both;}
        img{border:none;vertical-align: middle;}
        body{-webkit-tap-highlight-color:transparent;font-size:14px; font-family:"微软雅黑"}
        table.tab_xx{ background:#f8f8f8; width:94%;margin:20px auto;overflow:hidden;text-align:center;    color: #333;border-width: 1px;border-color: #666666;border-collapse: collapse;}
        table.tab_xx tr th{  border: 1px #666666 solid; padding: 8px; background-color: #fff2cc;}
        table.tab_xx tr td{ border: 1px #666666 solid; padding: 8px; background-color: #ffffff;}
    </style>
</head>
<body>


<?
$lists = [];
$DB->getPage("msg_push","*","where 1 order by id desc",20);

while($r=$DB->fetch_assoc()){
    $lists[] = $r;
}?>

<table  class="tab_xx">
    <tbody>
    <tr>
        <th>ID</th><th>推送内容</th><th>推送时间</th>
    </tr>
    <?
    foreach($lists as $k=>$msg){
        ?>
        <tr>
            <td><?= $msg['id']?></td>
            <td><?=$msg['content']?></td>
            <td><?=date('Y-m-d H:i:s', $msg['create_time'])?></td>
        </tr>
        <?
    }
    ?>

    </tbody>
</table>
<?
$DB->showPage();
?>
</body>
</html>
