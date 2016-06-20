<?php

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$QuestionID = isset($_GET["QuestionID"]) ? intval($_GET["QuestionID"]) : 0;
$Item = $DB->GetRs("question","*","where Question_ID=".$QuestionID);
if(!$Item){
	echo "无相关信息！";
	exit;
}
$Category = $DB->GetRs("question_category","*","where Category_ID=".$Item["Category_ID"]);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
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
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="question.php">问题列表</a></li>
        <li><a href="question_add.php">提交问题</a></li>
      </ul>
    </div>
    <div id="question" class="r_con_wrap r_con_form">
      <div class="rows">
          <label>提交时间</label>
          <span class="input">
           <?php echo date("Y-m-d H:i:s",$Item["Question_CreateTime"]);?>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>所属分类</label>
          <span class="input">
           <?php echo $Category["Category_Name"];?>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>问题标题</label>
          <span class="input">
          <?php echo $Item["Question_Title"];?>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>问题描述</label>
          <span class="input">
           <?php echo $Item["Question_Content"];?>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>问题状态</label>
          <span class="input">
           <?php echo $Item["Question_Status"]==1 ? '<font style="color:blue">已回复</font>' : '<font style="color:red">未回复</font>';?>
          </span>
          <div class="clear"></div>
        </div>
        <?php if($Item["Question_Status"]==1){?>
        <div class="rows">
          <label>回复时间</label>
          <span class="input">
           <?php echo date("Y-m-d H:i:s",$Item["Question_ReplyTime"]);?>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>回复内容</label>
          <span class="input">
           <?php echo $Item["Question_Reply"];?>
          </span>
          <div class="clear"></div>
        </div>
        <?php }?>
    </div>
  </div>
</div>
</body>
</html>