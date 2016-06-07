<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsConfig=$DB->GetRs("shop_config","Skin_ID","where Users_ID='".$_SESSION["Users_ID"]."'");
if(isset($_GET["action"]))
{
	if($_GET["action"]=="set")
	{		
		//开始事务定义
		$flag=true;
		mysql_query("begin");
		$Data=array(
			"Skin_ID"=>$_GET["Skin_ID"]
		);
		$Set=$DB->Set("shop_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
		$flag=$flag&&$Set;
		$rsHome=$DB->GetRs("shop_skin","*","where Skin_ID=".$_GET["Skin_ID"]);
		//判断shop_home表中是否有记录
		$rsSkin=$DB->GetRs("shop_home","*","where Users_ID='".$_SESSION["Users_ID"]."' and Skin_ID=".$_GET["Skin_ID"]);
		if(empty($rsSkin)){
			$Data=array(
				"Home_Json"=>$rsHome["Skin_Json"],
				"Skin_ID"=>$_GET["Skin_ID"],
				"Users_ID"=>$_SESSION["Users_ID"]
			);
			$Add=$DB->Add("shop_home",$Data);
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
<title></title>
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
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="skin.php">风格设置</a></li>
        <li class=""><a href="home.php">首页设置</a></li>
        <li><a href="menu_config.php">菜单配置</a></li>
      </ul>
    </div>
    <script language="javascript">$(document).ready(shop_obj.skin_init);</script>
  
    <div id="skin" class="r_con_wrap">
      <ul>
        
        <?php 
		$i=0;
		$DB->get("shop_skin","*","where Skin_Status=1 order by Skin_Index asc,Skin_ID asc");
while($rsSkin=$DB->fetch_assoc()){
	echo '<li class="'.($rsConfig['Skin_ID']==$rsSkin['Skin_ID']?"cur":"").'">
            <div class="item" title="点击选择微官网风格" onClick="SelectSkinID('.$rsSkin['Skin_ID'].');">
              <div class="img"><img src="/static/member/images/shop/skin/'.$rsSkin["Skin_ID"].'.jpg" /></div>
              <div class="title">'.$rsSkin['Skin_Name'].'</div>
            </div>
          </li>';
}?>
      </ul>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>