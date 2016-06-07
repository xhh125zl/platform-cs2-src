<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<div class="comtent">
    <?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="breadcrumb">
    <?php if(!empty($output['Bread'])){?>
    <?php foreach($output['Bread'] as $link => $name){?>
    <span><a href="<?php echo $link;?>"><?php echo $name;?></a></span> &nbsp;>&nbsp;
    <?php }?>
    <?php }?>
</div>
<div class="my_con">
    <div class="body">
        <?php include(__DIR__ . '/_left.php');?>
        <div class="body_center_pub all_dingdan">
			<div class="ddxq_top"> <span><em>订单号：</em><?php echo date('Ymd',$output['rsOrder']['Order_CreateTime']).$output['rsOrder']['Order_ID'];?></span> <span><em>状态：</em><?php echo $output['Order_Status'][$output['rsOrder']['Order_Status']];?></span><br>
				<span><em>下单时间：</em><?php echo date('Y-m-d H:i:s',$output['rsOrder']['Order_CreateTime']) ?></span> <span> <em>支付方式：</em><?php echo $output['rsOrder']['Order_PaymentMethod']?:'余额支付';?><?php if($output['rsOrder']['Order_PaymentMethod'] == '线下支付'){ ?>支付信息: <?php echo $output['rsOrder']['Order_PaymentInfo'] ?>&nbsp;&nbsp;<a href="<?php echo url('payment/omplete_pay', array('OrderID'=>$output['rsOrder']['Order_ID'],'Paymethod'=>'huodao'));?>" class="red"><strong>修改支付信息</strong></a><?php }?></span> <span><em>配送方式：</em>
				<?php 
				if(empty($output['Shipping'])) {
					echo '暂无信息';
				}else {
					if(empty($output['Shipping']['Express'])){
						echo '暂无信息';
					}else{
						echo $output['Shipping']['Express'];
					}
				}
				?>
				<?php if(empty($output['Shipping']['Price'])){?>
					免运费 
				<?php }else{?>
			    <?php
					echo '￥'.$output['Shipping']['Price'];
				?>
				<?php }?>
				</span></div>
			<div class="ddxq_botton">
				<div class="title">订单明细</div>
				<div class="ddxq_more">
					<div class="address">
						<p><span class="style">收货地址：</span><?php echo $output['rsOrder']['Address_Name'];?>，<?php echo $output['rsOrder']['Address_Mobile'];?>，<?php echo $output['Province'] . '&nbsp;&nbsp;' . $output['City'] . '&nbsp;&nbsp;' . $output['Area'];?></p>
						<p><span class="style">卖家留言：</span><?php echo $output['rsOrder']['Order_Remark'];?></p>
						<?php if($output['rsOrder']['Order_InvoiceInfo']){?>
						<p><span class="style">发票信息：</span><?php echo $output['rsOrder']['Order_InvoiceInfo'];?></p>
						<?php }?>
					</div>
					<?php if(empty($output['lists_back'])){?>
					<div class="masthead"> <span id="ddxq_name">商品</span> <span id="ddxq_price">单价</span> <span id="ddxq_num">数量</span> <span id="ddxq_truepay">实付款</span></div>
					<?php }else{?>
					<div class="masthead"> <span id="ddxq_name">商品</span> <span id="ddxq_price">单价</span> <span id="ddxq_num">数量</span> <span id="ddxq_truepay">实付款</span><span id="ddxq_paydo">交易操作</span></div>
					<?php }?>
					<?php foreach($output['CartList'] as $key => $value){?>
					<?php foreach($value as $k => $v){?>
					<div class="dingdan">
						<div class="dd_more"> 
						    <span id="ddxq_name" style="overflow:hidden;"><img title="<?php echo $v['ProductsName'];?>" src="<?php echo $v['ImgPath'];?>" style="width:150px;float:left; margin-right:10px;">
								<div style="float:left;">
									<a href="<?php echo url('goods/index', array('id'=>$key));?>"><?php echo $v['ProductsName'];?></a>
									<?php foreach($v['Property'] as $Attr_ID => $Attr){?>
									<span><?php echo $Attr['Name'];?> ：<?php echo $Attr['Value'];?></span><br>
									<?php }?>
								</div>
							</span> 
							<span id="ddxq_price"><i><?php echo $v['ProductsPriceX']?></i></span> 
							<span id="ddxq_num"><?php echo $v['Qty'];?></span> 
							<span id="ddxq_truepay"><?php echo '￥' . $v['ProductsPriceX']*$v['Qty'];?></span>
						</div>
					</div>
					<?php }?>
					<?php }?>
					<?php if(!empty($output['lists_back'])){?>
					<?php foreach($output['lists_back'] as $item){?>
					<?php 
					$CartList_back = json_decode(htmlspecialchars_decode($item['Back_Json']), true);
					?>
					<div class="dingdan">
						<div class="dd_more"> 
						    <span id="ddxq_name" style="overflow:hidden;"><img title="<?php echo $CartList_back['ProductsName'];?>" src="<?php echo $CartList_back['ImgPath'];?>" style="width:150px;float:left; margin-right:10px;">
								<div style="float:left;">
									<a href="<?php echo url('goods/index', array('id'=>$item['ProductID']));?>"><?php echo $CartList_back['ProductsName'];?></a>
									<?php foreach($CartList_back['Property'] as $Attr_ID => $Attr){?>
									<span><?php echo $Attr['Name'];?> ：<?php echo $Attr['Value'];?></span><br>
									<?php }?>
								</div>
							</span> 
							<span id="ddxq_price"><i><?php echo $CartList_back['ProductsPriceX']?></i></span> 
							<span id="ddxq_num"><?php echo $CartList_back['Qty'];?></span> 
							<span id="ddxq_truepay"><?php echo '￥' . $CartList_back['ProductsPriceX']*$CartList_back['Qty'];?></span>
							<span id="ddxq_paydo"><?php echo $output['_STATUS'][$item['Back_Status']];?>&nbsp;&nbsp;<a href="<?php echo url('member/backup_detail', array('id'=>$item['Back_ID']));?>">退款详情</a></span>
						</div>
					</div>
					<?php }?>
					<?php }?>
					<div class="zongji">
						<p>运费金额：￥<?php echo $output['fee']?></p>
						<p>促销优惠：￥<?php echo $output['rsOrder']['Coupon_Cash'];?></p>
						<p>付款总金额：<span>￥<?php echo $output['rsOrder']['Order_TotalPrice'];?></span></p>
					</div>
				</div>
			</div>
		</div>

    </div>
</div>