<?php
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
define("PARTNER_ID", $rsPay['PaymentTeegonClientID']);
define("SECURITY_KEY", $rsPay['PaymentTeegonClientSecret']);
define("NOTIFY_URL", SITE_URL."pay/yijipay/notify_url.php");
define("RETURN_URL", SITE_URL."pay/yijipay/return_url.php");
define("REQUEST_GATEWAY", "https://api.yiji.com");