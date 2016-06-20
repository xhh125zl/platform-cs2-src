<?php


if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$UsersID = $_SESSION['Users_ID'];

//如果设置有action

if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		
		$Flag=$DB->Del("kanjia","Users_ID='".$_SESSION["Users_ID"]."' and Kanjia_ID=".$_GET["KanjiaID"]);
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


//获取活动列表
$condition = "where Users_ID = '".$UsersID."'";

$rsKanjia = $DB->getPage("kanjia","*",$condition,10);
$kanjia_list = $DB->toArray($rsKanjia);



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
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>

</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <script type='text/javascript' src='/static/member/js/zhuli.js'></script>
    <div class="r_nav">
      <ul>
        <li ><a href="config.php">基本设置</a></li>
        <li class="cur" ><a href="activity_list.php">活动管理</a></li>
        <li ><a href="orders.php">订单管理</a></li>
        <li ><a href="commit.php">评论管理</a></li>
      </ul>
    </div>
    <div class="r_con_wrap" id="kanjia_list">
	<div class="control_btn">
    	<a href="activity_add.php" class="btn_green btn_w_120">新建活动</a>
    </div>
	<!-- 活动列表开始 -->
    <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="10%" nowrap="nowrap">活动名称</td>
            <td width="20%" nowrap="nowrap">活动产品</td>
            <td width="30%" nowrap="nowrap">活动时间</td>
            <td width="20%" nowrap="nowrap">随机区间</td>
            <td width="*" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php foreach($kanjia_list as $key=>$item):?>
          <tr>
          	<td><?=$item['Kanjia_ID']?></td>
            <td><?=$item['Kanjia_Name']?></td>
            <td><?=$item['Product_Name']?></td>
            <td><?=date('Y/m/d H:i:s',$item['Fromtime'])?>-<?=date('Y/m/d H:i:s',$item['Totime'])?></td>
            <td><?=$item['Beginnum']?>-<?=$item['Endnum']?></td>
           <td class="last" nowrap="nowrap">
           <a href="activity_edit.php?KanjiaID=<?=$item['Kanjia_ID']?>"><img src="/static/member/images/ico/mod.gif" alt="修改" align="absmiddle"></a> 
           <a href="activity_list.php?action=del&KanjiaID=<?=$item['Kanjia_ID']?>" onclick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" alt="删除" align="absmiddle"></a></td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
    <!-- 活动列表结束 -->
    
     <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>