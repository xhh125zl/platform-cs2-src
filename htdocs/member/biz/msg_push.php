<?php
if(empty($_SESSION["Users_Account"]))
{
    header("location:/member/login.php");
}
$lists = [];
$DB->getPage("msg_push","*","where 1",20);

while($r=$DB->fetch_assoc()){
    $lists[] = $r;
}
foreach($lists as $k=>$msg){
    ?>
<?= $msg['id']?> 内容为:<?=$msg['content']?>,发送时间为<?=date('Y-m-d H:i:s', $msg['create_time'])?><br/>
<?
}

$DB->showPage();
?>