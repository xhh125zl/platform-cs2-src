<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
  $UsersID=$_GET["UsersID"];
}else{
  exit;
}

$php_path = $_SERVER['DOCUMENT_ROOT'].'/';
$save_path = $php_path.'uploadfiles/userfile/';
$save_url = '/uploadfiles/userfile/';

$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png'),
);

$max_size = 512000;
$save_path = realpath($save_path) . '/';

if (!empty($_FILES['upthumb']['error'])) {
	switch($_FILES['upthumb']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	alert($error);
}

//有上传文件时
if (empty($_FILES) === false) {
	//原文件名
	$file_name = $_FILES['upthumb']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['upthumb']['tmp_name'];
	//文件大小
	$file_size = $_FILES['upthumb']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查目录
	if (@is_dir($save_path) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($save_path) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmp_name) === false) {
		alert("上传失败。");
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过".($max_size/1024)."K限制。");
	}
	//检查目录名
	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("目录名不正确。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	//创建文件夹
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	$save_path .= $_SESSION[$UsersID."User_ID"] . "/";
	$save_url .= $_SESSION[$UsersID."User_ID"] . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
			mkdir($save_path);
		}
	}
	//新文件名
	$UploadFilesID=dechex(time()) . dechex(rand(16, 255));
	$new_file_name = $UploadFilesID . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		echo "上传文件失败";
		exit;
	}
	@chmod($file_path, 0644);
	$file_url = $save_url . $new_file_name;
/*向数据库中插入记录*/
	mysql_query("INSERT INTO uploadfiles(
	UploadFiles_ID,
	UploadFiles_TableField,
	UploadFiles_DirName,
	UploadFiles_SavePath,
	UploadFiles_FileName,
	UploadFiles_FileSize,
	UploadFiles_CreateDate
	)VALUES(
	'".$UploadFilesID."',
	'userfile',
	'".$dir_name."',
	'".$file_url."',
	'".$file_name."',
	".number_format($file_size/1024,2,".","").",
	'".date("Y-m-d H:i:s")."'
	)
	");
	$Data = array(
		"Shop_Logo"=>$file_url,
		"Is_Regeposter"=>1,
	);
	
	$UsersID = isset($_GET["UsersID"]) ? $_GET["UsersID"] : '';
	$Flag = $DB->Set("shop_distribute_account",$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
	header("location:/api/".$UsersID."/shop/distribute/edit_headimg/");
}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}