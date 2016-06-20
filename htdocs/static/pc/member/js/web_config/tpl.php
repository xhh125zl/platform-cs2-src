<div class="public" id="f<?php echo $web_id;?>">
	<div class="mt">
		<div class="mt_top" style="background:<?php echo $output['style_name'];?>">
		<?php if (isset($output['code_tit']['code_info']['type']) && $output['code_tit']['code_info']['type'] == 'txt') { ?>
			<h1><?php echo $output['code_tit']['code_info']['title'];?></h1>
			<?php if(!empty($output['code_tit']['code_info']['floor'])) { ?>
			<span><?php echo $output['code_tit']['code_info']['floor'];?></span>
			<?php }?>
		<?php }else {?>
		<img src="<?php echo SITE_URL . '/uploadfiles/'. $_SESSION['Users_ID'] . '/image/' . $output['code_tit']['code_info']['pic'];?>" />
		<?php }?>
		</div>
		<div class="mt_buttom">
			<div class="mb_brand"> 
			<?php if(!empty($output['code_act']['code_info']['pic'])) { ?>
				<a href="<?php echo $output['code_act']['code_info']['url'];?>"><img src="<?php echo SITE_URL . '/uploadfiles/'. $_SESSION['Users_ID'] . '/image/' . $output['code_act']['code_info']['pic'];?>"/></a> 
			<?php }?>
			</div>
			<div class="mb_mune">
				<ul>
				<?php if (is_array($output['code_category_list']['code_info']['goods_class']) && !empty($output['code_category_list']['code_info']['goods_class'])) { ?>
				<?php foreach ($output['code_category_list']['code_info']['goods_class'] as $k => $v) {
					$rsCategory = model('shop_category')->where(array('Category_ID'=>$k))->find();
				?>
					<li><a href="<?php echo url('shop/list/index',array('UsersID'=>$_SESSION['Users_ID'], 'id'=>$rsCategory['category_id']));?>"><?php echo $rsCategory['category_name'];?></a></li>
				<?php } ?>
				<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="mc">
		<ul>
		    <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
			<?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
			<?php if (is_array($val) && !empty($val)) { ?>
			<li> <a href="<?php echo $val['pic_url'];?>" title="<?php echo $val['pic_name'];?>" target="_blank"> <img src="<?php echo SITE_URL . '/uploadfiles/' . $_SESSION['Users_ID'] . '/image/' . $val['pic_img'];?>" alt="<?php echo $val['pic_name'];?>"/></a> </li>
			<?php } ?>
			<?php } ?>
		    <?php } ?>
		</ul>
		<ol>
		    <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
			<?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
			<?php if (is_array($val) && !empty($val)) { ?>
			<li></li>
			<?php } ?>
			<?php } ?>
		    <?php } ?>
		</ol>
	</div>
	<div class="tab">
		<div class="hd">
			<ul>
			<?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
                    $i = 0;
                    ?>
			<?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
                    $i++;
                    ?>
				<li class="<?php echo $i==1 ? 'on':'';?>"><a href="javascript:void(0);"><?php echo $val['recommend']['name'];?></a></li>
			<?php } ?>
			<?php } ?>
			</ul>
		</div>
		<div class="bd">
		<?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
                    $i = 0;
                    ?>
		<?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
                    $i++;
                    ?>
		<?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
			<ul class="lh" style="display: <?php echo $i==1 ? '':'none';?>;">
			<?php foreach($val['goods_list'] as $goods_id => $v) {
			$rsProducts = model('shop_products')->where(array('Products_ID'=>$goods_id))->find();
			$JSON = json_decode($rsProducts['products_json'], TRUE);
			if(isset($JSON["ImgPath"])){
				$rsProducts['ImgPath'] = $JSON["ImgPath"][0];
			}else{
				$rsProducts['ImgPath'] =  SITE_URL . '/static/api/shop/skin/default/nopic.jpg';
			}
			?>
				<li>
					<div class="p-img ld"><a href="<?php echo url('shop/goods/index', array('id'=> $rsProducts['products_id'])); ?>"><img src="<?php echo SITE_URL . $rsProducts['ImgPath'];?>" /></a> </div>
					<div class="p-name"><a href="<?php echo url('shop/goods/index', array('id'=> $rsProducts['products_id'])); ?>"><?php echo $rsProducts['products_name']; ?></a></div>
					<div class="p-price"><strong><?php echo PriceFormatForList($rsProducts['products_pricex']); ?></strong><span><?php echo PriceFormatForList($rsProducts['products_pricey']); ?></span></div>
				</li>
			<?php }?>
			</ul>
		<?php } ?>
		<?php } ?>
		<?php } ?>
		</div>
		<div class="more" style="display:none;"> <a href="#">更多>></a> </div>
	</div>
</div>