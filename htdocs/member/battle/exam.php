<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("battle_exam","Users_ID='".$_SESSION["Users_ID"]."' and Exam_ID=".$_GET["ExamID"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}	
}
?>
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
      <div class="control_btn"><a href="exam_edit.php" class="btn_green btn_w_120">添加题目</a></div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="7%" nowrap="nowrap">序号</td>
            <td width="31%" nowrap="nowrap" class="left">题目</td>
            <td width="27%" nowrap="nowrap" class="left">答案列表</td>
            <td width="8%" nowrap="nowrap">正确答案</td>
            <td width="12%" nowrap="nowrap">录入时间</td>
            <td width="11%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php $DB->getPage("battle_exam","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Exam_CreateTime desc",$pageSize=10);
		$i=1;
		$CorrectAnswer=array('A','B','C','D');
		while($rsExam=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td class="left"><?php echo $rsExam['Exam_Name']; ?></td>
            <td class="left"><span<?php echo $rsExam['Exam_CorrectAnswer']==1?' class="fc_red"':'' ?>>A. <?php echo $rsExam['Exam_AnswerA']; ?></span><br>
              <span<?php echo $rsExam['Exam_CorrectAnswer']==2?' class="fc_red"':'' ?>>B. <?php echo $rsExam['Exam_AnswerB']; ?></span><br>
              <span<?php echo $rsExam['Exam_CorrectAnswer']==3?' class="fc_red"':'' ?>>C. <?php echo $rsExam['Exam_AnswerC']; ?></span><br>
              <span<?php echo $rsExam['Exam_CorrectAnswer']==4?' class="fc_red"':'' ?>>D. <?php echo $rsExam['Exam_AnswerD']; ?></span><br></td>
            <td nowrap="nowrap"><?php echo $rsExam['Exam_AnswerD']; ?></td>
            <td nowrap="nowrap"><?php echo $CorrectAnswer[$rsExam['Exam_CorrectAnswer']-1]; ?></td>
            <td class="last" nowrap="nowrap"><a href="exam_edit.php?ExamID=<?php echo $rsExam['Exam_ID']; ?>"> <img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /> </a> <a href="exam.php?action=del&ExamID=<?php echo $rsExam['Exam_ID']; ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"> <img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /> </a></td>
          </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>