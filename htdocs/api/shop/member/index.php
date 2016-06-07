<?php
require_once('global.php');

$Status=empty($_GET["Status"])?0:$_GET["Status"];

$rsUser=$DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]);
if(empty($rsUser["User_Profile"])){
	$_SESSION[$UsersID."HTTP_REFERER"] = "/api/".$UsersID."/shop/member/";
	header("location:/api/".$UsersID."/user/complete/");
	exit;
}

$num0 = $num1 = $num2 = $num3 = 0;
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=0");
$num0 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=1");
$num1 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=2");
$num2 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=3");
$num3 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=4");
$num4 = $r["num"];
$rsConfig1=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$LevelName = '普通会员';
if(!empty($rsConfig1["UserLevel"])){
	$level_arr = json_decode($rsConfig1["UserLevel"],true);
	if(!empty($level_arr[$rsUser["User_Level"]])){
		$LevelName = $level_arr[$rsUser["User_Level"]]["Name"];
	}
}

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>个人中心</title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="./static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/shop/skin/default/css/member.css?t=<?php echo time();?>" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
	<script type='text/javascript' src='/static/api/js/global.js'></script>
	<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script language="javascript">
		$(document).ready(shop_obj.page_init);
	</script>
    
</head>

<body>
<div class="wrap">
	<div class="container">
    <h4 class="row page-title">个人中心</h4>
    </div>
    
    <div class="contaienr">
    	<div id="member_header">
  
   <div class="header_r">会员级别: <font style="font-weight:bold"><?=$LevelName?></font> </div>
   <div class="header_l">
    <span class="img"><img src="<?=$rsUser['User_HeadImg']?>"></span>
    <span class="nickname">
	<?php 
		if(strlen($rsUser['User_NickName']) >0){
			echo $rsUser['User_NickName'];	
		}else{
			echo '暂无';
		}
	?></span>
	
	<div class="clearfix"></div>
   </div>   
   <div class="clearfix"></div>
   
   
   
  </div>
  	
    	
    </div>
    
    <div id="member_orders">
   <a href="/api/<?=$UsersID?>/shop/member/status/0/"><font style="font-size:16px; font-weight:bold"><?=$num0?></font><br>待确认</a>
   <a href="/api/<?=$UsersID?>/shop/member/status/1/"><font style="font-size:16px; font-weight:bold"><?=$num1?></font><br>待付款</a>
   <a href="/api/<?=$UsersID?>/shop/member/status/2/"><font style="font-size:16px; font-weight:bold"><?=$num2?></font><br>已付款</a>
   <a href="/api/<?=$UsersID?>/shop/member/status/3/"><font style="font-size:16px; font-weight:bold"><?=$num3?></font><br>已发货</a>
   <a href="/api/<?=$UsersID?>/shop/member/status/4/"><font style="font-size:16px; font-weight:bold"><?=$num4?></font><br>已完成</a>
  <div class="clearfix"></div>
  </div>
  
  <div class="list_item">
	<div class="dline"></div>    
	<a href="/api/<?=$UsersID?>/user/money/" class="item item_1"><span class="ico"></span> 我的余额：<?=$rsUser['User_Money']?> 元<span class="jt"></span></a>
	<a href="/api/<?=$UsersID?>/user/my/address/" class="item item_3"><span class="ico"></span>收货地址管理<span class="jt"></span></a>
    <a href="/api/<?=$UsersID?>/shop/member/favourite/" class="item item_6"><span class="ico"></span>我的收藏夹<span class="jt"></span></a>
    <a href="/api/<?=$UsersID?>/shop/member/backup/status/0/" class="item item_7"><span class="ico"></span>我的退货单<span class="jt"></span></a>
    <?php if($rsUser['Is_Distribute'] == 1):?>
    <?php endif;?> 
	
  </div>
</div>

 <?php
 	require_once('../skin/distribute_footer.php');
 ?>
</body>
</html>


