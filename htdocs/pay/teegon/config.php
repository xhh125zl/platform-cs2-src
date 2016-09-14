<?php
define('TEE_SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
define('VER', 'v1');
define('TEE_API_URL', 'https://api.teegon.com/'.VER);
define('TEE_CLIENT_ID', $rsPay['PaymentTeegonClientID']);
define('TEE_CLIENT_SECRET', $rsPay['PaymentTeegonClientSecret']);
define('NOTIFY_URL' ,'http://'.$_SERVER['HTTP_HOST'].'/pay/teegon/notify_url.php');
define('RETURN_URL' ,'http://'.$_SERVER['HTTP_HOST'].'/pay/teegon/return_url.php');