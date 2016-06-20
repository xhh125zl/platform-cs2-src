<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

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
$expiredate = time();
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if(isset($rsUsers["Users_ExpireDate"]) && $rsUsers["Users_ExpireDate"]>0){
	if(intval($rsUsers["Users_ExpireDate"])< $expiredate){
		session_unset();
		echo '<script>alert("您的账号已过期，请尽快延期");</script>';
		header("location:/member/login.php");
		exit;
	}
}
$RIGHT = json_decode($rsUsers["Users_Right"],true);

//将超过自动收货时限的订单进行自动收货
$ids = Order::get_expire_order($rsConfig);
Order::observe(new OrderObserver());

foreach($ids as $key=>$Order_ID){
	$order = Order::find($Order_ID);
	$order->confirmReceive();
}

//权限验证
$right = array();
if(isset($_SESSION['user_type'])){
	
	//var_dump($my_users_right);

	//员工权限
	$role = $DB->GetRs("users_roles","*","where id='".$_SESSION["role_id"]."'");
	$employee_right = json_decode($role['role_right'],true)?json_decode($role['role_right'],true):array();

	foreach ($employee_right as $key=>$val){
		foreach($val as $k=>$v){
			$right[$key][$v] = $file_all[$key][$v];
			if(in_array($key,array('kanjia','zhuli','hongbao'))){
				$right[$key]['config'] = $file_all[$key][$v];
			}
			if(in_array($key,array('zhongchou','games'))){
				$right[$key] = $file_all[$key];
			}
		}
	}
	$rights = !empty($right)?$right:array();
	//var_dump($right);
}else{
		//商家权限
	$rsUsers = $DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	$Users_Right = json_decode($rsUsers['Users_Right'],true)?json_decode($rsUsers['Users_Right'],true):array();
	
	foreach ($Users_Right as $key=>$val){
		foreach($val as $k=>$v){
			$right[$key][$v] = $file_all[$key][$v];
			if(in_array($key,array('kanjia','zhuli','hongbao'))){
				$right[$key]['config'] = $file_all[$key][$v];
			}
			if(in_array($key,array('zhongchou','games'))){
				$right[$key] = $file_all[$key];
			}
		}
	}
	//var_dump($right);
	$my_users_right = array_merge_recursive($file,$right);
	$rights = !empty($my_users_right)?$my_users_right:array();
}
//var_dump($right);
$my_right = array();

foreach($rights as $k=>$v){
	if($k == 'web'){
		$v = array(
			'web' => '微官网',
			'config' => '基本设置',
			'skin' => '风格设置',
			'home' => '首页设置',
			'column' => '栏目管理',
			'article' => '内容管理',
			'lbs' => '一键导航'
		);
	}
	foreach($v as $key=>$val){
		if($k != $key){
			$s = $k;
			$t = $key;
			if($k == 'weixin'){
				if($key == 'renewal_record'){
					$s = 'buy_record';
				}else{
					$s = 'wechat';
				}
			}
			if($k == 'weixin' && $key == 'shipping'){
					$s = 'shop';
				}
			if($k == 'weicuxiao'){
				if($key == 'sctrach'){
					$s = 'scratch';
				}else{
					$s = $key;
				}
					$t = 'config';
				}
			if(in_array($k,array('basic','product','articles','orders','backups','distribute'))){
				$s = 'shop';
				if(in_array($k,array('articles','orders','backups'))){
					$t = $k;
				}
			} 
			$my_right[$s][] = $t;
		}
	}
}
//var_dump($my_right);
$json_right = json_encode($my_right,JSON_UNESCAPED_UNICODE);
//echo $json_right;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SiteName;?></title>
<link href="/static/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="/static/style.css" rel="stylesheet" type="text/css" />

