<link href='<?php echo $output['_site_url'];?>/static/pc/shop/css/css.css' rel="stylesheet" type="text/css" />
<script src='<?php echo $output['_site_url'];?>/static/pc/shop/js/goods.js'></script>
<script type='text/javascript' src='<?php echo $output['_site_url'];?>/static/pc/shop/js/product_attr_helper.js'></script>
<script type="text/javascript" src="<?php echo $output['_site_url'];?>/static/pc/public/js/jquery.raty/jquery.raty.min.js"></script>

<script>
    var ajax_url = '<?php echo url('list/index', array('UsersID'=>$output['UsersID'], 'id'=>$_GET['id']));?>';
	var Products_ID = <?php echo $_GET['id'];?>;
	var is_virtual = <?php echo $output['rsProducts']['products_isvirtual'];?>;
	$(document).ready(function(){
		goods_obj.goods_init();
		$('.raty').raty({
			path: "<?php echo $output['_site_url'];?>/static/pc/public/js/jquery.raty/img",
			readOnly: true,
			score: function() {
			  return $(this).attr('data-score');
			}
		});
		$('.raty').css({'width':'130px'});
	});
</script>
<div class="comtent">
	<?php include(__DIR__ . '/_menu.php');?>
</div>
<div class="breadcrumb">
	<?php if(!empty($output['Bread'])){?>
	<?php foreach($output['Bread'] as $link => $name){?>
	<span><a href="<?php echo $link;?>"><?php echo $name;?></a></span> &nbsp;>&nbsp;
	<?php }?>
	<?php }?>
</div>
<div class="product-intro">
	<div class="preview">
		<div id="vertical" class="bigImg"> <img src="<?php echo $output['rsProducts']['ImgPath'];?>" width="400" height="400" alt="" id="midimg" />
			<div style="display:none;" id="winSelector"></div>
		</div>
		<!--bigImg end-->
		<div class="smallImg">
			<div class="scrollbutton smallImgUp disabled"></div>
			<div id="imageMenu">
				<ul>
				<?php if(!empty($output['Images'])){?>
				<?php foreach($output['Images'] as $key => $val){?>
				<li <?php if($key == 0){?> id="onlickImg"<?php }?>><img src="<?php echo $val;?>"/></li>
				<?php }?>
				<?php }?>
				</ul>
			</div>
			<div class="scrollbutton smallImgDown"></div>
		</div>
		<!--smallImg end-->
		<div id="bigView" style="display:none;"><img width="800" height="800" alt="" src="" /></div>
		<span class="shoucang<?php echo $output['rsProducts']['products_isfavourite'] ? ' shoucang_hover' : '';?>"> <a href="#none">收藏<span class="sc_number"><?php echo $output['rsProducts']['favourite_products_total'];?></span></a> </span> </div>
	<div class="itemInfo">
		<div class="name">
			<h1><?php echo $output['rsProducts']['products_name'];?></h1>
		</div>
		<div class="summary">
			<div class="summary-price">
				<div class="comment">
				<span><a href="#control" onClick="$('.control_right_ul li').eq(2).click();">商品评论</a></span> 
				<div class="raty" data-score="<?php echo $output['commit']['score'];?>"></div>
				</div>
				<div class="price" style="padding:8px 0;">
					<div class="now_price">
						<span class="money_icon">现价：￥</span><price><?php echo $output['cur_price'];?></price><br />
						<span class="ago_prive">原价：￥<?php echo $output['rsProducts']['products_pricey'];?></span>
					</div>
				</div>
			</div>
			<?php if($output['rsProducts']['products_isshippingfree'] == 1) {?>
			<div class="pub freight"> <span class="dt">运费：</span>
			    <div class="pub_right">商家包邮</div>
			</div>
			<?php }?>
			<form name="addtocart_form" id="addtocart_form">
			<?php $spec_list = array();?>
			<?php if(!empty($output['specification'])) {?>
			<?php foreach($output['specification'] as $spec_key => $spec) {?>
				<div class="pub"> <span class="dt"><?php echo $spec['Name'];?>：</span>
					<div class="pub_right spec">
						<!--判断属性是复选还是单选-->  
						<?php if($spec['Attr_Type'] == 1) {?>
							<?php foreach($spec['Values'] as $key => $value){?>
							<a class="<?=($key == 0) ? 'cattsel' : '';?>"  onclick="changeAtt(this)" href="javascript:;" name="<?=$value['id']?>" title="<?=$value['label']?>">
								<?php if($key == 0){
									$spec_list[] = $value['id'];
								}?>
							    <?=$value['label']?>
							    <input style="display:none" id="spec_value_<?=$value['id']?>" type="radio" name="spec_<?=$spec_key?>" value="<?=$value['id']?>" <?=($key == 0) ? 'checked' : '';?> />
							    <i></i>
							</a>
							<?php } ?>
						<?php }else { ?>
							    <?php foreach($spec['Values'] as $key => $value){ ?>
							    <label class="label" for="spec_value_<?=$value['id']?>" onClick="changePrice()">
									<?=$value['label']?>
									<input type="checkbox" name="spec_<?=$spec_key?>[]" value="<?=$value['id']?>" id="spec_value_<?=$value['id']?>" />
							        <i></i>
								</label>
							    <?php } ?>
							    <div class="clearfix"></div>
						<?php } ?>
					</div>
				</div>
			<?php }?>
			<?php }?>
			<input type="hidden" id="spec_list" name="spec_list" value="<?=implode(',', $spec_list)?>" />
				<div class="pub amount"> <span class="dt">数量：</span>
				    <input type="hidden" id="no_attr_price" value="<?=$output['rsProducts']['products_pricex']?>"/>
					<div class="pub_right qty_box"> 
					    <a href="#none" id="minus">-</a>
						<input type="text" value="1" id="amount" name="Qty"/>
						<a href="#none" id="add">+</a>
					</div>
				</div>
			</form>
			<?php if($output['rsProducts']['products_isvirtual'] == 0) {?>
			<div class="pub button">
				<div class="b_submit"><a href="#none">立即购买</a></div>
				<div class="b_shopcar"><a href="#none">加入购物车</a></div>
			</div>
			<?php }else {?>
			<div class="pub button">
				<div class="b_submit"><a href="#none">立即购买</a></div>
			</div>
			<?php }?>
			<div class="pub promise" style="dis"></div>
			<!-- S 加入购物车弹出提示框 -->
			<div class="wzw-cart-popup">
				<dl>
					<dt>成功添加到购物车<a title="关闭" onClick="$('.wzw-cart-popup').css({'display':'none'});">X</a></dt>
					<dd>购物车共有 <strong id="bold_num"></strong> 个商品 总金额为：<em id="bold_mly" class="saleP"></em></dd>
					<dd class="btns"><a href="javascript:void(0);" class="wzw-btn-mini wzw-btn-green" onClick="location.href='<?php echo url('buy/cart');?>'">查看购物车</a> <a href="javascript:void(0);" class="wzw-btn-mini" value="" onClick="$('.wzw-cart-popup').css({'display':'none'});">继续购物</a></dd>
				</dl>
			</div>
			<!-- E 加入购物车弹出提示框 -->
		</div>
	</div>
	<div class="beibi_tui_ma">
		<h3>商家同款</h3>
		<div class="beibi_tui_posi">
			<div class="beibi_tui">
				<ul>
				<?php if(!empty($output['same_goods'])){?>
				<?php foreach($output['same_goods'] as $key => $val){?>
					<li class="<?php echo $key+1;?>">
						<div>
							<div class="beibi_tui_img"> <a href="<?php echo $val[0]['link'];?>"> <img src="<?php echo $val[0]['ImgPath'];?>" /></a> </div>
							<div class="beibi_tui_word">
								<a href="<?php echo $val[0]['link'];?>">
									<p><?php echo $val[0]['products_name'];?></p>
									<span>￥<?php echo $val[0]['products_pricex'];?></span>
								</a>
							</div>
						</div>
						<?php if(!empty($val[1])){?>
						<div>
							<div class="beibi_tui_img"> <a href="<?php echo $val[1]['link'];?>"> <img src="<?php echo $val[1]['ImgPath'];?>" /></a> </div>
							<div class="beibi_tui_word">
								<a href="<?php echo $val[1]['link'];?>">
									<p><?php echo $val[1]['products_name'];?></p>
									<span>￥<?php echo $val[1]['products_pricex'];?></span>
								</a>
							</div>
						</div>
						<?php }?>
					</li>
				<?php }?>
				<?php }?>
				</ul>
			</div>
		</div>
		<div class="up"><a href="javascript:;"></a></div>
		<div class="down"><a href="javascript:;"></a></div>
	</div>
