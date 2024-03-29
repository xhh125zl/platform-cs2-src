<link href='<?php echo $output['_site_url'];?>/static/pc/shop/css/cart.css' rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/public/js/jquery.ui.js"></script>
<script src="<?php echo $output['_site_url'];?>/static/pc/public/js/dialog/dialog.js" id="dialog_js"></script>
<script src='<?php echo $output['_site_url'];?>/static/pc/shop/js/order.js'></script>
<script>
	$(document).ready(order_obj.order_init);
</script>
<style>
input[type="text"], input[type="password"], input.text, input.password {padding: 0;}
</style>
<div class="comtent">
	<?php include(__DIR__ . '/_menu.php');?>
</div>
<div class="product-intro">
	<div class="wzw-wrapper">
	    <form id="order_form">
		    <input type="hidden" name="action" value="checkout"/>
		    <input type="hidden" name="cart_key" id="cart_key" value="Virtual"/>
		    <div class="wzw-main">
			    <div class="wzw-title">
					<h3>填写核对购物信息</h3>
					<h5>请仔细核对填写联系方式、发票等信息。</h5>
			    </div>
				<?php
				$total = $qty = 0;//总计
				$recieve = 0;
				foreach($output['cartList'] as $Biz_ID => $BizCartList) {
				?>
				<div class="wzw-receipt-info">
					<div class="wzw-receipt-info-title">
						<h3>商品清单</h3>
					</div>
					<table class="wzw-table-style">
						<thead>
							<tr>
								<th>&nbsp;&nbsp;</th>
								<th>&nbsp;&nbsp;</th>
								<th>商品</th>
								<th>单价(元)</th>
								<th>数量</th>
								<th>小计(元)</th>
								<th>&nbsp;&nbsp;</th>
							</tr>
						</thead>
						<?php 
						$heji = $qty2 = 0;//店铺合计
						?>
						<?php foreach($BizCartList as $Products_ID => $Product_List) {?>
						<tbody>
							<tr>
								<th colspan="20">
								    <strong>店铺：<a href="<?php echo url('store/index', array('id'=>$Biz_ID));?>"><?php echo $output['order_total_info'][$Biz_ID]['Biz_Config']['Biz_Name'];?></a></strong>
									<div class="store-sale"> </div>
								</th>
							</tr>
							<?php
							$xiaoji = 0;//商品小计
							foreach($Product_List as $key => $product){
								$total += $product['Qty'] * $product['ProductsPriceX'];
								$qty += $product['Qty'];
									
								$heji += $product['Qty'] * $product['ProductsPriceX'];
								$qty2 += $product['Qty'];
									
								$xiaoji += $product['Qty'] * $product['ProductsPriceX'];
								
								$rsProduct = model('shop_products')->field('Products_IsRecieve')->where(array('Products_ID'=>$Products_ID))->find();
								if($rsProduct['Products_IsRecieve'] == 1){
									$recieve = 1;
								}
							?>
							<tr class="shop-list" BizID="<?php echo $Biz_ID;?>" ProductsID="<?php echo $Products_ID;?>" CartID="<?php echo $key;?>">
								<td>&nbsp;&nbsp;</td>
								<td><a class="wzw-goods-thumb" target="_blank" href="<?php echo url('goods/index', array('id'=>$Products_ID));?>"><img alt="<?php echo $product['ProductsName'];?>" src="<?php echo $product['ImgPath'];?>"></a></td>
								<td class="tl">
									<dl class="wzw-goods-info">
										<dt><a target="_blank" href="<?php echo url('goods/index', array('id'=>$Products_ID));?>"><?php echo $product['ProductsName'];?></a></dt>
										<?php
										if(!empty($product["Property"])){
											echo '<dd>';
											foreach($product["Property"] as $Attr_ID => $Attr){
												echo '<span>' . $Attr['Name'] . ': '.$Attr['Value'] . '；</span>';
											}
											echo '</dd>';
										}
										?>
									</dl>
								</td>
								<td><em><?php echo $product["ProductsPriceX"];?></em></td>
								<td><?php echo $qty2;?></td>
								<td><em wzw_type="eachGoodsTotal"><?php echo $xiaoji;?></em></td>
								<td></td>
							</tr>
							<?php }?>
							<tr>
								<td></td>
								<td colspan="2" class="tl">接收短信手机：
								    <input type="input" maxlength="11" title="当前使用的联系方式" class="wzw-msg-input" name="Mobile" value="" />
								</td>
								<td colspan="10" class="tl"><div class="wzw-form-default"> </div></td>
							</tr>
							<tr>
								<td class="w10"></td>
								<td colspan="2" class="tl">买家留言：
									<textarea maxlength="150" title="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）" placeholder="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）" class="wzw-msg-textarea" name="Remark[<?php echo $Biz_ID;?>]"></textarea></td>
								<td colspan="10" class="tl"><div class="wzw-form-default"> </div></td>
							</tr>
							<tr>
								<td colspan="20" class="tr">
								    <div class="wzw-store-account">
										<dl class="total">
											<dt>本店合计：</dt>
											<dd><em wzw_type="eachStoreTotal"><?php echo $heji;?></em>元</dd>
										</dl>
									</div>
								</td>
							</tr>
						</tbody>
				        <?php }?>
					</table>
				</div>
				<!-- 发票begin -->
				<div class="wzw-receipt-info">
					<div class="wzw-receipt-info-title">
						<h3>发票信息</h3>
						<a class="edit_invoice" href="javascript:void(0)" style="display: inline-block;">[修改]</a>
					</div>
				    <div class="wzw-candidate-items invoice_list1"><ul><li>不需要发票</li></ul></div>
					<div class="wzw-candidate-items invoice_list2" style="display:none;">
						<ul>
							<li class="inv_item">
								<label style="color:#666666">&nbsp;&nbsp;提示：请填写发票抬头，个人姓名或公司名称...</label>
							</li>
							<div style="">
								<div class="wzw-form-default vat_invoice_panel">
										
									<dl>
										<dt style="width:auto;"><i class="required">*</i>送票信息：</dt>
										<dd>
										    <input type="hidden" name="Order_NeedInvoice[<?=$Biz_ID?>]" value="0" class="Order_NeedInvoice" />
											<input type="text" value="" name="Order_InvoiceInfo[<?=$Biz_ID?>]" maxlength="200" class="text Order_InvoiceInfo" size="100" />
										</dd>
									</dl>
								</div>
							</div>
						</ul>
						<div class="hr16"> <a href="javascript:void(0);" class="wzw-btn wzw-btn-red hide_invoice_list">保存发票信息</a> <a href="javascript:void(0);" class="wzw-btn cancel_invoice">不需要发票</a></div>
					</div>
				</div>
				<!-- 发票end -->
				<?php }?>
                                <!-- 积分begin -->
				<?php if($output['order_total_info']['man_array']){?>
				<div class="wzw-receipt-info">
				    <div class="wzw-receipt-info-title">
						<h3>此次购物可获得<span id="total_integral" style="color:#F60;"><?=$output['order_total_info']['integral']?></span>个积分</h3>
						 
					</div>
				</div>
				<?php }?>
				<!-- 积分end -->
				<div class="wzw-all-account">订单总金额：<em><?php echo $output['order_total_info']['total'] + $output['order_total_info']['total_shipping_fee'];?></em>元</div>
				<div class="wzw-bottom"><a class="wzw-btn wzw-btn-acidblue fr" href="javascript:void(0)" id="order_submit"><i class="icon-pencil"></i>提交订单</a></div>
			</div>
			<input type="hidden" name="recieve" value="<?php echo $recieve;?>" />
		</form>
	</div>
</div>