<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.mousewheel.js'></script> 
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.jscrollpane.js'></script>
<link href='/static/js/plugin/jscrollpane/jquery.jscrollpane.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/main.js'></script> 
<script language="javascript">$(document).ready(main_obj.page_init); window.onresize=main_obj.page_init;</script>
<div id="header">
  <div class="logo"><img src="<?php echo $SiteLogo ? $SiteLogo : '/static/member/images/main/header_logo.png';?>" /></div>
  <ul>
    <li class="ico-0"><a href="/member/wechat/account.php" target="iframe">我的帐号</a></li>
    <li class="ico-6"><a href="/member/wechat/profile.php" target="iframe">修改密码</a></li>
    <li class="ico-1"><a href="/member/wechat/guide.php" target="iframe">操作指南</a></li>
    <li class="ico-2"><a href="/member/html/material.php" target="iframe">素材库</a></li>
    <li class="ico-4"><a href="/member/html/spread.php" target="iframe">推广技巧</a></li>
   <li class="ico-4"><a href="/member/employee/employee_add.php" target="iframe">员工管理</a></li>
    <li class="ico-5"><a id='logout' href="?action=logout">退出登录</a></li>
	<?php if(isset($_SESSION['user_type'])){?>
    <li class=""><b style='color:#FFF;'>员工在线:<?php echo $_SESSION['employee_name'];?></b></li>
	<?php }?>
  </ul>
