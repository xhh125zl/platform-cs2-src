<?php $session_name = session_name();
if (!isset($_POST[$session_name])) {
    exit;
} else {
    session_id($_POST[$session_name]);
    session_start();
}

$targetFolder = '/uploadfiles/'.$_SESSION["Users_ID"].'/'; // Relative to the root
if(@is_dir($_SERVER['DOCUMENT_ROOT'] .$targetFolder)===false){
	mkdir($_SERVER['DOCUMENT_ROOT'] .$targetFolder);
}
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	
	$fileTypes = array(
		'image' => array('gif', 'jpg', 'jpeg', 'png'),
		'flash' => array('swf', 'flv'),
		'media' => array('mp3','mp4'),
		'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pem'),
	);
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	foreach($fileTypes as $k=>$v){
		if(in_array($fileParts['extension'],$v)){
			if(@is_dir($_SERVER['DOCUMENT_ROOT'] .$targetFolder.$k)===false){
				mkdir($_SERVER['DOCUMENT_ROOT'] .$targetFolder.$k);
			}
			$targetFile = rtrim($targetFolder,'/') . '/' . $k . '/' . dechex(time()) . dechex(rand(16, 255)) . '.' . $fileParts['extension'];
			if (move_uploaded_file($tempFile,$_SERVER['DOCUMENT_ROOT'] . $targetFile) === false) {
				$Data=array(
					"status"=>0,
					"msg"=>'没有上传权限',
				);
			}else{
				$Data=array(
					"status"=>1,
					"filename"=>$_FILES['Filedata']['name'],
					"filesize"=>number_format($_FILES['Filedata']['size']/1024,2,".",""),
					"filepath"=>$targetFile
				);
			}
		}
	}
	if(empty($Data)){
		$Data=array(
			"status"=>0,
			"msg"=>'无效的文件类型',
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}
?>