<?php
require($_SERVER['DOCUMENT_ROOT'] . '/version.php');
require($_SERVER['DOCUMENT_ROOT'] . '/include/update/file.func.php');
defined('IN_UPDATE') or exit('No Access');

set_error_handler('myerror', E_STRICT);

$release = isset($_GET['release']) ? intval($_GET['release']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$release or msg('非法操作');
$release_dir = $_SERVER["DOCUMENT_ROOT"] . '/data/update/' . $release;
switch($action) {
	case 'download':
		if($release<=$version['release']){
			msg('请升级高版本', 'index.php',2);
		}
		$PHP_URL = @get_cfg_var('allow_url_fopen');
		if(!$PHP_URL) msg('当前服务器不支持URL打开文件，请修改php.ini中allow_url_fopen = on');
		$url = 'http://down.haofenxiao.net/update.php?product='.$version['product'].'&type='.$version['type'].'&release='.$release;
		$code = @file_get_contents($url);
		if($code) {
			if(substr($code, 0, 8) == 'StatusOK') {
				$code = substr($code, 8);
			} else {
				msg($code);
			}
		} else {
			msg('无法连接官方服务器，请重试或稍后更新');
		}
		
		dir_create($release_dir);
		if(@copy($code, $release_dir . '/' . $release . '.zip')) {
			dir_create($release_dir . '/source/');
			dir_create($release_dir . '/backup/');
			msg('更新下载成功，开始解压缩..', '?action=unzip&release=' . $release,2);
		} else {
			msg('更新下载失败，请重试..');
		}
	break;
	case 'unzip':
		require_once($_SERVER['DOCUMENT_ROOT'] . '/include/update/unzip.class.php');
		$zip = new unzip;
		$zip->extract_zip($release_dir . '/' . $release . '.zip', $release_dir . '/source/');
		if(is_file($release_dir . '/source/version.php')) {	
			if(is_file($release_dir . '/source/update.sql')) {	
				msg('解压缩成功，开始更新数据库..', '?action=cmd&release='.$release,2);
			}else{
				msg('更新解压缩成功，开始更新文件..', '?action=copy&release='.$release,2);
			}
		} else {
			msg('更新解压缩失败，请重试..');
		}
	break;
	case 'copy'://备份&覆盖替换文件
		$files = file_list($release_dir . '/source');
		foreach($files as $v) {
			$file_a = str_replace('data/update/' . $release . '/source/', '', $v);//原系统文件名
			$file_b = str_replace('source/', 'backup/', $v);
			if(is_file($file_a)) file_copy($file_a, $file_b);//备份动作
			//更新动作
			file_copy($v, $file_a) or msg('无法覆盖' . str_replace($_SERVER['DOCUMENT_ROOT'] . '/', '', $file_a) . '请设置此文件及上级目录属性为可写，然后刷新此页');
		}
		msg('文件更新成功，开始运行更新..', '?action=cmd&release=' . $release,2);
	break;
	case 'cmd':
		if (file_exists($release_dir . '/source/cmdupdate.php')) {
			require_once($release_dir . '/source/cmdupdate.php');			
		}

		msg('更新运行成功', '?action=finish&release=' . $release,2);
	break;
	case 'finish':
		if (file_exists($release_dir . '/source/cmdupdate.php')) {
		    file_del($_SERVER["DOCUMENT_ROOT"] . '/cmdupdate.php');
		}
		dir_delete($release_dir . '/source/');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/version.php');
		msg('系统更新成功 当前版本' . $version['version'], 'index.php',2);
	break;
	case 'undo':
		is_file($release_dir . '/backup/version.php') or msg('此版本备份文件不存在，无法还原', '?');
		@include $release_dir . '/source/cmd.inc.php';
		$files = file_list($release_dir . '/backup');
		foreach($files as $v) {
			file_copy($v, str_replace('data/update/' . $release . '/backup/', '', $v));
		}
		msg('系统还原成功', '?');
	break;
	case 'getdown':
		$url = 'http://down.haofenxiao.net/down.php?product=' . $version['product'].'&type='.$version['type'].'&release='.$release;
		$code = @file_get_contents($url);
		if($code){
			if(substr($code, 0, 8) == 'StatusOK') {
				$code = substr($code, 8);
				header('Location:'.$code);
			} else {
				msg($code);
			}
		}else{
			msg('无法连接官方服务器，请重试或稍后更新');
		}
	break;
	default:
		$release > intval($version["release"]) or msg('当前版本不需要运行此更新', '?');
		msg('在线更新已经启动，开始下载更新..', '?action=download&release=' . $release,2);
	break;
}
?>