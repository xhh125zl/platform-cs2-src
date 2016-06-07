<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("user_message","Users_ID='".$_SESSION["Users_ID"]."' and Message_ID=".$_GET["MessageID"]);
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
        <li class=""><a href="business_password.php">商家密码设置</a></li>
        <li class="cur"><a href="message.php">消息发布管理</a></li>
      </ul>
    </div>    
    <div id="user_message" class="r_con_wrap">
      <div class="control_btn"><a href="message_add.php" class="btn_green btn_w_120">添加内容</a></div>
      <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%"><strong>序号</strong></td>
            <td><strong>内容标题</strong></td>
            <td width="20%"><strong>时间</strong></td>
            <td width="15%" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("user_message","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Message_ID desc",$pageSize=10);
		  $i=1;
		  while($rsMessage=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsMessage["Message_Title"] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsMessage["Message_CreateTime"]) ?></td>
            <td nowrap="nowrap" class="last"><a href="message_edit.php?MessageID=<?php echo $rsMessage["Message_ID"] ?>"><img src="/static/member/images/ico/mod.gif" /></a> <a href="message.php?action=del&MessageID=<?php echo $rsMessage["Message_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" /></a></td>
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