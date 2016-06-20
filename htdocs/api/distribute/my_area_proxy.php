<?php
require_once('global.php');
$applyList = array();
$applyObj = $DB->Get('agent_order', '*', ' WHERE `Users_ID`="' . $UsersID . '" and `User_ID` = "' . $User_ID . '" and `Order_Status` != 3 order by `Order_CreateTime` DESC');
while ($row = $DB->fetch_assoc()) 
{
	$applyList[] = $row;
}

$header_title = '我的区域代理申请列表';
require_once('header.php');
?>

<body>
<link href="/static/api/distribute/css/detaillist.css?t=<?php echo time();?>" rel="stylesheet">
<link href='/static/api/distribute/css/area_proxy.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/api/distribute/js/area_proxy.js?t=<?php echo time();?>'></script>
<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">我的区域代理申请列表</h1>
</header>
<script type="text/javascript">
var UsersID = "<?=$UsersID?>";
var ajax_url  = "<?=distribute_url()?>ajax/";
$(document).ready(area_proxy_obj.area_proxy_init);
$(document).ready(function(){
	$('a.cancel').click(function(){
		var UsersID = "<?php echo $UsersID; ?>";
		var currentObj = $('a.cancel');
		$.get('/api/distribute/ajax.php?UsersID='+UsersID, {'orderId':currentObj.attr('data-id'), 'action':'removeApplyOrder'}, function(data){
			if (data.status == 1) { $('.ll_'+currentObj.attr('data-id')).remove(); };
		}, 'json');
	});

});
	
</script>
<style type="text/css">
	.dislist { width: 97%; margin: 10px auto; background: #FFF; border-radius: 5px; display: block; overflow: hidden; border: 1px solid #ddd; }
	.disContent { padding: 10px; width: 60%; float: left; }
	.disContent dd { display: block; overflow: hidden; height: 26px; line-height: 26px; font-size: 12px; } 

	.disAction { width: 20%; float: right; display: block; overflow: hidden; height: 55px; line-height: 55px; padding: 13px 0; margin-right: 10px; }
	.disAction a { display: block; overflow: hidden; height: 24px; line-height: 24px; text-align: center; background: #ccc; margin-bottom: 15px; color: #FFF; border-radius: 5px; font-weight: bold; font-size: 12px; }
	.disAction a.cancel { background-color: #F00019; border: 1px solid #c10001; background-image: linear-gradient(bottom, #E20018 0, #DC3749 100%); background-image: -moz-linear-gradient(bottom, #E20018 0, #DC3749 100%); background-image: -webkit-linear-gradient(bottom, #E20018 0, #DC3749 100%); box-shadow: 0 1px 0 #D95260 inset, 0 1px 2px rgba(0,0,0,0.5); }
    .disAction a.pay { background-color: #f78d1d; border: 1px solid #da7c0c; background-image: linear-gradient(bottom,#faa51a 0, #f47a20 100%); background-image: -moz-linear-gradient(bottom, #faa51a 0, #f47a20 100%); background-image: -webkit-linear-gradient(bottom, #faa51a 0, #f47a2 100%); box-shadow: 0 1px 0 #D95260 inset, 0 1px 2px rgba(0,0,0,0.5);}
</style>
<div class="wrap">
	<?php if(!empty($applyList)): ?>
	<?php foreach ($applyList as $k => $v) : ?>
	<div class="dislist ll_<?php echo $v['Order_ID']; ?>">
		<div class="disContent">
			<dd><b>申请区域：</b><?php echo $v['Area_Concat']; ?></dd>
			<dd><b>状态：</b>
				<?php if($v['Order_Status'] == 0): ?>
				<font color="red">待审核</font>
				<?php elseif($v['Order_Status'] == 1): ?>
				<font color="#f78d1d">待付款</font>
				<?php elseif($v['Order_Status'] == 2): ?>
				<font color="#00FF00">已完成</font>
				<?php elseif($v['Order_Status'] == 4): ?>
				<font color="red">已拒绝</font>　　<?php if(isset($v['Refuse_Be'])){ echo $v['Refuse_Be']; } ?>
				<?php endif; ?></dd>
			<dd><?php echo date('Y-m-d H:i:s', $v['Order_CreateTime']); ?></dd>

		</div>

		<div class="disAction">
			<?php if($v['Order_Status'] == 1): ?>
			<a href="javascript:void(0);" class="pay payaction" data-id="<?php echo $v['Order_ID']; ?>" data-value="<?php echo $v['Order_TotalPrice']; ?>">马上付款</a>
			<?php endif; ?>
			<?php if($v['Order_Status'] != 2 && $v['Order_Status'] != 4): ?>
			<a href="javascript:void(0);" data-id="<?php echo $v['Order_ID']; ?>" class="cancel">取消申请</a>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
	<?php endif; ?>
</div>

<?php $rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");?>
    <div class="pay_select_list" id="">
        <h2><span>[×]</span>请选择支付方式</h2>
		<h3>支付总额：<span></span></h3>
        <?php if(!empty($rsPay["PaymentWxpayEnabled"])){ ?>
		<a href="javascript:void(0)" class="btn btn-default btn-pay direct_pay" id="wzf" data-value="1"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信支付</a>
		<?php }?>
		<?php if(!empty($rsPay["Payment_AlipayEnabled"])){?>
		<a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="zfb" data-value="2"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝支付</a>
		<?php }?>
		<a href="javascript:void(0)" class="btn btn-warning  btn-pay" id="money" data-value="余额支付"><img  src="/static/api/shop/skin/default/money.jpg"  width="16px" height="16px"/>&nbsp;余额支付</a>
        <p class="money_info">
			<b style="display:block; width:85%; height:40px; line-height:38px; font-size:14px; margin:0px auto; font-weight:normal">您当前余额：<span style="padding:0px 5px; font-size:16px; color:#F00">&yen;<?php echo $rsUser['User_Money'];?></span></b>
        	<input type="password" value="" placeholder="请输入支付密码，默认123456" style="display:block; width:85%; margin:5px auto 0px; border:1px #dfdfdf solid; border-radius:5px; height:40px; line-height:40px;">
            <button style="display:block; width:85%; margin:5px auto 0px; border:none; border-radius:5px; background:#4F93CE; color:#FFF; font-size:14px; text-align:center; height:40px; line-height:38px;">确认支付</button>
        </p>
    </div>
    
    <div class="products_list_tobuy">
    	<h2><span>[×]</span>请选择商品</h2>
        <div class="products_list_content"></div>
    </div>
	<div class="body_bg"></div>


<?php require_once('../shop/skin/distribute_footer.php');?> 
</body>
</html>
