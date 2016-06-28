<?php
$url = 'http://cs2.haofenxiao.net/cron/Run.php';
$html = file_get_contents($url);
echo $html;