<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$games = $DB->GetRs("games","*","where Users_ID='".$_SESSION["Users_ID"]."' and Model_ID=".$ModelID);
if($games){
	require_once("edit_".$_GET["ModelID"].".php");
}else{
	require_once("add_".$_GET["ModelID"].".php");
}
?>