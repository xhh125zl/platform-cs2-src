<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
if(isset($_GET["action"])){
	$action=empty($_GET["action"])?"":$_GET["action"];
	if($action=="del"){
		//开始事务定义
		$Flag=true;
		$msg="";
		mysql_query("begin");
		$Del=$DB->Del("battle","Users_ID='".$_SESSION["Users_ID"]."' and Battle_ID=".$_GET["BattleID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("wechat_keyword_reply","Users_ID='".$_SESSION["Users_ID"]."' and Reply_Display=0 and Reply_Table='battle' and Reply_TableID=".$_GET["BattleID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("wechat_material","Users_ID='".$_SESSION["Users_ID"]."' and Material_Display=0 and Material_Table='battle' and Material_TableID=".$_GET["BattleID"]);
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
    <script type='text/javascript' src='/static/member/js/battle.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="exam.php">题库管理</a></li>
        <li class="cur"><a href="battle.php">活动管理</a></li>
        <li class=""><a href="battle_user.php">用户列表</a></li>
      </ul>
    </div>
    <div id="battle" class="r_con_wrap">
      <div class="control_btn"><a href="battle_edit.php" class="btn_green btn_w_120">添加活动</a></div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="32%" nowrap="nowrap">活动名称</td>
            <td width="25%" nowrap="nowrap">触发关键词</td>
            <td width="16%" nowrap="nowrap">截止时间</td>
            <td width="10%" nowrap="nowrap">参与用户</td>
            <td width="12%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("battle","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Battle_ID asc",$pageSize=10);
		  $i=1;
		  while($rsBattle=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsBattle["Battle_Title"] ?></td>
            <td nowrap="nowrap">【<?php echo str_replace("\n","】【",$rsBattle["Battle_Keywords"]) ?>】</td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBattle["Battle_StartTime"])."<br>~<br>".date("Y-m-d H:i:s",$rsBattle["Battle_EndTime"]) ?></td>
            <td nowrap="nowrap"><a href="battle_user.php?BattleID=<?php echo $rsBattle["Battle_ID"] ?>">查看</a></td>
            <td class="last" nowrap="nowrap"><a href="battle_edit.php?BattleID=<?php echo $rsBattle["Battle_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="battle.php?action=del&BattleID=<?php echo $rsBattle["Battle_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
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