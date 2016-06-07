<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$UrlID=empty($_REQUEST['UrlID'])?0:$_REQUEST['UrlID'];
$rsUrl=$DB->GetRs("wechat_url","*","where Users_ID='".$_SESSION["Users_ID"]."' and Url_ID=".$UrlID);
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("wechat_url","Users_ID='".$_SESSION["Users_ID"]."' and Url_ID=".$UrlID);
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
	if(empty($_POST['Name'])){
		echo '<script language="javascript">alert("请填写URL名称");history.go(-1);</script>';exit;
	}
	if(empty($_POST['Value'])){
		echo '<script language="javascript">alert("请填写URL地址");history.go(-1);</script>';exit;
	}
	if($rsUrl)
	{
		$Data=array(
			"Url_Name"=>$_POST["Name"],
			"Url_Value"=>$_POST['Value']
		);
		$Flag=$DB->Set("wechat_url",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Url_ID=".$UrlID);
	}else
	{
		$Data=array(
			"Url_Name"=>$_POST["Name"],
			"Url_Value"=>$_POST['Value'],
			"Users_ID"=>$_SESSION["Users_ID"]
		);
		$Flag=$DB->Add("wechat_url",$Data);
	}
	
	if($Flag)
	{
		echo '<script language="javascript">alert("'.$_POST['submit_btn'].'成功");window.location="url.php";</script>';
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
    <link href='/static/member/css/material.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class=""><a href="index.php">图文消息管理</a></li>
        <li class="cur"><a href="url.php">自定义URL</a></li>
        <li class=""><a href="sysurl.php">系统URL查询</a></li>
      </ul>
    </div>
    <div id="url" class="r_con_wrap">
      <form id="add_form" class="add_form<?php echo empty($rsUrl)?"":" mod_form" ?>" method="post" action="url.php">
        <table border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td>名称
              <input type="text" name="Name" value="<?php echo empty($rsUrl["Url_Name"])?"":$rsUrl["Url_Name"] ?>" size="25" class="form_input" notnull /></td>
            <td>Url
              <input type="text" name="Value" value="<?php echo empty($rsUrl["Url_Value"])?"":$rsUrl["Url_Value"] ?>" size="40" class="form_input" notnull /></td>
            <td><input type="submit" class="submit" value="<?php echo empty($rsUrl)?"添加":"更新" ?>URL" name="submit_btn"></td>
          </tr>
        </table>
        <input type="hidden" name="UrlID" value="<?php echo $UrlID ?>" />
      </form>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="30%" nowrap="nowrap">名称</td>
            <td width="50%" nowrap="nowrap">Url</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
<?php $DB->getPage("wechat_url","*","where Users_ID='".$_SESSION["Users_ID"]."'",$pageSize=10);
while($rsUrl=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $rsUrl["Url_ID"] ?></td>
            <td><?php echo $rsUrl["Url_Name"] ?></td>
            <td><a href="<?php echo $rsUrl["Url_Value"] ?>" target="_blank"><?php echo $rsUrl["Url_Value"] ?></a></td>
            <td class="last" nowrap="nowrap"><a href="url.php?UrlID=<?php echo $rsUrl["Url_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="编辑" /></a> <a href="url.php?action=del&UrlID=<?php echo $rsUrl["Url_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
          </tr>
<?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>