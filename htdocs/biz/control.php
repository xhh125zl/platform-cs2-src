<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
	require_once(CMS_ROOT .'/include/update/common.php');
	
	$path = $_SERVER["REQUEST_URI"];	
	$path1 = explode('?',$path);
	$my_path = str_replace('biz','',trim($path1[0],'/'));	
	$arr_my_path = explode('/',$path1[0]);
	if(!in_array($arr_my_path[(count($arr_my_path)-1)],array('login.php','upload_json.php'))){
		if(empty($_SESSION["Users_ID"])){
			header("location:/member/login.php");
		}
	}	

	if(!empty($my_path)){		
		require_once('.'.$my_path);
	}else{
		require_once('index.php');
	}
?>