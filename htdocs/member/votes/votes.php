<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
if(isset($_GET["action"]))
{
	$action=empty($_GET["action"])?"":$_GET["action"];
	if($action=="del")
	{
		//开始事务定义
		$Flag=true;
		$msg="";
		mysql_query("begin");
		$Del=$DB->Del("votes_item","Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$_GET["VotesID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("votes_order","Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$_GET["VotesID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("votes","Users_ID='".$_SESSION["Users_ID"]."' and Votes_ID=".$_GET["VotesID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("wechat_keyword_reply","Users_ID='".$_SESSION["Users_ID"]."' and Reply_Display=0 and Reply_Table='votes' and Reply_TableID=".$_GET["VotesID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("wechat_material","Users_ID='".$_SESSION["Users_ID"]."' and Material_Display=0 and Material_Table='votes' and Material_TableID=".$_GET["VotesID"]);
		$Flag=$Flag&&$Del;
		if($Flag){
			mysql_query("commit");
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			mysql_query("roolback");
			echo '<script language="javascript">alert("保存失败");history.go(-1);</script>';
		}
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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/votes.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/votes.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="votes.php">投票管理</a></li>
      </ul>
    </div>
    <div id="reserve" class="r_con_wrap">
      <div class="control_btn"><a href="votes_add.php" class="btn_green btn_w_120">添加投票</a></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="9%">序号</td>
            <td width="20%">投票名称</td>
            <td width="12%">触发关键词</td>
            <td width="12%">背景颜色</td>
            <td width="12%">投票形式</td>
            <td width="15%">起止时间</td>
            <td width="10%">投票选项</td>
            <td width="10%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("votes","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Votes_ID asc",$pageSize=10);
		  $i=1;
		  while($rsVotes=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td nowrap="nowrap"><?php echo $rsVotes["Votes_Title"] ?></td>
            <td nowrap="nowrap">【<?php echo $rsVotes["Votes_Keyword"] ?>】</td>
            <td nowrap="nowrap"><?php echo $rsVotes["Votes_BgColor"] ?></td>
            <td nowrap="nowrap"><?php echo $rsVotes["Votes_Pattern"]==0 ? "图片" : "文字" ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsVotes["Votes_StartTime"]).'<br /> ~ <br />'.date("Y-m-d H:i:s",$rsVotes["Votes_EndTime"]) ?></td>
            <td nowrap="nowrap"><a href="items.php?VotesID=<?php echo $rsVotes["Votes_ID"] ?>">管理</a></td>
            <td nowrap="nowrap" class="last">
            	<a href="orders.php?VotesID=<?php echo $rsVotes["Votes_ID"] ?>"><img src="/static/member/images/ico/statistics.png" align="absmiddle" alt="数据统计" /></a> 
                <a href="votes_edit.php?VotesID=<?php echo $rsVotes["Votes_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> 
                <a href="?action=del&VotesID=<?php echo $rsVotes["Votes_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a>
            </td>
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