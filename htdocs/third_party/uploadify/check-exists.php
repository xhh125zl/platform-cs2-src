<?php session_start();
if(empty($_POST['action'])){
	$targetFolder = '/uploadfiles'; // Relative to the root and should match the upload folder in the uploader script
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/' . $_POST['filename'])) {
		echo 1;
	} else {
		echo 0;
	}
	exit;
}else{
	$Data=array(
		"status"=>1,
		"session_name"=>session_name(),
		"session_id"=>session_id()
	);
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}
?>