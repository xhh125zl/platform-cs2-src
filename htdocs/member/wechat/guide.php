<?php

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
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
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="guide.php">操作指南列表</a></li>
      </ul>
    </div>
    <div id="reply_keyword" class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td nowrap="nowrap">标题</td>
            <td width="15%" nowrap="nowrap">发布时间</td>
            <td width="10%" nowrap="nowrap" class="last">浏览</td>
          </tr>
        </thead>
        <tbody>
        <?php
		$i=1;
		$list=array();
		$DB->getPage("guide","*","where Guide_Status=1 order by Guide_CreateTime desc",$pageSize=10);
		while($v=$DB->fetch_assoc()){
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $i; ?></td>
            <td align="left" style="text-align:left; padding:0px 15px;"><?php echo $v["Guide_Title"]; ?></td>
            <td><?php echo date("Y-m-d H:i:s",$v["Guide_CreateTime"]); ?></td>
            <td class="last" nowrap="nowrap"><a href="guide_view.php?GuideID=<?php echo $v["Guide_ID"]; ?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" alt="查看" /></a></td>
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