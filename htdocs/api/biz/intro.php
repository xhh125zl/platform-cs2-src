<?php
require_once('global.php');
$header_title = "店铺简介";
$rsBiz['Biz_Introduce'] = htmlspecialchars_decode($rsBiz["Biz_Introduce"],ENT_QUOTES);
include($rsBiz['Skin_ID']."/intro.php");
?>