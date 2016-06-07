<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo 'error';
	exit;
}
$Data=array(
	"Users_ID"=>$UsersID,
	"Room_ID"=>isset($_POST["RoomID"]) && $_POST["RoomID"]<>"" ? $_POST["RoomID"] : "0",
	"Reserve_Name"=>isset($_POST["Name"]) && $_POST["Name"]<>"" ? $_POST["Name"] : "",
	"Reserve_Mobile"=>isset($_POST["Telephone"]) && $_POST["Telephone"]<>"" ? $_POST["Telephone"] : "",
	"Reserve_SDate"=>isset($_POST["ReserveDate"]) && $_POST["ReserveDate"]<>"" ? $_POST["ReserveDate"] : "",
	"Reserve_SHour"=>isset($_POST["ReserveTimeHour"]) && $_POST["ReserveTimeHour"]<>"" ? $_POST["ReserveTimeHour"] : "0",
	"Reserve_SMinute"=>isset($_POST["ReserveTimeMinute"]) && $_POST["ReserveTimeMinute"]<>"" ? $_POST["ReserveTimeMinute"] : "0",
	"Reserve_EDate"=>isset($_POST["CheckOutDate"]) && $_POST["CheckOutDate"]<>"" ? $_POST["CheckOutDate"] : "",
	"Reserve_EHour"=>isset($_POST["CheckOutHour"]) && $_POST["CheckOutHour"]<>"" ? $_POST["CheckOutHour"] : "0",
	"Reserve_EMinute"=>isset($_POST["CheckOutMinute"]) && $_POST["CheckOutMinute"]<>"" ? $_POST["CheckOutMinute"] : "0",
	"Reserve_PriceY"=>isset($_POST["PriceY"]) && $_POST["PriceY"]<>"" ? $_POST["PriceY"] : "0",
	"Reserve_PriceX"=>isset($_POST["PriceX"]) && $_POST["PriceX"]<>"" ? $_POST["PriceX"] : "0",
	"Reserve_CreateTime"=>time(),
	"Reserve_Status" => 0
);
$Flag=$DB->Add("app_hotels_reserve",$Data);
if($Flag){
	$Data=array(
		"status"=>1
	);
}else{
	$Data=array(
		"status"=>$_POST["RoomID"],
		"msg"=>"发生错误"
	);
}
echo json_encode($Data,JSON_UNESCAPED_UNICODE);
?>