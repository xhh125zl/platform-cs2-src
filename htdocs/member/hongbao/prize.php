<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$_SESSION["Users_ID"]."'");
if(!$rsConfig){
	header("location:config.php");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("hongbao_prize","usersid='".$_SESSION["Users_ID"]."' and prizeid=".$_GET["prizeid"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
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
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
		<li class=""><a href="rules.php">活动规则</a></li>
        <li class="cur"><a href="prize.php">红包管理</a></li>
		<li class=""><a href="users.php">用户列表</a></li>
      </ul>
    </div>
    <div id="column" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
      <div class="control_btn"><a href="prize_add.php" class="btn_green btn_w_120">添加红包</a></div>
      <div style="background:#F7F7F7; border:1px #dddddd solid; height:40px; line-height:40px; font-size:12px; margin:10px 0px; padding-left:15px; color:#ff0000">红包抢中几率计算规则： 某金额的红包对应的数量 / 红包总数量。例如金额0元的红包50个，金额1元的红包10个，金额2元的红包40个，则抢到1元红包的几率为10/100=10%,抢到2元的红包的几率为40/100=40%</div>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
                <tr>
                  <td width="10%" align="center"><strong>序号</strong></td>
                  <td width="20%" align="center"><strong>红包金额</strong></td>
                  <td width="20%" align="center"><strong>红包数量</strong></td>
                  <td width="20%" align="center"><strong>剩余数量</strong></td>
                  <td width="20%" align="center"><strong>所需好友数</strong></td>
                  <td width="10%" align="center"><strong>操作</strong></td>
                </tr>
				<?php
				$DB->get("hongbao_prize","*","where usersid='".$_SESSION["Users_ID"]."' order by addtime asc");
				$i = 0;
				while($r=$DB->fetch_assoc()){
					$i++;
					$r["shengyu"] = intval($r["amount"])-intval($r["expire"]);
				?>
                <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';">
                  <td align="center"><?php echo $i; ?></td>
                  <td align="center"><?php echo $r["money"]; ?></td>
                  <td align="center"><?php echo $r["amount"]; ?></td>
                  <td align="center"><?php echo $r["shengyu"]; ?></td>
                  <td align="center"><?php echo $r["friend"]; ?></td>
                  <td align="center"><a href="prize_edit.php?prizeid=<?php echo $r["prizeid"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="?action=del&prizeid=<?php echo $r["prizeid"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
                </tr>
                <?php }?>
              </table>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>