<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$UsersID=$_GET["UsersID"];
$type=$_POST["type"];
if($type=='judge'){
	$rsExam=$DB->GetRs("battle_exam","*","where Users_ID='".$UsersID."' and Exam_ID=".$_POST["ExamID"]);
	if($rsExam['Exam_CorrectAnswer']==$_POST["answer"]){
		$_SESSION[$UsersID."SN_Integral"]=$_SESSION[$UsersID."SN_Integral"]+1;
		$Data=array(
			"stu"=>1
		);
	}else{
		$Data=array(
			"stu"=>2
		);
	}
}elseif($type=='nextQuestion'){
	$currentExamID=$_POST["currentExamID"];
	$notInId=empty($_POST["notInId"])?'0,'.$currentExamID:$_POST["notInId"].','.$currentExamID;
	$DB->query("SELECT * FROM `battle_exam` AS a JOIN ( SELECT ROUND(RAND() * ( ( SELECT MAX(Exam_ID) FROM `battle_exam` WHERE Users_ID='".$UsersID."' and Exam_ID not in (".$notInId.")) - ( SELECT MIN(Exam_ID) FROM `battle_exam` WHERE Users_ID='".$UsersID."' and Exam_ID not in (".$notInId.")) ) + ( SELECT MIN(Exam_ID) FROM `battle_exam` WHERE Users_ID='".$UsersID."' and Exam_ID not in (".$notInId.")) ) AS Rand_ID ) AS b WHERE a.Users_ID='".$UsersID."' and a.Exam_ID not in (".$notInId.") and a.Exam_ID >= b.Rand_ID ORDER BY a.Exam_ID LIMIT 1");
	$rsExam=$DB->fetch_assoc();
	$Data=array(
		'question'=>'<div class="questionTitle">
      <div class="questionFrame">第<b class="currentNum">'.count(explode(',',$notInId)).'</b>题：'. $rsExam['Exam_Name'].'</div><div class="clean"></div></div><div currentId="'.$rsExam['Exam_ID'].'" class="questionList">A. '.$rsExam['Exam_AnswerA'].'</div><div currentId="'.$rsExam['Exam_ID'].'" class="questionList">B. '.$rsExam['Exam_AnswerB'].'</div><div currentId="'.$rsExam['Exam_ID'].'" class="questionList">C. '.$rsExam['Exam_AnswerC'].'</div><div currentId="'.$rsExam['Exam_ID'].'" class="questionList">D. '.$rsExam['Exam_AnswerD'].'</div></div>',
		//'source'=>'第<b class="currentNum">'.count(explode(',',$notInId)).'</b>题',
		'notInId'=>$notInId,
		'currentExamID'=>$currentExamID
	);
}
echo json_encode($Data,JSON_UNESCAPED_UNICODE);
?>