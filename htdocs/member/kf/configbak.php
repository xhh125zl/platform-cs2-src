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
        <li class=""><a href="account.php">坐席管理</a></li>
        <li class="cur"><a href="config.php">网页客服设置</a></li>
        <li class=""><a href="/kf/admin/login.php" target="_blank">网页客服系统</a></li>
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
					<td class="last">客服图标</td>
				</tr>
			</thead>
			<tbody>
                <tr>
					<td class="last" align="left" nowrap="nowrap">
						<img src='/static/kf/ico/00.png' ico='00' <?php echo $rsConfig["KF_Icon"]=="/static/kf/ico/00.png" ? " class='cur'" : ""?>>
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