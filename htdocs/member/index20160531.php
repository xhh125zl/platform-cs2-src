<?php
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="logout")
	{
		session_unset();
		header("location:/member/login.php");
	}
}
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$RIGHT = json_decode($rsUsers["Users_Right"],true);
//将超过自动收货时限的订单进行自动收货
$ids = Order::get_expire_order($rsConfig);
Order::observe(new OrderObserver());

foreach($ids as $key=>$Order_ID){
	$order = Order::find($Order_ID);
	$order->confirmReceive();
}
$rsUsers = $DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$Users_Right = json_decode($rsUsers['Users_Right'],true);
foreach($rmenu as $key=>$value){
	if (array_key_exists($key,$Users_Right)){
	foreach($value as $k=>$v){
		$Users_Right[$key][] = $k;
	}
	}
}
$myrmenu = array();
foreach($sysrmenu as $key=>$value){
	if (array_key_exists($key,$Users_Right)){
		foreach($value as $k=>$v){
			if($key == 'weicuxiao'){
				$cux = 0;
				$cux = $k == 'weicuxiao'?1:0;
				if($k != 'weicuxiao' && in_array($k,$Users_Right[$key])){			
				$cux = 1;
				}
			}else{
				$cux = 1;
			}
		if($cux == 1){
					$myrmenu[$key][$k] = $v;					
				}				
			}
		}
	}
/*edit in 20160326*/	
$paixarry = Array("buy_record","weicuxiao");
$newpaiarry = array();
	foreach($paixarry as $val){
		$newpaiarry[$val] = $rmenu[$val];
		unset($rmenu[$val]);
	}
unset($newpaiarry["weicuxiao"]);
$my_right = array_merge($rmenu,$myrmenu);
$my_right = array_merge($my_right,$newpaiarry);

if(isset($_SESSION['user_type'])){
	$my_rightcopy = array();
	$my_rightcopy = $my_right;
	unset($my_right);
	$my_right = array();
	$role = $DB->GetRs("users_roles","*","where id='".$_SESSION["role_id"]."'");
	$employee_right = json_decode($role['role_right'],true)?json_decode($role['role_right'],true):array();
	$newemparray = array();
	foreach($employee_right as $key=>$val){
		foreach($val as $k=>$v){
		if($val[0] == $key){
			$newemparray[$key][$k] = $v;
		}else{
			$newemparray[$key][0] = $key;
			$newemparray[$key][] = $v;
		}
		}
	}
	foreach ($my_rightcopy as $key=>$val){
		if (array_key_exists($key,$newemparray)){
		foreach($val as $k=>$v){
		if(in_array($k,$newemparray[$key])){
			$my_right[$key][$k] = $v;
		}			
		}
	}
	}	
}
$json_right = json_encode($my_right,JSON_UNESCAPED_UNICODE);
$myArray = Array("basic","product","article","order","backup","financial");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SiteName;?></title>
<link href="/static/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="/static/style.css" rel="stylesheet" type="text/css" />

<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js?t=<?php echo time();?>'></script>
</head>

<body>
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.mousewheel.js'></script> 
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.jscrollpane.js'></script>
<link href='/static/js/plugin/jscrollpane/jquery.jscrollpane.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/main.js?t=<?php echo time();?>'></script> 
<script language="javascript">$(document).ready(main_obj.page_init); window.onresize=main_obj.page_init;</script>
<div id="header">
  <div class="logo"><img src="<?php echo $SiteLogo ? $SiteLogo : '/static/member/images/main/header_logo.png';?>" /></div>
  <ul>
    <?php foreach($topmeu as $key=>$value){?>	
	<?php foreach($value as $k=>$v){
		switch($v){
			case '我的帐号':
			$n = 0;
			break;
			case '修改密码':
			$n = 6;
			break;
			case '操作指南':
			$n = 1;
			break;
			case '素材库':
			$n = 2;
			break;
			case '推广技巧':
			$n = 4;
			break;
			case '员工管理':
			$n = 4;
			break;
		}
		?>
    <li class="ico-<?=$n?>"><a href="/member/<?=$key?>/<?=$k?>.php" target="iframe"><?=$v?></a></li>
	<?php }}?>    
    <li class="ico-5"><a id='logout' href="?action=logout">退出登录</a></li>
	<?php if(isset($_SESSION['user_type'])){?>
    <li class=""><b style='color:#FFF;'>员工在线:<?php echo $_SESSION['employee_name'];?></b></li>
	<?php }?>
  </ul>
