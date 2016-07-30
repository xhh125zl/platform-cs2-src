<?php
require_once('global.php');
if(isset($_GET["action"])){
	if($_GET["action"]=="logout"){
		session_unset();
		header("location:/biz/login.php");
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SiteName;?>商家后台</title>
<link href="/static/style.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<style>
.msg { float:right;width:300px;margin-top:-45%;margin-right:20px;background:#fff; }
.msg ul { margin:20px 10px;}
.msg ul li { line-height:20px;}
.msg .title { height:30px; line-height:30px;padding:10px;padding-left:30px;border-bottom:1px solid #fcc;}
</style>
</head>

<body>
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.mousewheel.js'></script> 
<script type='text/javascript' src='/static/js/plugin/jscrollpane/jquery.jscrollpane.js'></script>
<link href='/static/js/plugin/jscrollpane/jquery.jscrollpane.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/main.js'></script>
<script language="javascript">$(document).ready(main_obj.page_init); window.onresize=main_obj.page_init;</script>
<div id="header">
  <div class="logo"><img src="<?php echo $SiteLogo ? $SiteLogo : '/static/member/images/main/header_logo.png'?>" /></div>
  <ul>
    <li class="ico-0"><a href="#"> <?php echo $rsBiz["Biz_Name"];?> ( <?php echo $rsGroup["Group_Name"];?> )</a></li>
    <li class="ico-5"><a href="?action=logout">退出登录</a></li>
  </ul>
</div>
<div id="main">
  <div class="menu">
    <dl>
      <dt class="cur">商家资料</dt>
      <dd style="display:block">
      	<div class="cur"><a href="account/account.php" target="iframe">商家资料</a></div>
        <div><a href="account/account_edit.php" target="iframe">修改资料</a></div>
		<div><a href="account/address_edit.php" target="iframe">收货地址</a></div>
        <div><a href="account/bind_user.php" target="iframe">绑定会员</a></div>
        <div><a href="account/account_password.php" target="iframe">修改密码</a></div>
      </dd>
      <?php if($IsStore==1){?>
      <dt>店铺管理</dt>
      <dd>
      	<div class="cur"><a href="home/skin.php" target="iframe">模板风格</a></div>
        <div><a href="home/home.php" target="iframe">首页设置</a></div>
      </dd>
      <?php }?>
      <dt>产品管理</dt>
      <dd>
        <div><a href="products/products.php" target="iframe">产品管理</a></div>
        <?php if($IsStore==1){?>
        <div><a href="products/category.php" target="iframe">自定义分类</a></div>
        <?php }?>
        <div><a href="products/product_type.php" target="iframe">属性类型</a></div>        
        <div><a href="products/shop_attr.php" target="iframe">产品属性库</a></div>
		<div><a href="products/virtual_card.php" target="iframe">虚拟卡密管理</a></div>
      </dd>
      <dt style="display:none;">优惠券管理</dt>
      <dd style="display:none;">
        <div><a href="coupon/coupon_list.php" target="iframe">优惠券管理</a></div>
      </dd>
      <dt>订单管理</dt>
      <dd>
        <div><a href="orders/orders.php" target="iframe">订单列表</a></div>
        <div><a href="orders/virtual_orders.php" target="iframe">消费认证</a></div>
        <div><a href="orders/backorders.php" target="iframe">退货列表</a></div>
        <div><a href="orders/commit.php" target="iframe">评论管理</a></div>
      </dd>
      <dt>活动管理</dt>
      <dd>
        <div><a href="active/active.php" target="iframe">申请活动列表</a></div>
        <div><a href="active/active_add.php" target="iframe">申请活动</a></div>
      </dd>
      <dt>财务结算</dt>
      <dd>
        <div><a href="finance/distribute_record.php" target="iframe">分销记录</a></div>
        <div><a href="finance/sales_record.php" target="iframe">销售记录</a></div>
        <div><a href="finance/payment.php" target="iframe">收款单</a></div>
      </dd>
      <dt>运费管理</dt>
      <dd>
      	<div><a href="shipping/config.php" target="iframe">运费设置</a></div>
        <div><a href="shipping/company.php" target="iframe">快递公司管理</a></div>
        <div><a href="shipping/template.php" target="iframe">快递模板管理</a></div>
        <div><a href="shipping/printtemplate.php" target="iframe">运单模板管理</a></div>
      </dd>	
	 <dt>拼团管理</dt>
	 <dd>
	    <div><a href="pintuan/products.php" target="iframe">产品管理</a></div>
        <div><a href="pintuan/orders.php" target="iframe">订单管理</a></div>
        <div><a href="pintuan/comment.php" target="iframe">评论管理</a></div>
        <div><a href="pintuan/virtual_card.php" target="iframe">虚拟卡密管理</a></div>
      </dd>
      <dt>云购管理</dt>
	 <dd>
	    <div><a href="cloud/products.php" target="iframe">产品管理</a></div>
        <div><a href="cloud/orders.php" target="iframe">订单管理</a></div>
        <div><a href="cloud/shipping_orders.php" target="iframe">订单领取管理</a></div>
        <div><a href="cloud/virtual_orders.php" target="iframe">虚拟订单管理</a></div>
      </dd>	
    </dl>
  </div>
  <div class="iframe" >
    <iframe src="account/account.php" name="iframe" frameborder="0" scrolling="auto" ></iframe>
  </div>
  
</div>
<div id="footer">
  <div class="oem"><?php echo $Copyright;?></div>
</div>
</body>
</html>