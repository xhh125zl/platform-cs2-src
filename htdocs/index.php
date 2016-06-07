<?php
$UA = strtoupper($_SERVER['HTTP_USER_AGENT']);
if(strpos($UA, 'WINDOWS NT') == false){
	header("location:/wap/index.php");
}else{
	header("location:/member/index.php");
}
?>