</div>
<div id="main">
  <div class="menu">
  <!--edit in 20160326-->
    <dl>
	<?php foreach($my_right as $key=>$value){?>	
	<?php if(!array_key_exists($key,$topmeurkey)){ ?>
	<?php foreach($value as $k=>$v){?>
	<?php if($k == $key){?>
	<dt group="<?=$key?>" class=""><?=$v?></dt>
	<dd>
	<?php }else{ ?>
	<?php if($key == 'weixin' || $key == 'buy_record'){?>
	<?php if(!array_key_exists(substr($key,0,3).'_'.$k,$topmeurv)){ ?>
      	<div><a href="/member/wechat/<?=$k?>.php" target="iframe"><?=$v?></a></div>	
	<?php }}else if(in_array($key,$myArray)){?>
      	<div><a href="/member/shop/<?=$k?>.php" target="iframe"><?=$v?></a></div>
	<?php }else if($key == 'weicuxiao'){?>
      	<?php if($k == 'sctrach'){?>
		<div><a href="/member/scratch/config.php" target="iframe"><?=$v?></a></div>
		<?php }else{?>
		<div><a href="/member/<?=$k?>/config.php" target="iframe"><?=$v?></a></div>
		<?php }?>
	<?php }else{ ?>
		<div><a href="/member/<?=$key?>/<?=$k?>.php" target="iframe"><?=$v?></a></div>
	<?php }}}?>
      </dd>
	  <?php }}?>
	  <dt class="" group="buy_record" style="display: block;">PC站点管理</dt>
		<dd style="display: none;">
			<div style="display:block" class="">
				<a target="iframe" href="http://oldb2c.cc/pc.php?m=member&amp;c=pc_diy&amp;a=index_block&amp;UsersID=yd0tcni067" style="display: inline;">首页管理</a>
			</div>
	   </dd>
    </dl>
  </div>
  <div class="iframe">
    <iframe src="wechat/account.php" name="iframe" frameborder="0" scrolling="auto"></iframe>
  </div>
  <div class="clear"></div>
</div>
<div id="footer">
  <div class="oem"><?php echo $Copyright;?></div>
</div>
<script type="text/javascript">
	var right = <?php echo $json_right;?>;
	var myArray = new Array("basic","product","article","order","backup","financial");  
	if(right == ''){		
		$("a").hide();
		$("a").parent("dt").hide();
		$("a").parent("div").hide();
		$("a").parent("div").parent("dd").hide();
		$("a").parent("div").parent("dd").prev("dt").hide();
		$("#logout").show();
	}else{		
		$("a").hide();
		$("a").parent("li").hide();
		$("a").parent("dt").hide();
		$("a").parent("div").hide();
		$("a").parent("div").parent("dd").hide();
		$("a").parent("div").parent("dd").prev("dt").hide();
		$("#logout").show();
		$.each(right,function(key,val){
			$.each(val,function(k,v){
				if(key == 'weixin' || key == 'buy_record'){
					str = "'\/member\/wechat\/"+k+".php'";
				}else if($.inArray(key,myArray) != -1){
					str = "'\/member\/shop\/"+k+".php'";
				}else if(key == 'weicuxiao'){
					(k == 'sctrach') ? str = "'\/member\/scratch\/config.php'" : str = "'\/member\/"+k+"\/config.php'"; 					
				}else{
					str = "'\/member\/"+key+"\/"+k+".php'";
				}				
				$("a[href="+str+"]").show();
				$("a[href="+str+"]").parent("li").show();
				$("a[href="+str+"]").parent("dt").show();
				$("a[href="+str+"]").parent("div").show();
				$("a[href="+str+"]").parent("div").parent("dd").show();
				$("a[href="+str+"]").parent("div").parent("dd").prev("dt").show();
			});
		});
		$("#logout").parent('li').show();
	}
	$('dt').next('dd').hide();	
</script>
</body>
</html>