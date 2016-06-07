<?php
//引入文件类
require_once('Smarty/Smarty.class.php');

//实例化
Smarty::unmuteExpectedErrors();
$smarty = new Smarty();
//指定模版存放目录
$template_dir = $_SERVER["DOCUMENT_ROOT"].'/api/';

$smarty->template_dir = $template_dir;
//指定编译文件存放目录
$compile_dir = $_SERVER["DOCUMENT_ROOT"]."/data/templates_c/";
$smarty->compile_dir = $compile_dir;
//指定配置文件存放目录
$smarty->config_dir = $_SERVER["DOCUMENT_ROOT"].'/data/config/';

//指定缓存存放目录
$smarty->cache_dir = $_SERVER["DOCUMENT_ROOT"].'data/cache/';

$smarty->caching = false;


