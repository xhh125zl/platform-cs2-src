<?php

if(empty($_SESSION["Users_Account"]))
{
    header("location:/member/login.php");
}
require_once(CMS_ROOT . '/include/api/JPush.php');
$action = $_GET['action'];

if ($action == 'send') {
    $content = $_POST['content'];
    $jPush = new Jpush_send();
    $jPush->send_pub('all', $content);
}