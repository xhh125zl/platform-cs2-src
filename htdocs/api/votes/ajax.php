<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["VotesID"])){
	$VotesID=$_GET["VotesID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET["ItemID"])){
	$ItemID=$_GET["ItemID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$_SESSION[$UsersID."OpenID"] = empty($_SESSION[$UsersID."OpenID"]) ? session_id() : $_SESSION[$UsersID."OpenID"];
$rsVotes=$DB->GetRs("votes","*","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Votes_StartTime<=".time()." and Votes_EndTime>=".time());
$rsItem = $DB->GetRs("votes_item","*","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Item_ID=".$ItemID);
if(!$rsVotes || !$rsItem){
	echo "该信息不存在";
	exit;
}
$msg = '';
if($rsVotes["Votes_TotalCounts"]>0){
	$num = $DB->GetRs("votes_order","count(*) as num","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Open_ID='".$_SESSION[$UsersID."OpenID"]."'");
	if($num["num"]>=$rsVotes["Votes_TotalCounts"]){
		$msg = '每人最多投'.$rsVotes["Votes_TotalCounts"].'票';
		$Data = array(
			"status"=>0,
			"msg"=>$msg
		);
	}
}
if(!$msg && $rsVotes["Votes_DayCounts"]>0){	
	$num = $DB->GetRs("votes_order","count(*) as num","where Users_ID='".$UsersID."' and Votes_ID=".$VotesID." and Item_ID=".$ItemID." and Open_ID='".$_SESSION[$UsersID."OpenID"]."'");
	if($num["num"]>=$rsVotes["Votes_DayCounts"]){
		$msg = '每人对某选项最多投'.$rsVotes["Votes_DayCounts"].'票';
		$Data = array(
			"status"=>0,
			"msg"=>$msg
		);
	}
}
if(!$msg){
	$Data = array(
		'Open_ID'=>$_SESSION[$UsersID."OpenID"],
		"Users_ID"=>$UsersID,
		"Votes_ID"=>$VotesID,
		"Item_ID"=>$ItemID,
		"Order_CreateTime"=>time()
	);
	$flag = $DB->Add("votes_order",$Data);
	$Data = array(		
		"Item_Votes"=>$rsItem["Item_Votes"]+1
	);
	$flag = $DB->Set("votes_item",$Data,"where Users_ID='".$UsersID."' and Item_ID=".$ItemID." and Votes_ID=".$VotesID);
	$Data = array(		
		"Votes_Votes"=>$rsVotes["Votes_Votes"]+1
	);
	$flag = $DB->Set("votes",$Data,"where Users_ID='".$UsersID."' and Votes_ID=".$VotesID);
	if($flag){
		$Data = array(
			"status"=>1,
			"msg"=>"已成功投票"
		);
	}
}

echo json_encode($Data,JSON_UNESCAPED_UNICODE);
?>