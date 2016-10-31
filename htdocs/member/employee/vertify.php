<?php
/*	权限验证
*	$path 当前地址
*/
function check_right($path,$file,$file_all,$filesub,$file_allsub){	
	global $DB;		
	$my_dir = str_replace(dirname(dirname($path)).'/','',dirname($path));
	$my_file = basename($path, '.php');
	if(strstr($my_file,"?")){
		$myfilearr = array();
		$myfilearr = explode("?",$my_file);
		$my_file = basename($myfilearr[0], '.php');		
	}
	//商家权限
	$rsUsers = $DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	$Users_Right = json_decode($rsUsers['Users_Right'],true);
	foreach($file as $key=>$value){
	if (array_key_exists($key,$Users_Right)){
	foreach($value as $k=>$v){
		$Users_Right[$key][] = $k;
		}
	}
}
$myrmenus = array();
foreach($file_all as $key=>$value){
	if (array_key_exists($key,$Users_Right)){		
		foreach($value as $k=>$v){			
			if(array_key_exists(substr($key,0,3).'_'.$k,$file_allsub)){
				foreach($file_allsub[substr($key,0,3).'_'.$k] as $ks=>$kv){					
					$myrmenus[$key][$ks] = $kv;					
				}
			}
			if($key == 'weicuxiao'){
				$cux = 0;
				$cux = $k == 'weicuxiao'?1:0;
				if($k != 'weicuxiao' && in_array($k,$Users_Right[$key])){			
				$cux = 1;
				}
			}else{
				$cux = 1;
			}
		if($cux == 1){
					$myrmenus[$key][$k] = $v;
				}				
			}
		}
	}
	
	$new_file = array();
	foreach($file as $key=>$value){
		foreach($value as $k=>$v){
		if(array_key_exists(substr($key,0,3).'_'.$k,$filesub)){
			foreach($filesub[substr($key,0,3).'_'.$k] as $ks=>$kv){					
					$new_file[$key][$ks] = $kv;					
				}
		}
					$new_file[$key][$k] = $v;
		}
	}
	$my_rights = array_merge($new_file,$myrmenus);
	$all_rights = array_merge($file,$file_all);
	$all_subright = array_merge($filesub,$file_allsub);
	$new_all = array();
	foreach($all_rights as $key=>$value){
		foreach($value as $k=>$v){
		if(array_key_exists(substr($key,0,3).'_'.$k,$all_subright)){
			foreach($all_subright[substr($key,0,3).'_'.$k] as $ks=>$kv){
					$new_all[$key][$ks] = $kv;
				}
		}
					$new_all[$key][$k] = $v;
		}
	}
	//var_dump($my_users_right);
	if(isset($_SESSION['user_type'])){
	//员工权限
	$my_rightcopy = array();
	$my_rightcopy = $my_rights;	
	$em_right = array();
	$role = $DB->GetRs("users_roles","*","where id='".$_SESSION["role_id"]."'");
	$employee_right = json_decode($role['role_right'],true)?json_decode($role['role_right'],true):array();

	foreach ($my_rightcopy as $key=>$val){
		if (array_key_exists($key,$employee_right)){
		foreach($val as $k=>$v){			
		if(in_array($k,$employee_right[$key])){
			if(array_key_exists(substr($key,0,3).'_'.$k,$all_subright)){
			foreach($all_subright[substr($key,0,3).'_'.$k] as $ks=>$kv){					
					$em_right[$key][$ks] = $kv;					
				}
		}
			$em_right[$key][$k] = $v;
		}			
		}
	}
	}
	}
	//微促销
	$spathArray = Array("scratch","fruit","turntable","battle","guanggao");
	//var_dump($employee_rights);
	//例外
	if($my_dir == 'wechat'){
		if($my_file == 'renewal_record'){
			$my_dir = 'buy_record';
		}else{
			$my_dir = 'weixin';
		}
	}
	if($my_dir == 'shop'){			
		if(isset($_SESSION['user_type'])){
		foreach($em_right as $k=>$v){
				if(isset($em_right[$k][$my_file])){
					$my_dir = $k;
					break;
				}
			}
		}else{
			foreach($new_file as $k=>$v){
				if(isset($new_file[$k][$my_file])){
					$my_dir = $k;
					break;
				}
			}
		}
	}
	if($my_dir == 'sms'){
			$my_dir = 'setting';
	}
	
	if($my_dir == 'setting'){
			$newmy_file = $my_dir."/".$my_file;
			$my_file = $newmy_file;
			foreach($new_file as $k=>$v){
				if(isset($new_file[$k][$newmy_file])){
					$my_dir = $k;
					break;
				}
			}
	}	
	if(in_array($my_dir,$spathArray)){
			$my_file = $my_dir;			
			if($my_file == 'scratch'){
				$my_file = 'sctrach';
			}
			$my_dir = 'weicuxiao';			
	}	
	//echo '标题'.$moudle.'--操作'.$action;
	
	//检测
	$style1 = "style='width:100%;text-align:center;background-color:#F1F2F7;height:500;line-height:500px;margin:0px;'";
	$num = rand(-30,30);

	$style2 = "style='border:3px solid red;width:400px;height:100px;line-height:100px;margin:200px auto;color:red;transform:rotate({$num}deg);-ms-transform:rotate({$num}deg);-moz-transform:rotate({$num}deg);-webkit-transform:rotate({$num}deg);-o-transform:rotate({$num}deg);'";
	
	//商家
		if(isset($my_rights[$my_dir])){
			$moudle = $my_dir;
			$action = $my_file;
		if(isset($_SESSION['user_type'])){
		//员工
		if(isset($em_right[$my_dir])){
			$moudle = $my_dir;
			$action = $my_file;
		}else{
			$moudle = '';
			$action = '';
		}
		}
		}else{
			$moudle = '';
			$action = '';
		}		
	//判断商家权限	
		if(isset($my_rights[$moudle])){		
				if(!isset($my_rights[$moudle][$action])){
					echo "<div class='un_access' ".$style1."><div ".$style2.">您暂未开通<b style='font-size:25px;'>".$new_all[$my_dir][$my_file]."</b>权限,请联系管理员</div></div>";
					exit;
				}
			}else{
				echo "<div class='un_access' ".$style1."><div ".$style2.">您暂未开通<b style='font-size:25px;'>".$new_all[$my_dir][$my_file]."</b>权限,请联系管理员</div></div>";
				exit;
			}
	//判断员工权限
	if(isset($_SESSION['user_type'])){
	if(isset($em_right[$moudle])){
				if(!isset($em_right[$moudle][$action])){
					echo "<div class='un_access' ".$style1."><div ".$style2.">您暂未开通<b style='font-size:25px;'>".$new_all[$my_dir][$my_file]."</b>权限,请联系管理员</div></div>";
					exit;
				}
			}else{
				echo "<div class='un_access' ".$style1."><div ".$style2.">您暂未开通<b style='font-size:25px;'>".$new_all[$my_dir][$my_file]."</b>权限,请联系管理员</div></div>";
				exit;
			}
	}
}
?>