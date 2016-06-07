<link href='<?php echo $output['_site_url'];?>/static/pc/shop/css/cart.css' rel="stylesheet" type="text/css" />
<script src='<?php echo $output['_site_url'];?>/static/pc/shop/js/payment.js'></script>
<script>
	$(document).ready(payment_obj.payment_init);
</script>
<div class="comtent">
	<?php include(__DIR__ . '/_menu.php');?>
</div>
<div class="product-intro">
	<div class="wzw-wrapper">
		<div class="wzw-main">
			<div class="wzw-title">
				<h3>完善支付信息！</h3>
				<h5>订单详情内容可通过查看<a target="_blank" href="<?php echo url('member/status', array('Status' => 0));?>">我的订单</a>进行核对处理。</h5>
			</div>
			<form id="buy_form">
				<input type="hidden" name="PaymentMethod" id="PaymentMethod_val" value="<?=$output['method_list'][$_GET['Paymethod']]?>"/>
				<input type="hidden" name="OrderID" value="<?php echo $output['OrderID']; ?>" />
				<input type="hidden" name="action" value="payment" />
				<input type="hidden" name="DefautlPaymentMethod" value="" />
				<div class="wzw-receipt-info">
					<div class="wzw-receipt-info-title">
						<h3>完善支付信息！在线支付金额：<strong>￥<?php echo $output['total'];?></strong> </h3>
					</div>
					<table class="wzw-table-style">
						<thead>
							<tr>
								<th></th>
								<th class="tl">订单号</th>
								<th class="tl">支付方式</th>
								<th class="tl">金额</th>
								<th>账户余额</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td class="tl"><?php echo $output['ordersn'];?></td>
								<td class="tl"><?=$output['method_list'][$_GET['Paymethod']]?></td>
								<td class="tl">￥<?php echo $output['total'];?></td>
								<td>￥<?php echo $output['rsUser']['user_money'];?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="wzw-receipt-info">
				<?php if($_GET['Paymethod'] == 'money'){?>
				<?php if($output['rsUser']['user_money'] < $output['total']){?>
				<div class="wzw-receipt-info-title">
					<h3 style="color:#F60;">抱歉，余额不足</h3>
				</div>
			    <?php }else {?>
				<div class="wzw-receipt-info-title">
					<h3>请输入支付密码</h3>
				</div>
				<ul class="wzw-payment-list">
				    <input type="password" value="" placeholder="请输入支付密码" name="PayPassword" />
				</ul>
				<?php }?>
				<?php }else if($_GET['Paymethod'] == 'huodao'){?>
				<div class="wzw-receipt-info-title">
					<h3>请填写支付信息</h3>
				</div>
				<ul class="wzw-payment-list">
				    <textarea name="PaymentInfo" cols='60'></textarea>
				</ul>
				<?php }?>
				</div>
				<?php if($output['rsUser']['user_money'] >= $output['total']){?>
				<div class="wzw-bottom tc mb50"><a class="wzw-btn wzw-btn-green" id="pay_money_button" href="javascript:void(0);">确认提交支付</a></div>
			    <?php }?>
			</form>
		</div>
	</div>
</div>