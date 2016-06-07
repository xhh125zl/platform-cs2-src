<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsConfig=$DB->GetRs("kf_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"KF_Icon"=>'/static/kf/ico/00.png'
	);
	$DB->Add("kf_config",$Data);
	$rsConfig=$DB->GetRs("kf_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="item"){
		$Flag=$DB->Set("kf_config","KF_".$_GET['field']."=".(empty($_GET['Status'])?1:0),"where Users_ID='".$_SESSION["Users_ID"]."'");
		$Data=array("status"=>1);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}elseif($_GET["action"]=="icon"){
		$ico = '/static/kf/ico/'.(empty($_GET['ico'])?"00":$_GET['ico']).'.png';
		$Flag=$DB->Set("kf_config","KF_Icon='".$ico."'","where Users_ID='".$_SESSION["Users_ID"]."'");
		$Data=array("status"=>1);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}
if($_POST){
	$_POST['Code'] = htmlspecialchars($_POST['Code'], ENT_QUOTES);
	$Flag=$DB->Set("kf_config",array("KF_Code"=>$_POST["Code"]),"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location="config.php";</script>';
	}else{
		echo '<script language="javascript">alert("设置失败");history.back();</script>';
	}
	exit;
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
    <script language="javascript">$(document).ready(kf_obj.web_init);</script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="config.php">客服设置</a></li>
      </ul>
    </div>
    <div id="kf_web" class="r_con_wrap">
	  <div class="table">
		<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
			<thead>
				<tr>
					<td width="60%">功能模块</td>
					<td width="40%" class="last">启用</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>微官网</td>
					<td class="last"><img src="/static/member/images/ico/<?php echo $rsConfig['KF_IsWeb']==1?'on':'off' ?>.gif" field="IsWeb" Status="<?php echo $rsConfig['KF_IsWeb']; ?>" /></td>
				</tr>
				<tr>
					<td>微商城</td>
					<td class="last"><img src="/static/member/images/ico/<?php echo $rsConfig['KF_IsShop']==1?'on':'off' ?>.gif" field="IsShop" Status="<?php echo $rsConfig['KF_IsShop']; ?>" /></td>
				</tr>
				<tr>
					<td>会员中心</td>
					<td class="last"><img src="/static/member/images/ico/<?php echo $rsConfig['KF_IsUser']==1?'on':'off' ?>.gif" field="IsUser" Status="<?php echo $rsConfig['KF_IsUser']; ?>" /></td>
				</tr>
			</tbody>
		</table>
	  </div>
	  <div class="ico_list">
		<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
			<thead>
				<tr>
					<td class="last">第三方客服代码</td>
				</tr>
			</thead>
			<tbody>
                <tr>
					<td class="last" align="left" nowrap="nowrap">
					   <form id="kfconfig_form" method="post" action="?" style="width:70%; margin:0px auto">
						<textarea name="Code" style="width:100%; height:87px;" notnull><?php echo $rsConfig["KF_Code"];?></textarea>
						<input type="submit" value="提交保存" name="submit_btn" style="margin:15px auto 8px; display:block; height:30px; line-height:30px; background:#3AA0EB; border:none; color:#FFF; width:145px; border-radius:5px; text-align:center; text-decoration:none;">
					   </form>
                    </td>
				</tr>
			</tbody>
		</table>
	  </div>
	  <div class="clear"></div>
	</div>
  </div>
</div>
</body>
</html>