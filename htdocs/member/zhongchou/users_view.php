<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig = $DB->GetRs("zhongchou_config","*","where usersid='".$_SESSION["Users_ID"]."'");
if(!$rsConfig){
	header("location:config.php");
}
$orderid=empty($_REQUEST['orderid'])?0:$_REQUEST['orderid'];
$orderinfo=$DB->GetRs("user_order","*","where Users_ID='".$_SESSION["Users_ID"]."' and Order_ID=".$orderid);
if(!$orderinfo){
	echo '<script language="javascript">alert("该订单不存在！");window.location="project.php";</script>';
}
$projectid=empty($_REQUEST['projectid'])?0:$_REQUEST['projectid'];
$item=$DB->GetRs("zhongchou_project","*","where usersid='".$_SESSION["Users_ID"]."' and itemid=".$projectid);
if(!$item){
	echo '<script language="javascript">alert("该项目不存在！");window.location="project.php";</script>';
}
$userinfo = $DB->GetRs("user","User_HeadImg,User_NickName","where User_ID=".$orderinfo["User_ID"]);
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

<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
	  <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="project.php">项目管理</a></li>
      </ul>
    </div>
    <div id="reserve" class="r_con_wrap">
	       <div class="r_con_form">
				<div class="rows">
					<label>项目</label>
					<span class="input"><span class="tips"><?php echo $item['title'];?></span></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>支持金额</label>
					<span class="input"><span class="tips">￥<?php echo $orderinfo['Order_TotalPrice'];?></span></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>支持人</label>
					<span class="input"><span class="tips"><img src="<?php echo $userinfo["User_HeadImg"];?>" width="60" /><br /><?php echo $userinfo["User_NickName"];?></span></span>
					<div class="clear"></div>
				</div>
                <div class="rows">
					<label>回报</label>
					<span class="input"><span class="tips"><font style="color:#FF6600"><?php echo $orderinfo['Order_CartList'] ? $orderinfo['Order_CartList'] : '无私奉献';?></font></span></span>
					<div class="clear"></div>
				</div>
                <?php if($orderinfo['Order_CartList']){?>
                <div class="rows">
					<label>收货人</label>
					<span class="input"><span class="tips"><font style="color:blue"><?php echo $orderinfo['Address_Name'];?> (<?php echo $orderinfo['Address_Mobile'];?>)</font></span></span>
					<div class="clear"></div>
				</div>
                <div class="rows">
					<label>收获地址</label>
					<span class="input"><span class="tips"><font style="color:blue"><?php echo $orderinfo['Address_Province'];?> <?php echo $orderinfo['Address_City'];?> <?php echo $orderinfo['Address_Area'];?><br /><?php echo $orderinfo['Address_Detailed'];?></font></span></span>
					<div class="clear"></div>
				</div>
                <div class="rows">
					<label>备注</label>
					<span class="input"><span class="tips"><font style="color:blue"><?php echo $orderinfo['Order_Remark'];?></font></span></span>
					<div class="clear"></div>
				</div>
                <?php }?>
				<div class="rows">
					<label>时间</label>
					<span class="input"><span class="tips"><?php echo date("Y-m-d H:i:s",$orderinfo['Order_CreateTime']);?></span></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label></label>
					<span class="input"><a href="#" onclick="history.go(-1);" class="btn_cancel">返 回</a></span>
				<div class="clear"></div>
			</div>
		</div>
	</div>
  </div>
</div>
</body>
</html>