</div>
<div id="main">
  <div class="menu">
    <dl>
      <dt group="wechat" class="cur">我的微信</dt>
      <dd>
      	<div><a href="/member/wechat/account.php" target="iframe">系统首页</a></div>
        <div><a href="/member/wechat/attention_reply.php" target="iframe">首次关注设置</a></div>
        <div><a href="/member/wechat/menu.php" target="iframe">自定义菜单设置</a></div>
        <div><a href="/member/wechat/keyword_reply.php" target="iframe">关键词回复设置</a></div>
        <div><a href="/member/wechat/token_set.php" target="iframe">微信接口配置</a></div>
		<div><a href="/member/wechat/shopping.php" target="iframe">运费与支付</a></div>
		<div><a href="/member/shop/shipping.php" target="iframe">运费管理</a></div>
      </dd>
      
      <dt group="material">素材管理</dt>
      <dd>
        <div><a href="/member/material/index.php" target="iframe">图文消息管理</a></div>
        <div><a href="/member/material/url.php" target="iframe">自定义URL管理</a></div>
        <div><a href="/member/material/sysurl.php" target="iframe">系统URL查询</a></div>
      </dd>
      <dt group="config">商城设置</dt>
      <dd>
      	 	<div><a href="/member/shop/config.php" target="iframe">基本设置</a></div>
       		<div><a href="/member/shop/other_config.php" target="iframe">活动设置</a></div>
            <div><a href="/member/shop/distribute_config.php" target="iframe">分销设置</a></div>
        	<div><a href="/member/shop/skin.php" target="iframe">风格设置</a></div>
        	<div><a href="/member/shop/home.php" target="iframe">首页设置</a></div>
      </dd>
      
      <dt group="product">产品管理</dt>
      <dd>
      		
      		<div><a href="/member/shop/products.php" target="iframe">产品列表</a></div>
        	<div><a href="/member/shop/category.php" target="iframe">产品分类管理</a></div>
        	<div><a href="/member/shop/shop_attr.php" target="iframe">产品属性管理</a></div> 
            <div><a href="/member/shop/product_type.php" target="iframe">产品类型管理</a></div> 
            <div><a href="/member/shop/commit.php" target="iframe">产品评论管理</a></div>
      </dd>
    	
      <dt group="article"><a href="/member/shop/articles.php" target="iframe">文章发布</a></dt>
      <dt group="order"><a href="/member/shop/orders.php" target="iframe">订单管理</a></dt>
      <dt group="back"><a href="/member/shop/backups.php" target="iframe">退货单管理</a></dt>
      
      <dt group="distribute">分销管理</dt>
      <dd>
            <div><a href="/member/shop/distributes.php" target="iframe">分销账号管理</a></div>
            <div><a href="/member/shop/distribute_record.php" target="iframe">分销记录</a></div>
            <div><a href="/member/shop/dis_agent_rec.php" target="iframe">代理商获奖记录</a></div>
            <div><a href="/member/shop/withdraw_record.php" target="iframe">提现记录</a></div>
            <div><a href="/member/shop/distribute_title.php" target="iframe">爵位设置</a></div>
            <div><a href="/member/shop/withdraw_method.php" target="iframe">提现方法管理</a></div>
			<?php if($rsConfig['Dis_Agent_Type'] == 2):?>
			<div><a href="/member/shop/dis_agent_list.php" target="iframe">地区代理列表</a></div>	
			<?php endif;?>
      </dd>
      
	  <dt group="kf">在线客服</dt>
      <dd>
        <!--<div><a href="/member/kf/account.php" target="iframe">坐席管理</a></div>
		<div><a href="/member/kf/config.php" target="iframe">网页客服设置</a></div>
		<div><a href="/kf/admin/login.php" target="_blank">网页客服系统</a></div>-->
		<div><a href="/member/kf/config.php" target="iframe">客服设置</a></div>
      </dd>
            
      <dt group="user">会员中心</dt>
      <dd>
        <div><a href="/member/user/config.php" target="iframe">基本设置</a></div>
        <div><a href="/member/user/user_list.php" target="iframe">会员管理</a></div>
        <div><a href="/member/user/card_config.php" target="iframe">会员卡设置</a></div>
        <div><a href="/member/user/coupon_list.php" target="iframe">优惠券管理</a></div>
        <div><a href="/member/user/gift_orders.php" target="iframe">礼品兑换管理</a></div>
        <div><a href="/member/user/business_password.php" target="iframe">商家密码设置</a></div>
        <div><a href="/member/user/message.php" target="iframe">消息发布管理</a></div>
      </dd>
	  <dt group="web">微砍价</dt>
      <dd>
        <div><a href="/member/kanjia/config.php" target="iframe">微砍价管理</a></div>
      </dd>
	  <dt group="action">微促销活动</dt>
      <dd>        
        <div><a href="/member/scratch/config.php" target="iframe">刮刮卡管理</a></div>        
        <div><a href="/member/fruit/config.php" target="iframe">水果达人管理</a></div>        
        <div><a href="/member/turntable/config.php" target="iframe">欢乐大转盘管理</a></div>        
        <div><a href="/member/battle/config.php" target="iframe">一战到底</a></div>
		<div><a href="/member/guanggao/config.php" target="iframe">广告中心</a></div>
      </dd>
	  
	  <dt group="web">微官网</dt>
      <dd>
        <div><a href="/member/web/config.php" target="iframe">基本设置</a></div>
        <div><a href="/member/web/skin.php" target="iframe">风格设置</a></div>
        <div><a href="/member/web/home.php" target="iframe">首页设置</a></div>
        <div><a href="/member/web/column.php" target="iframe">栏目管理</a></div>
        <div><a href="/member/web/article.php" target="iframe">内容管理</a></div>
        <div><a href="/member/web/lbs.php" target="iframe">一键导航</a></div>
      </dd>
	  
	  <dt group="web">微众筹</dt>
      <dd>
        <div><a href="/member/zhongchou/config.php" target="iframe">基础设置</a></div>
		<div><a href="/member/zhongchou/project.php" target="iframe">项目管理</a></div>
      </dd>
	  <dt group="zhulli"><a href="/member/votes/config.php" target="iframe">微投票</a></dt>
	  <dt group="zhulli"><a href="/member/zhuli/config.php" target="iframe">微助力</a></dt>
	  <dt group="hongbao"><a href="/member/hongbao/config.php" target="iframe">抢红包</a></dt>
	  
	  <dt group="action">游戏中心</dt>
      <dd>        
        <div><a href="/member/games/config.php" target="iframe">基础设置</a></div>        
        <div><a href="/member/games/lists.php" target="iframe">游戏管理</a></div>        
      </dd>
	  
      <dt group="staticstic">数据统计</dt>
      <dd>
        <div><a href="/member/staticstics/fans.php" target="iframe">粉丝数据统计</a></div>
        <div><a href="/member/staticstics/sales.php" target="iframe">微促销参与次数</a></div>
        <div><a href="/member/staticstics/user.php" target="iframe">会员注册统计</a></div>
        <div><a href="/member/staticstics/user_area.php" target="iframe">会员来源地统计</a></div>
      </dd>
      <dt group="order"><a href="/member/wechat/renewal_record.php" target="iframe">购买记录</a></dt>
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
<script>
	var right = <?php echo $json_right?>;
	
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
				str = "'\/member\/"+key+"\/"+v+".php'";
				//alert(str);
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