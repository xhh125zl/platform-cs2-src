<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if($_POST)
{
	if(empty($_POST["Title"])){
		echo '<script language="javascript">alert("请填写问题标题！");window.location="javascript:history.back()";</script>';
		exit();
	}
	if(empty($_POST["Content"])){
		echo '<script language="javascript">alert("请填写问题描述！");window.location="javascript:history.back()";</script>';
		exit();
	}
	$Data=array(
		"Category_ID"=>$_POST['CategoryID'],
		"Question_Title"=>$_POST["Title"],
		"Question_Content"=>$_POST["Content"],
		"Question_CreateTime"=>time(),
		"Question_Users"=>$_SESSION["Users_ID"]
	);
	$Flag=$DB->Add("question",$Data);
	if($Flag)
	{
		echo '<script language="javascript">alert("提交成功");window.location="question.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("提交失败");history.back();</script>';
	}
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href="/static/style.css" rel="stylesheet" type="text/css" />
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
    <script type='text/javascript' src='/static/member/js/wechat.js'></script>
    <div class="r_nav">
      <ul>
        <li><a href="question.php">问题列表</a></li>
        <li class="cur"><a href="question_add.php">提交问题</a></li>
      </ul>
    </div>
    <div class="r_con_wrap"> 
    	<form id="question_form" method="post" action="?" class="r_con_form">
        	<div class="rows">
				<label>问题类型</label>
				<span class="input">
					<select name="CategoryID">
                     <?php
                      $DB->Get("question_category","*");
					  while($r=$DB->fetch_assoc()){
					 ?>
                     <option value="<?php echo $r["Category_ID"];?>"><?php echo $r["Category_Name"];?></option>
                     <?php }?>
                    </select>
				</span>
				<div class="clear"></div>
			</div>
      		<div class="rows">
				<label>问题标题</label>
				<span class="input"><input type="text" name="Title" class="form_input" value="" size="25" maxlength="15" notnull /> <span class="fc_red">*</span></span>
				<div class="clear"></div>
			</div>
			<div class="rows">
				<label>问题描述</label>
				<span class="input"><textarea name="Content" style="width:350px; height:200px"></textarea> <span class="fc_red">*</span></span>
				<div class="clear"></div>
			</div>
            
            <div class="rows">
				<label></label>
				<span class="input"><input type="submit" class="btn_green" name="submit" value="提交" /></span>
				<div class="clear"></div>
			</div>
    	</form>
    </div>
  </div>
</div>
</body>
</html>