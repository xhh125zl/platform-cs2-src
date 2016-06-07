<?php 
//$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$ExamID=empty($_REQUEST['ExamID'])?0:$_REQUEST['ExamID'];
$rsExam=$DB->GetRs("battle_exam","*","where Exam_ID=".$ExamID);
if($_POST){	
	if($rsExam){
		$Data=array(
			"Exam_Name"=>htmlspecialchars($_POST["Name"]),
			"Exam_AnswerA"=>htmlspecialchars($_POST["AnswerA"]),
			"Exam_AnswerB"=>htmlspecialchars($_POST["AnswerB"]),
			"Exam_AnswerC"=>htmlspecialchars($_POST["AnswerC"]),
			"Exam_AnswerD"=>htmlspecialchars($_POST["AnswerD"]),
			"Exam_CorrectAnswer"=>empty($_POST['CorrectAnswer'])?0:$_POST['CorrectAnswer']
		);
		$Flag=$DB->Set("battle_exam",$Data,"where Exam_ID=".$ExamID);
	}else{
		$Data=array(
			"Users_ID"=>$_SESSION["Users_ID"],
			"Exam_Name"=>htmlspecialchars($_POST["Name"]),
			"Exam_AnswerA"=>htmlspecialchars($_POST["AnswerA"]),
			"Exam_AnswerB"=>htmlspecialchars($_POST["AnswerB"]),
			"Exam_AnswerC"=>htmlspecialchars($_POST["AnswerC"]),
			"Exam_AnswerD"=>htmlspecialchars($_POST["AnswerD"]),
			"Exam_CorrectAnswer"=>empty($_POST['CorrectAnswer'])?0:$_POST['CorrectAnswer'],
			"Exam_CreateTime"=>time()			
		);
		$Flag=$DB->Add("battle_exam",$Data);
	}
	if($Flag){
		echo '<script language="javascript">alert("保存成功");window.location="exam.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content"> 
    <script type='text/javascript' src='/static/member/js/battle.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="exam.php">题库管理</a></li>
        <li class=""><a href="battle.php">活动管理</a></li>
        <li class=""><a href="battle_user.php">用户列表</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(battle_obj.exam_init);</script>
      <form id="exam_form" class="r_con_form" method="post" action="exam_edit.php">
        <div class="rows">
          <label>题目</label>
          <span class="input">
          <input type="text" name="Name" value="<?php echo $rsExam['Exam_Name'] ?>" class="form_input" size="25" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input fc_red">温馨提示：答案填写以后勿忘勾选正确的答案</span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>答案A</label>
          <span class="input">
          <input type="text" name="AnswerA" value="<?php echo $rsExam['Exam_AnswerA'] ?>" class="form_input" size="35" maxlength="100" notnull />
          <input type="radio" name="CorrectAnswer" value="1"<?php echo $rsExam['Exam_CorrectAnswer']==1||empty($rsExam['Exam_CorrectAnswer'])?' checked="checked"':'' ?> />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>答案B</label>
          <span class="input">
          <input type="text" name="AnswerB" value="<?php echo $rsExam['Exam_AnswerB'] ?>" class="form_input" size="35" maxlength="100" />
          <input type="radio" name="CorrectAnswer" value="2"<?php echo $rsExam['Exam_CorrectAnswer']==2?' checked="checked"':'' ?> />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>答案C</label>
          <span class="input">
          <input type="text" name="AnswerC" value="<?php echo $rsExam['Exam_AnswerC'] ?>" class="form_input" size="35" maxlength="100" />
          <input type="radio" name="CorrectAnswer" value="3"<?php echo $rsExam['Exam_CorrectAnswer']==3?' checked="checked"':'' ?> />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>答案D</label>
          <span class="input">
          <input type="text" name="AnswerD" value="<?php echo $rsExam['Exam_AnswerD'] ?>" class="form_input" size="35" maxlength="100" />
          <input type="radio" name="CorrectAnswer" value="4"<?php echo $rsExam['Exam_CorrectAnswer']==4?' checked="checked"':'' ?> />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="javascript:void(0);" onClick="history.go(-1);" class="btn_gray">返回</a> </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="ExamID" value="<?php echo $rsExam['Exam_ID'] ?>">
      </form>
    </div>
  </div>
</div>
</body>
</html>