<link href='<?php echo $output['_site_url'];?>/static/pc/shop/css/cart.css' rel="stylesheet" type="text/css" />
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
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
		    <input type="hidden" name="cart_key" id="cart_key" value="CartList"/>
		    <div class="wzw-main">
			    <div class="wzw-title">
					<h3>填写核对购物信息</h3>
					<h5>请仔细核对填写收货、发票等信息，以确保物流快递及时准确投递。</h5>
			    </div>
				<?php if($output['shopConfig']['needshipping'] == 1) {?>
				<div class="wzw-receipt-info">
				<?php if($output['address_info']) {?>
				    <div class="wzw-receipt-info-title">
						<h3>收货人信息</h3>
						<a id="edit_reciver" href="javascript:void(0)">[重新选择]</a>
					</div>
				    <div class="wzw-candidate-items" id="addr_list" add_id="<?=$output['address_info']['address_id']?>">
						<ul>
						    <li>收货人：<span class="true-name"><?=$output['address_info']['address_name']?></span>&nbsp;&nbsp;<span class="phone" style="background:none;margin-right:5px;padding-left:5px;"><?=$output['address_info']['address_mobile']?></span></li>
							<li>所在地：<span class="address"><?=$output['address_info']['Province'].$output['address_info']['City'].$output['address_info']['Area'];?></span></li>
							<li>详细地址：<span class="detailed"><?=$output['address_info']['address_detailed']?></span></li>
							<input type="hidden" id="City_Code" name="City_Code" value="<?=$output['address_info']['address_city']?>"/>
						</ul>
				    </div>
				<?php }?>
				</div>
				<?php }else {?>
				<div class="wzw-receipt-info">
				    <div class="wzw-receipt-info-title">
					    <h3>此平台无需物流</h3>
				    </div>
				</div>
				<script>
				    $(document).ready(function(){
						$('.shipping_box').remove();
					});
				</script>
				<?php }?>
				<?php
				$total = $qty = 0;//总计
				$Shipping_error = false;//检测是否有商家没有配置好物流
				foreach($output['cartList'] as $Biz_ID => $BizCartList) {
					$ShippingFree[$Biz_ID] = 1;//初始化为免运费
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
								if($ShippingFree[$Biz_ID]==1 && $product['IsShippingFree'] == 0){
									$ShippingFree[$Biz_ID] = 0;
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
				<!-- 配送方式begin -->
				<div class="wzw-receipt-info shipping_box">
				<?php if(!$ShippingFree[$Biz_ID]) {?>
				<?php if(empty($output['order_total_info'][$Biz_ID]['error'])) {?>
				    <div class="wzw-receipt-info-title" style="font-size:14px;line-height:22px;">
						<h3>配送方式</h3>&nbsp;&nbsp;
						<?=$output['order_total_info'][$Biz_ID]['Shipping_Name']?>&nbsp;&nbsp;
						<span id="biz_shipping_fee_txt_<?=$Biz_ID;?>">
						<?=$output['order_total_info'][$Biz_ID]['total_shipping_fee'];?>
						元 
						</span>
						<a href="javascript:void(0)" class="shipping_method" Biz_ID="<?=$Biz_ID?>">[重新选择]</a>
						<input type="hidden" name="Biz_Shipping_Fee[<?=$Biz_ID?>]" id="Shipping_ID_<?=$Biz_ID?>" value="<?=$output['order_total_info'][$Biz_ID]['total_shipping_fee']?>" class="Shipping_fee_value"/>
						<input type="hidden" name="Biz_Shipping_ID[<?=$Biz_ID?>]" value="<?=$output['order_total_info'][$Biz_ID]['Shipping_ID']?>"/>
					</div>
				<?php }else {?>
				    <?php 
					    $Shipping_error = true;
						$Shipping_error_msg = $output['order_total_info'][$Biz_ID]['msg'];
					?>
				<?php }?>
				<?php }else {?>
				<div class="wzw-receipt-info-title">
					<h3 style="color:#F60;">商家免运费</h3>
				</div>
				<?php }?>
				</div>
				<!-- 配送方式end --> 
				<?php }?>
				<?php if($Shipping_error == false){?>
				<div class="wzw-all-account">订单总金额：<em><?php echo $output['order_total_info']['total'] + $output['order_total_info']['total_shipping_fee'];?></em>元</div>
				<div class="wzw-bottom"><a class="wzw-btn wzw-btn-acidblue fr" href="javascript:void(0)" id="order_submit"><i class="icon-pencil"></i>提交订单</a></div>
				<?php }else{?>
				<div class="wzw-receipt-info">
					<div class="wzw-receipt-info-title">
						<h3 style="color:#F60;"><?php echo $Shipping_error_msg;?></h3>
					</div>
				</div>
				<?php }?>
			</div>
		</form>
	</div>
</div>