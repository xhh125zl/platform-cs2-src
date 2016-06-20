<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$Flag=false;
$OperatorID=empty($_REQUEST['OperatorID'])?0:$_REQUEST['OperatorID'];
$rsOperator=$DB->GetRs("user_operator","*","where Users_ID='".$_SESSION["Users_ID"]."' and Operator_ID=".$OperatorID);
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("user_operator","Users_ID='".$_SESSION["Users_ID"]."' and Operator_ID=".$OperatorID);
	}
	if($Flag)
	{
		echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else
	{
		echo '<script language="javascript">alert("删除失败");history.back();</script>';
	}
	exit;
}
if($_POST)
{
	if(empty($_POST["UserName"])){
		echo '<script language="javascript">alert("请填写名称");history.go(-1);</script>';exit;
	}
	if(empty($_POST["Password"])){
		echo '<script language="javascript">alert("请填写地址");history.go(-1);</script>';exit;
	}
	if($rsOperator)
	{
		$Data=array(
			"Operator_UserName"=>$_POST["UserName"],
			"Operator_Password"=>$_POST["Password"]
		);
		$Flag=$DB->Set("user_operator",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Operator_ID=".$OperatorID);
	}
	else
	{
		$Password=$DB->GetRs("user_operator","*","where Users_ID='".$_SESSION["Users_ID"]."' and Operator_Password='".$_POST["Password"]."'");
		if($Password){
			echo '<script language="javascript">alert("密码已被占用，请重新输入！");history.back();</script>';
		}else{
			$Data=array(
				"Operator_UserName"=>$_POST["UserName"],
				"Operator_Password"=>$_POST["Password"],
				"Users_ID"=>$_SESSION["Users_ID"]
			);
			$Flag=$DB->Add("user_operator",$Data);
		}
	}
	if($Flag)
	{
		echo '<script language="javascript">alert("'.$_POST['submit_btn'].'成功");window.location="business_password.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
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
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a>
          <dl>
            <dd class="first"><a href="lbs.php">一键导航设置</a></dd>
          </dl>
        </li>
        <li class=""> <a href="user_list.php">会员管理</a>
          <dl>
            <dd class="first"><a href="user_level.php">会员等级设置</a></dd>
            <dd class=""><a href="user_profile.php">会员注册资料</a></dd>
            <dd class=""><a href="card_benefits.php">会员权利说明</a></dd>
            <dd class=""><a href="user_list.php">会员管理</a></dd>
          </dl>
        </li>
        <li class=""> <a href="card_config.php">会员卡设置</a></li>
        <li class="cur"><a href="business_password.php">商家密码设置</a></li>
		<li class="cur"><a href="message.php">消息发布管理</a></li>
      </ul>
    </div>
    <script language="javascript">$(document).ready(user_obj.business_password);</script>
    <div id="business_password" class="r_con_wrap">
      <form id="add_form" class="add_form<?php echo empty($rsOperator)?"":" mod_form" ?>" method="post" action="business_password.php">
        <table border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td>名称
              <input type="text" name="UserName" value="<?php echo empty($rsOperator["Operator_UserName"])?"":$rsOperator["Operator_UserName"] ?>" class="form_input" notnull /></td>
            <td>密码
              <input type="text" name="Password" value="<?php echo empty($rsOperator["Operator_Password"])?"":$rsOperator["Operator_Password"] ?>" class="form_input" style="ime-mode:disabled;" notnull /></td>
            <td><input type="submit" class="submit" value="<?php echo empty($rsOperator)?"添加":"更新" ?>" name="submit_btn"></td>
          </tr>
        </table>
        <input type="hidden" name="OperatorID" value="<?php echo $OperatorID ?>" />
      </form>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="15%" nowrap="nowrap">序号</td>
            <td width="35%" nowrap="nowrap">名称</td>
            <td width="35%" nowrap="nowrap">密码</td>
            <td width="15%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
<?php $DB->getPage("user_operator","*","where Users_ID='".$_SESSION["Users_ID"]."'",$pageSize=10);
$i=1;
while($rsOperator=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsOperator["Operator_UserName"] ?></td>
            <td><?php echo $rsOperator["Operator_Password"] ?></td>
            <td class="last" nowrap="nowrap"><a href="business_password.php?OperatorID=<?php echo $rsOperator["Operator_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="编辑" /></a> <a href="business_password.php?action=del&OperatorID=<?php echo $rsOperator["Operator_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
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