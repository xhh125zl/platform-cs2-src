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
				<h3>支付提交</h3>
				<h5>订单详情内容可通过查看<a target="_blank" href="<?php echo url('member/status', array('Status' => 0));?>">我的订单</a>进行核对处理。</h5>
			</div>
			<form id="buy_form">
				<input type="hidden" name="PaymentMethod" id="PaymentMethod_val" value=""/>
				<input type="hidden" name="OrderID" value="<?php echo $output['OrderID'];?>" />
				<input type="hidden" name="action" value="payment" />
				<input type="hidden" name="DefautlPaymentMethod" value="" />
				<div class="wzw-receipt-info">
					<div class="wzw-receipt-info-title">
						<h3>请您及时付款，以便订单尽快处理！在线支付金额：<strong>￥<?php echo $output['total'];?></strong> </h3>
					</div>
					<table class="wzw-table-style">
						<thead>
							<tr>
								<th></th>
								<th class="tl">订单号</th>
								<th class="tl">金额</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td class="tl"><?php echo $output['ordersn'];?></td>
								<td class="tl">￥<?php echo $output['total'];?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="wzw-receipt-info">
					<div class="wzw-receipt-info-title">
						<h3>支付选择</h3>
					</div>
					<ul class="wzw-payment-list">
					<?php if(!empty($output['rsPay']['payment_alipayenabled'])) {?>
						<li data-value="支付宝">
							<label>
							<i></i>
							<div class="logo"> <img src="<?php echo $output['_site_url']?>/static/pc/shop/images/alipay_logo.gif"> </div>
							</label>
						</li>
					<?php }else if(!empty($output['rsPay']['payment_offlineenabled'])) {?>
						<li data-value="线下支付">
							<label>
							<i></i>
							<div class="logo"> <img src="<?php echo $output['_site_url']?>/static/pc/shop/images/huodao_logo.gif"> </div>
							</label>
						</li>
					<?php }?>
					    <li data-value="余额支付">
							<label>
							<i></i>
							<div class="logo"> <img src="<?php echo $output['_site_url']?>/static/pc/shop/images/money_logo.gif"> </div>
							</label>
						</li>
					</ul>
				</div>
				<div class="wzw-bottom tc mb50"><a class="wzw-btn wzw-btn-green" id="pay_button" href="javascript:void(0);">确认提交支付</a></div>
			</form>
		</div>
	</div>
</div>