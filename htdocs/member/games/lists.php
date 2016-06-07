<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
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
    <script type='text/javascript' src='/static/member/js/games.js'></script>
    <div class="r_nav">
      <ul>
		<li><a href="config.php">基本设置</a></li>
		<li class="cur"><a href="lists.php">游戏管理</a></li>
      </ul>
    </div>
    <div id="greeting_cards" class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
           <td width="8%">序号</td>
           <td width="18%">游戏名称</td>
           <td width="10%">游戏模式</td>
           <td width="20%">图片</td>
           <td width="20%">触发关键词</td>
           <td width="16%">游戏状态</td>
           <td width="8%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php
          $DB->getPage("games_model","*","order by Model_ID asc",10);
		  $lists = array();
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  $i=0;
		  foreach($lists as $k => $v){
			  $i++;
			  $games = $DB->GetRs("games","*","where Users_ID='".$_SESSION["Users_ID"]."' and Model_ID=".$v["Model_ID"]);
			  if($games){
				  $v["name"] = $games["Games_Name"];
				  $v["type"] = $games["Games_Pattern"]==0 ? "推广模式" : "积分模式";
				  $v["keywords"] = $games["Games_KeyWords"];
				  $v["status"] = $games["Games_IsClose"] ? '<font style="color:#ff0000">已关闭</font>' : '<font style="color:blue">已开启</font>';
				  $v["linkurl"]='edit_'.$v["Model_ID"].'.php?ModelID='.$v["Model_ID"];
			  }else{
				  $v["name"] = $v["Model_Name"];
				  $v["type"] = $v["keywords"] = $v["status"] = "";
				  $v["linkurl"]='add_'.$v["Model_ID"].'.php?ModelID='.$v["Model_ID"];
			  }			  
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $v["name"];?></td>
            <td nowrap="nowrap"><?php echo $v["type"];?></td>
            <td nowrap="nowrap"><img src="/static/api/games/images/cover_<?php echo $v["Model_ID"];?>.jpg" width="200" height="110" /></td>
            <td nowrap="nowrap">【 <?php echo $v["keywords"];?> 】</td>
            <td nowrap="nowrap"><?php echo $v["status"];?></td>
            <td class="last" nowrap="nowrap"><a href="<?php echo $v["linkurl"];?>" title="设置"><img src="/static/member/images/ico/set.gif" align="absmiddle" /></a></td>
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