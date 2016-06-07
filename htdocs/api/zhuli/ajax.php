<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$rsConfig = $DB->GetRs("zhuli","*","where Users_ID='".$UsersID."'");
if($rsConfig["Fromtime"]){
	if($rsConfig["Fromtime"]>time()){
		$Data = array(
			"status"=>0,
			"msg"=>'活动未开始'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}

if($rsConfig["Totime"]){
	if($rsConfig["Totime"]<time()){
		$Data = array(
			"status"=>0,
			"msg"=>'活动已结束'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}

$action = !empty($_REQUEST['action'])?$_REQUEST['action']:'do_zhuli';

if($action == 'do_zhuli'){
	$actinfo = $DB->GetRs("zhuli_act","*","where Users_ID='".$UsersID."' and Act_ID=".$_POST["actid"]);
	if(!$actinfo){
		$Data = array(
		    "status"=>0,
			"msg"=>'不存在该活动'
		);
	}else{
		$score = rand(5,30);
		$data  = array(
			'Act_ID'=>$_POST["actid"],
			'Users_ID'=>$UsersID,
			'User_ID'=>$_SESSION[$UsersID."User_ID"],
			'Open_ID'=>'',
			'Open_Info'=>'Open_Info',
			'Record_Time'=>time(),
			'Record_Score'=>$score
		);
		$flag = $DB->Add("zhuli_record",$data);
		$data  = array(
			'Act_Score'=>$actinfo["Act_Score"]+$score
		);	
		$flag = $DB->Set("zhuli_act",$data,"where Act_ID=".$_POST["actid"]);
		if($flag){
			$Data = array(
				"status"=>1,
				"msg"=>'助力成功'
			);
		}else{
			$Data = array(
				"status"=>0,
				"msg"=>'助力失败'
			);
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}elseif($action == 'join'){
	$actinfo = $DB->GetRs("zhuli_act","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
	if($actinfo){
		$Data = array(
			"status"=>1,
			"msg"=>'您已参加',
			"url"=>"/api/zhuli/index.php?UsersID=".$UsersID."_".$actinfo["Act_ID"]
		);
	}else{
		$data = array(
			'Users_ID'=>$UsersID,
			'User_ID'=>$_SESSION[$UsersID."User_ID"],
			'Open_ID'=>'',
			'Act_Time'=>time()
		);
		$flag = $DB->Add("zhuli_act",$data);
		$Act_ID = $DB->insert_id();
		if($flag){
			$Data = array(
				"status"=>1,
				"msg"=>'参加成功',
				"url"=>"/api/zhuli/index.php?UsersID=".$UsersID."_".$Act_ID
			);
		}else{
			$Data = array(
				"status"=>0,
				"msg"=>'参加失败'				
			);
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}
?>