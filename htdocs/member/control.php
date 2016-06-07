<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
	//$_SESSION['user_type'] = 'employee';	
	require_once('./employee/top.php');
	require_once('./employee/right_all.php');	
	require_once('./employee/sysright_all.php');	
	$path = $_SERVER["REQUEST_URI"];	
	$path1 = explode('?',$path);
	$my_path = str_replace('member','',trim($path1[0],'/'));	
	$arr_my_path = explode('/',$path1[0]);
	if(!in_array($arr_my_path[(count($arr_my_path)-1)],array('login.php','findpwd.php','upload_json.php'))){
		if(empty($_GET["autodata"])){			
		if(empty($_SESSION["Users_ID"])){
			header("location:/member/login.php");
		}else{
			if(!empty($_SESSION['Users_Account']) && isset($_SESSION['user_type'])){
				if(empty($_SESSION['employee_id'])){
					header("location:/member/login.php");
				}
			}
		}
	}
	}	
	//var_dump($arr_my_path);
	//echo '目录'.$my_dir.'--文件'.$my_file;
	if(!empty($my_path)){		
		if(count(array_filter($arr_my_path)) == 3 || count(array_filter($arr_my_path)) == 4){				
				require_once('employee/vertify.php');
				check_right($path,$rmenu,$sysrmenu,$rmenusub,$sysrmenusub);				
		}
		//echo $my_path;
		require_once('.'.$my_path);
	}else{
		require_once('index.php');
	}
//var_dump($_SESSION);
?>