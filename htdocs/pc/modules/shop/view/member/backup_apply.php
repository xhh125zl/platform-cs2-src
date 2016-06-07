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
			<div class="sqtk">
				<div class="title"> <span class="nowon">申请退款</span></div>
				<div class="sqform">
				<form id="apply_form">
				  <div class="information">
					<div class="title">商品信息</div>
					<ul>
					  <li> <img class="img" src="<?=$output['item']['ImgPath']?>"><a class="word" href="<?=url('goods/index',array('id'=>$output['ProductsID']))?>"><?=$output['item']['ProductsName']?></a> </li>
					  <li>单价：<span>￥<?=$output['item']['ProductsPriceX']?></span></li>
					  <li>数量：<span><?=$output['item']['Qty']?></span></li>
					  <?php if($output['item']['Property']){?>
					  <li>
					   <?php foreach($output['item']["Property"] as $Attr_ID=>$Attr){?>
							<?php echo $Attr['Name'];?>: <?php echo $Attr['Value'];?>
						<?php }?>	 
					  </li>
					  <?php }?>
					  <li>下单时间：<?php echo date('Y-m-d H:i:s',$output['rsOrder']['Order_CreateTime']);?></li>
					</ul>
					<div class="more"><a href="<?php echo url('member/detail',array('id'=>$output['rsOrder']['Order_ID']));?>">查看订单详情</a></div>
				  </div>
				  <div class="howtk">
					<div class="tkje">
					  <ul>
						<li>数量：</li>
						<li><span class="span2">（商品数量）</span>
						  <select name="Qty">
							<?php for($i=1; $i<=$output['item']['Qty']; $i++){?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php }?>
						  </select>
						</li>
					  </ul>
					</div>
					<div class="tkyy">
					  <label for="tkzh"><span><i>*</i>退款账号：</span></label>
					  <input type="text" id="tkzh" name="Account" value="" />
					</div>
					<div class="tkyy">
					  <label for="tkyy"><span><i>*</i>退款原因：</span></label>
					  <textarea id="tkyy" placeholder="1-200个字" name="Reason"></textarea>
					</div>
					<a id="tjsq" href="javascript:;">提交申请</a> </div>
						<input type="hidden" name="action" value="backup_apply" />
						<input type="hidden" name="OrderID" value="<?=$output['rsOrder']['Order_ID'];?>" />
						<input type="hidden" name="ProductsID" value="<?=$output['ProductsID'];?>" />
						<input type="hidden" name="KEY" value="<?=$output['KEY'];?>" />
					</form>
				</div>
			</div>
		</div>
    </div>
</div>
<script>
    $('#tjsq').click(function(){
	    $.post(shop_ajax_url, $('#apply_form').serialize(),function(data){
		    if(data.status == 1){
			    location.href = data.url;
			}else{
			    alert(data.msg);
			}
		},'json');
	});
</script>