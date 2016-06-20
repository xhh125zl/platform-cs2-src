<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>

<ul class="dialog-goodslist-s2">
  <?php foreach($output['goods_list'] as $k => $v){ ?>
  <li>
    <div onclick="select_recommend_goods(<?php echo $v['products_id'];?>);" class="goods-pic"><span class="ac-ico"></span><span class="thumb size-72x72"><i></i>
        <img goods_id="<?php echo $v['products_id'];?>" goods_price="<?php echo $v['products_pricex'];?>" market_price="<?php echo $v['products_pricey'];?>" 
        title="<?php echo $v['products_name'];?>" src="<?php echo SITE_URL . $v['ImgPath'];?>" onload="javascript:DrawImage(this,72,72);" /></span></div>
    <div class="goods-name"><a href="<?php echo url('shop/goods/index',array('id'=>$v['products_id']));?>" target="_blank"><?php echo $v['products_name'];?></a></div>
  </li>
  <?php } ?>
  <div class="clear"></div>
</ul>
<div style="display:none;" id="show_recommend_goods" class="pagination"> <?php echo $output['show_page'];?> </div>
<?php }else { ?>
<p class="no-record">暂无记录</p>
<?php } ?>
<div class="clear"></div>
<script type="text/javascript">
	$('#show_recommend_goods .demo').ajaxContent({
		target:'#show_recommend_goods_list'
	});
</script>