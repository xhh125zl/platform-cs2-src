<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$TradeID=empty($_GET['TradeID'])?0:$_GET['TradeID'];
$rsConfig=$DB->GetRs("web_config","Skin_ID,Trade_ID","where Users_ID='".$_SESSION["Users_ID"]."'");
//$TradeID=empty($rsConfig['Trade_ID'])?0:$rsConfig['Trade_ID'];
//$TradeID=empty($_GET['TradeID'])?$TradeID:$_GET['TradeID'];
if(isset($_GET["action"]))
{
	if($_GET["action"]=="set")
	{		
		//开始事务定义
		$flag=true;
		mysql_query("begin");
		$Data=array(
			"Skin_ID"=>$_GET["Skin_ID"],
			"Trade_ID"=>$_GET["Trade_ID"]
		);
		$Set=$DB->Set("web_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
		$flag=$flag&&$Set;
		//判断web_home表中是否有记录
		$rsSkin=$DB->GetRs("web_home","*","where Users_ID='".$_SESSION["Users_ID"]."' and Skin_ID=".$_GET["Skin_ID"]);
		if(empty($rsSkin)){
			$rsHome=$DB->GetRs("web_skin","Skin_Json","where Skin_ID=".$_GET["Skin_ID"]);
			$Data=array(
				"Skin_ID"=>$_GET["Skin_ID"],
				"Home_Json"=>$rsHome["Skin_Json"],
				"Users_ID"=>$_SESSION["Users_ID"]
			);
			$Add=$DB->Add("web_home",$Data);
			$flag=$flag&&$Add;
		}
		
		if($flag)
		{
			//执行事务
			mysql_query("commit");
			echo '<script language="javascript">alert("选择成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			//失败回滚
			mysql_query("roolback");
			echo '<script language="javascript">alert("操作失败");history.back();</script>';
		}
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
<SCRIPT type=text/javascript>
function SelectSkinID(Skin_ID,Trade_ID){
	if(confirm("您确定要选择此风格吗？")){
		location.href="skin.php?action=set&Skin_ID="+Skin_ID+"&Trade_ID="+Trade_ID;
	}
}
</SCRIPT>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/web.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/web.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
        <li class=""><a href="column.php">栏目管理</a></li>
        <li class=""><a href="article.php">内容管理</a></li>
        <li class=""><a href="lbs.php">一键导航</a></li>
      </ul>
    </div>
    <script language="javascript">//$(document).ready(web_obj.skin_init);</script>
    <div id="skin" class="r_con_wrap">
      <div class="trade"> <a href="?TradeID=0" class="<?php echo $TradeID==0?"cur":"" ?>">全部</a> <a href="?TradeID=1" class="<?php echo $TradeID==1?"cur":"" ?>">餐饮</a> <a href="?TradeID=2" class="<?php echo $TradeID==2?"cur":"" ?>">旅游</a> <a href="?TradeID=3" class="<?php echo $TradeID==3?"cur":"" ?>">婚庆</a> <a href="?TradeID=4" class="<?php echo $TradeID==4?"cur":"" ?>">教育</a> <a href="?TradeID=5" class="<?php echo $TradeID==5?"cur":"" ?>">汽车</a> <a href="?TradeID=6" class="<?php echo $TradeID==6?"cur":"" ?>">酒店</a> </div>
      <div class="list">
        <ul>
          <?php
		  if($TradeID>0){
			  $DB->get("web_skin","*","where Skin_Status=1 and Trade_ID=".$TradeID." order by Skin_Index asc");
		  }else{
		  	  $DB->get("web_skin","*","where Skin_Status=1 order by Skin_Index asc");
		  }
		  $i=0;
		  while($rsSkin=$DB->fetch_assoc()){
			echo '<li class="'.($rsConfig['Skin_ID']==$rsSkin['Skin_ID']?"cur":"").'">
					<div class="item" title="点击选择微官网风格" onClick="SelectSkinID('.$rsSkin['Skin_ID'].','.$TradeID.');">
					  <div class="img"><img src="'.$rsSkin['Skin_ImgPath'].'" /></div>
					  <div class="title">'.($rsSkin['Skin_ID']==1 ? 'Diy个性首页' : '风格'.$i).'</div>
					</div>
				  </li>';
			$i++;
		  }
		?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>