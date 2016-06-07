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
        <li class="cur"><a href="announce.php">公告列表</a></li>
      </ul>
    </div>
    <div id="reply_keyword" class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">所属分类</td>
            <td nowrap="nowrap">标题</td>
            <td width="15%" nowrap="nowrap">发布时间</td>
            <td width="10%" nowrap="nowrap" class="last">浏览</td>
          </tr>
        </thead>
        <tbody>
        <?php
		$i=1;
		$list=array();
		$DB->getPage("announce","*","where Announce_Status=1 order by Announce_CreateTime desc",$pageSize=10);
		while($r=$DB->fetch_assoc()){
			$list[] = $r;
		}
		foreach($list as $k=>$v){
			$item = $DB->GetRs("announce_category","*","where Category_ID=".$v["Category_ID"]);
			$v["Category_Name"] = $item["Category_Name"];
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $i; ?></td>
            <td nowrap="nowrap"><?php echo $v["Category_Name"]; ?></td>
            <td nowrap="nowrap"><?php echo $v["Announce_Title"]; ?></td>
            <td><?php echo date("Y-m-d H:i:s",$v["Announce_CreateTime"]); ?></td>
            <td class="last" nowrap="nowrap"><a href="announce_view.php?AnnounceID=<?php echo $v["Announce_ID"]; ?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" alt="查看" /></a></td>
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