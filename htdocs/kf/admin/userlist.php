<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$UsersID = empty($_POST["UsersID"]) ? '' : $_POST["UsersID"];
$KfId = $_POST["KfId"];
$rsKF=$DB->GetRs("kf_account","*","where Account_ID=".$KfId);
if(!$rsKF) exit();
$expiretime = time()-86400;
$first = $DB->GetRs("kf_message","*","where Message_LastTime>=".$expiretime." and KF_Account='".$rsKF["Account_Name"]."' and Users_ID='".$UsersID."' order by Message_ID ASC");
$DB->Get("kf_message","*","where Message_LastTime>=".$expiretime." and KF_Account='".$rsKF["Account_Name"]."' and Users_ID='".$UsersID."' order by Message_ID ASC");
$html = '';
while($r=$DB->fetch_assoc()){
	//if($r["Message_ID"]==$first["Message_ID"]){
	//	$html .= '<div class="cur"><a href="chat.php?UserId='.$r["Message_ID"].'" target="iframe">用户'.$r["Message_ID"].'</a></div>';
	//}else{
		$html .= '<div><a href="chat.php?UserId='.$r["Message_ID"].'" target="iframe">用户'.$r["Message_ID"].'</a></div>';
	//}
}
$Data = array(
	"UserId"=>empty($first["Message_ID"]) ? '' : $first["Message_ID"],
	"UserList"=>$html
);
echo json_encode($Data,JSON_UNESCAPED_UNICODE);
?>