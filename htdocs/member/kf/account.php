<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("kf_account","Users_ID='".$_SESSION["Users_ID"]."' and Account_ID=".$_GET["AccountID"]);
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
$count = $DB->GetRs("kf_account","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."'");
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/kf.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="account.php">坐席管理</a></li>
        <li class=""><a href="config.php">网页客服设置</a></li>
        <li class=""><a href="/kf/admin/login.php" target="_blank">网页客服系统</a></li>
      </ul>
    </div>
    <div id="chat" class="r_con_wrap">
    <?php if($count["num"]==0){?>
    <div class="control_btn"><a href="account_add.php" class="btn_green btn_w_120">添加客服账号</a></div>
    <?php }?>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
           <tr>
              <td width="10%">序号</td>
              <td width="40%">客服账号</td>
              <td width="30%">创建时间</td>
              <td width="20%" class="last">操作</td>
           </tr>
        </thead>
        <tbody> 
		<?php
		 $DB->get("kf_account","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Account_ID asc");
		 $i=0;
		 while($rsAccount=$DB->fetch_assoc()){
			 $i++;
		?>
        <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';">
           <td align="center"><?php echo $i; ?></td>
           <td align="center"><?php echo $rsAccount["Account_Name"]; ?></td>
           <td align="center"><?php echo date("Y-m-d H:i:s",$rsAccount["Account_CreateTime"]);?></td>
           <td align="center"><a href="account_edit.php?AccountID=<?php echo $rsAccount["Account_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="account.php?action=del&AccountID=<?php echo $rsAccount["Account_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
        </tr>
		<?php }?>
      </tbody>
    </table>
    </div>
  </div>
</div>
</body>
</html>