<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["action"])){
	if($_GET["action"] == "trade" && isset($_GET["id"])){
		$html = array();
		$DB->get("industry","*","where parentid=".$_GET["id"]." order by id asc");
		while($r=$DB->fetch_assoc()){
			$html[] = $r;
		}
		$Data = array(
			"status"=> count($html)>0 ? 1 : 0,
			"html"=>count($html)>0 ? $html : ""
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}
if($_POST){
	$Data=array(
		"company"=>$_POST["company"],
		"industry"=>$_POST["trade"],
		"contact"=>$_POST["contact"],
		"email"=>$_POST["email"],
		"telephone"=>$_POST["telephone"],
		"mobile"=>$_POST["mobile"],
		"addtime"=>time()
	);
	$Flag=$DB->Add("comein",$Data);
	if($Flag){
		$Data=array(
			"status"=>1
		);
	}else{
		$Data=array(
			"status"=>0,
			"msg"=>"提交失败"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}

include("skin/sjrz.php");
?>