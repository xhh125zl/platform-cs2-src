<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$_SESSION["Users_ID"]."'");
if(!$rsConfig){
	header("location:config.php");
}
$prizeid=empty($_REQUEST['prizeid'])?0:$_REQUEST['prizeid'];
$rsPrize=$DB->GetRs("hongbao_prize","*","where usersid='".$_SESSION["Users_ID"]."' and prizeid=".$prizeid);
if($_POST)
{
	$money = empty($_POST["money"]) ? 0 : floatval($_POST["money"]);
	if($money>0){
		if($money<1.00 || $money>200.00){
			echo '<script language="javascript">alert("红包金额必须大于0.99元小于200元");history.back();</script>';
		}
	}
	
	$amount = empty($_POST["amount"]) ? 0 : intval($_POST["amount"]);
	if($amount<=0){
		echo '<script language="javascript">alert("红包数量必须大于零");history.back();</script>';
	}
	
	$friend = empty($_POST["friend"]) ? 0 : intval($_POST["friend"]);
	if($money>0){
		if($friend<=0){
			echo '<script language="javascript">alert("所需好友数必须大于零");history.back();</script>';
		}
	}
	
	$Data=array(
		"money"=>$money,
		"amount"=>$amount,
		"friend"=>$friend
	);
	$Flag=$DB->Set("hongbao_prize",$Data,"where usersid='".$_SESSION["Users_ID"]."' and prizeid=".$prizeid);
	
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="prize.php";</script>';
	}else{
		echo '<script language="javascript">alert("修改失败");history.back();</script>';
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <script type='text/javascript' src='/static/member/js/hongbao.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
		<li class=""><a href="rules.php">活动规则</a></li>
        <li class="cur"><a href="prize.php">红包管理</a></li>
		<li class=""><a href="users.php">用户列表</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <form id="form_submit" class="r_con_form" method="post" action="prize_edit.php">
        <div class="rows">
          <label>红包金额</label>
          <span class="input">
          <input name="money" value="<?php echo $rsPrize["money"];?>" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">* (金额设置为零，即抢不到红包；若金额大于零，则 1.00元<=红包金额<=200.00元)</font></span>
          <div class="clear"></div>
        </div>
		
        <div class="rows">
          <label>红包数量</label>
          <span class="input">
          <input name="amount" value="<?php echo $rsPrize["amount"];?>" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">*</font> 必须大于零</span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>所需好友数</label>
          <span class="input">
          <input name="friend" value="<?php echo $rsPrize["friend"];?>" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">*</font> 即用户得到红包需借助好友帮助（红包金额大于零时，此项必须大于零）</span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="#" onclick="history.go(-1);" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="prizeid" value="<?php echo $prizeid;?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>