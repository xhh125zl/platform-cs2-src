<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if($_POST)
{	
	$count = $DB->GetRs("kf_account","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."'");
	if($count["num"]>=1){
		$Data = array(
			"status"=>4
		);
	}else{
		$count = $DB->GetRs("kf_account","count(*) as num","where Account_Name='".$_POST['Account']."'");
		if($count["num"]>=1){
			$Data = array(
				"status"=>2
			);
		}else{
			if(strlen($_POST['Account'])<6 || strlen($_POST['Password'])<6){
				$Data = array(
					"status"=>3
				);
			}else{
				$Data=array(
					"Account_Name"=>$_POST['Account'],
					"Account_PassWord"=>md5($_POST['Password']),
					"Users_ID"=>$_SESSION["Users_ID"],
					"Account_CreateTime"=>time()
				);
				$Flag=$DB->Add("kf_account",$Data);
				if($Flag)
				{
					$Data = array(
						"status"=>1
					);
				}else{
					$Data = array(
						"status"=>0
					);
				}
			}
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}
$count = $DB->GetRs("kf_account","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."'");
if($count["num"]>0){
	echo '<script language="javascript">alert("已经设置了客服账号");window.location="account.php";</script>';
}
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/kf.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/kf.js'></script>
    <script language="javascript">$(document).ready(kf_obj.kf_init);</script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="account.php">坐席管理</a></li>
        <li class=""><a href="config.php">网页客服设置</a></li>
        <li class=""><a href="/kf/admin/login.php" target="_blank">网页客服系统</a></li>
      </ul>
    </div>
    <div id="chat" class="r_con_wrap">
      <form id="kf_form" class="r_con_form" method="post" action="?">
        <div class="rows">
          <label>客服账号</label>
          <span class="input">
          <input name="Account" value="" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>登录密码</label>
          <span class="input">
          <input type="Password" class="form_input" name="Password" value="" size="30" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>确认密码</label>
          <span class="input">
          <input type="Password" class="form_input" name="ConfirmPassword" value="" size="30" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="account.php" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>