<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("wechat_keyword_reply","Users_ID='".$_SESSION["Users_ID"]."' and Reply_ID=".$_GET["ReplyID"]);
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
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/wechat.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="/member/wechat/attention_reply.php">首次关注设置</a></li>
        <li class=""><a href="/member/wechat/menu.php">自定义菜单设置</a></li>
        <li class="cur"><a href="/member/wechat/keyword_reply.php">关键词回复</a></li>
        <li class=""><a href="/member/wechat/token_set.php">微信接口配置</a></li>
      </ul>
    </div>
    <div id="reply_keyword" class="r_con_wrap">
      <div class="control_btn"><a href="keyword_edit.php" class="btn_green btn_w_120">添加关键字</a></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="25%" nowrap="nowrap">触发关键词</td>
            <td width="15%" nowrap="nowrap">匹配模式</td>
            <td width="35%" nowrap="nowrap">回复内容</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php $i=1;
$DB->getPage("wechat_keyword_reply","*","where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Display=1 order by Reply_ID desc",$pageSize=10);
while($rsReply=$DB->fetch_assoc()){
	$Reply_Keywords=explode("\n",$rsReply["Reply_Keywords"]);
	$Keywords="";
	foreach($Reply_Keywords as $value)
	{
		$Keywords.='【'.$value.'】';
	}
?>
          <tr>
            <td nowrap="nowrap"><?php echo $i; ?></td>
            <td nowrap="nowrap"><?php echo $Keywords; ?></td>
            <td nowrap="nowrap"><?php echo $rsReply["Reply_PatternMethod"]?"模糊匹配":"精确匹配"; ?></td>
            <td><?php echo $rsReply["Reply_TextContents"]?$rsReply["Reply_TextContents"]:"&nbsp;"; ?></td>
            <td class="last" nowrap="nowrap"><a href="keyword_edit.php?ReplyID=<?php echo $rsReply["Reply_ID"]; ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="?action=del&ReplyID=<?php echo $rsReply["Reply_ID"]; ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
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