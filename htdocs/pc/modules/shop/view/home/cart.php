<link href='<?php echo $output['_site_url'];?>/static/pc/shop/css/cart.css' rel="stylesheet" type="text/css" />
<script src='<?php echo $output['_site_url'];?>/static/pc/shop/js/cart.js'></script>
<script>
	$(document).ready(cart_obj.cart_init);
</script>
<style>
input[type="text"], input[type="password"], input.text, input.password {padding: 0;}
</style>
<div class="comtent">
	<?php include(__DIR__ . '/_menu.php');?>
</div>
<div class="product-intro">
	<div class="wzw-wrapper">
	<?php if(empty($output['cartList'])) {?>
	<div class="wzw-null-shopping"><i class="ico"></i>
	    <h4>您的购物车还没有商品</h4>
	    <p><a class="wzw-btn-mini mr10" href="<?php echo url('index/index');?>"><i class="icon-reply-all"></i>马上去购物</a> <a class="wzw-btn-mini" href="<?php echo url('member/status', array('Status'=>0));?>"><i class="icon-file-text"></i>查看自己的订单</a></p>
	</div>
	<?php }else{?>
	<style>
	.wzw-table-style tbody tr.item_disabled td {
		background: none repeat scroll 0 0 #F9F9F9;
		height: 30px;
		padding: 10px 0;
		text-align: center;
	}
	</style>
		<div class="wzw-main">
			<div class="wzw-title">
				<h3>我的购物车</h3>
				<h5>查看购物车商品清单，增加减少商品数量进入下一步操作。</h5>
			</div>
			<form id="form_buy">
				<input type="hidden" name="ifcart" value="1">
				<table wzw_type="table_cart" class="wzw-table-style">
					<thead>
						<tr>
						    <th>&nbsp;&nbsp;</th>
							<th></th>
							<th>商品</th>
							<th class="w120">单价(元)</th>
							<th class="w120">数量</th>
							<th class="w120">小计(元)</th>
							<th class="w80">操作</th>
						</tr>
					</thead>
					<?php 
					$total = $qty = 0;//总计
  	                foreach($output['cartList'] as $BizID => $BizCart){
						$rsBiz = model('biz')->field('Biz_Name')->where(array('Biz_ID'=>$BizID))->find();
						if(!$rsBiz) {
							continue;
						}
					?>
					<tbody>
						<tr>
							<th colspan="20"><strong>店铺：<a href="<?php echo url('store/index');?>"><?php echo $rsBiz['Biz_Name'];?></a></strong> <span id="biz_cart_<?php echo $BizID;?>"></span> </th>
						</tr>
						
						<!-- S one store list -->
						<?php 
						$heji = $qty2 = 0;//店铺合计
						foreach($BizCart as $ProductsID => $Products){
							$xiaoji = $qty3 = 0;//商品小计
							foreach($Products as $CartID => $Cart){
								$total += $Cart['Qty'] * $Cart['ProductsPriceX'];
								$qty += $Cart['Qty'];
								
								$heji += $Cart['Qty'] * $Cart['ProductsPriceX'];
								$qty2 += $Cart['Qty'];
								
								$xiaoji += $Cart['Qty'] * $Cart['ProductsPriceX'];
								$qty3 += $Cart['Qty'];
						?>
						<tr class="shop-list" BizID="<?php echo $BizID;?>" ProductsID="<?php echo $ProductsID;?>" CartID="<?php echo $CartID;?>">
							<td>&nbsp;&nbsp;</td>
							<td class="w60"><a class="wzw-goods-thumb" target="_blank" href="<?php echo url('goods/index', array('id'=>$ProductsID));?>"><img alt="<?php echo $Cart['ProductsName'];?>" src="<?php echo $Cart['ImgPath'];?>"></a></td>
							<td class="tl">
							    <dl class="wzw-goods-info">
									<dt><a target="_blank" href="<?php echo url('goods/index', array('id'=>$ProductsID));?>"><?php echo $Cart['ProductsName'];?></a></dt>
									<?php
								    if(!empty($Cart["Property"])){
									    echo '<dd>';
										foreach($Cart["Property"] as $Attr_ID => $Attr){
											echo '<span>' . $Attr['Name'] . ': '.$Attr['Value'] . '；</span>';
										}
										echo '</dd>';
									}
									?>
								</dl>
							</td>
							<td class="w120"><em><?php echo $Cart["ProductsPriceX"];?></em></td>
							<td class="w120 ws0">
							    <a class="add-substract-key tip" title="减少商品件数" href="JavaScript:void(0);" rel="qty_less">-</a>
								<input type="text" class="text" value="<?php echo $qty2;?>" style="width:30px;" rel="qty_input"/>
								<a class="add-substract-key tip" title="增加商品件数" href="JavaScript:void(0);" rel="qty_add">+</a>
							</td>
							<td class="w120"><em wzw_type="eachGoodsTotal"><?php echo $xiaoji;?></em></td>
							<td class="w80">
								<a href="javascript:void(0)" class="delCart">删除</a>
							</td>
						</tr>
						<?php }?>
						<?php }?>
						<tr>
							<td colspan="20" class="tr">
							    <div class="wzw-store-account">
									<dl>
										<dt>店铺合计：</dt>
										<dd><em wzw_type="eachStoreTotal"><?php echo $heji;?></em>元</dd>
									</dl>
								</div>
							</td>
						</tr>
						<!-- E one store list --> 
					</tbody>
					<?php }?>
					<tfoot>
						<tr>
							<td colspan="20"><div class="wzw-all-account">商品总价（不含运费）<em id="cartTotal"><?php echo $total;?></em>元</div></td>
						</tr>
					</tfoot>
				</table>
			</form>
			<div class="wzw-bottom"><a class="wzw-btn wzw-btn-acidblue fr" href="javascript:void(0)" id="next_submit"><i class="icon-pencil"></i>下一步，填写核对购物信息</a></div>
		</div>
	<?php }?>
	</div>
</div>