</div>
<div class="control" id="control">
	<div class="control_right">
		<div class="control_right_ul">
			<ul>
				<li class="li_hover"><a href="#none">宝贝详情</a></li>
				<li><a href="#none">产品参数</a></li>
				<li><a href="#none">评价（<?php echo $output['commit']['num'];?>）</a></li>
			</ul>
		</div>
		<div class="control_right_all">
			<div class="cra1"><?php echo htmlspecialchars_decode($output['rsProducts']['products_description']);?></div>
			<div class="cra2" style="display:none;">
			<table cellspacing="1" cellpadding="0" width="100%">
			    <tbody>
					<?php foreach($output['properties']['pro'] as $attr_group=>$attr_list){?>
					<tr>
					    <th class="tdTitle" colspan="2"><?=$attr_group?></th>
					</tr>
					<tr></tr>
					<?php foreach($attr_list as $k=>$attr){?>
					<tr>
					    <td class="tdTitle"><?=$attr['Name']?></td>
					    <td><?=$attr['Value']?></td>
					</tr>
					<?php } ?>
					<?php } ?>
			    </tbody>
			</table>
			</div>
			<div class="cra3" style="display:none;">
			<?php if(!empty($output['commitList'])) {?>
			<?php foreach($output['commitList'] as $k => $v){?>
			    <table width="100%" cellpadding="0" cellspacing="0">
					<tr>
					    <td class="commit_time"><?php echo date("Y-m-d H:i:s",$v["createtime"]);?></td>
					</tr>
					<tr>
					    <td class="commit_note"><?php echo $v["note"];?></td>
					</tr>
					<tr>
					    <td class="commit_score"><?php echo number_format(($v["score"]), 1, '.', '');?> 分</td>
					</tr>
				</table>
			<?php }?>
			<?php }?>
			</div>
		</div>
	</div>
	<div class="see_agine">
		<div class="see_agine_h3">
			<h3>看了又看</h3>
		</div>
		<?php if(!empty($output['history_goods'])){?>
		<?php foreach($output['history_goods'] as $key => $val){?>
		<div class="see_agine_beibi">
			<div class="beibi_img "><a href="<?php echo $val['link'];?>"><img src="<?php echo $val['ImgPath'];?>" /></a></div>
			<div class="beibi_name"><a href="<?php echo $val['link'];?>"><?php echo $val['products_name'];?></a></div>
			<div class="beibi_price">￥<?php echo $val['products_pricex'];?></div>
		</div>
		<?php }?>
		<?php }?>
	</div>
</div>