<div class="ncsl-nav">
	<!-- 不启用店铺装修 -->
	<?php if($output['store_banner']){?>
	<div class="banner">
		<a class="img" href="javascript:;">
			<div class="ncs-default-banner"><img src="<?php echo $output['store_banner'];?>" style="width:100%;"/></div>
		</a>
	</div>
	<?php }?>
	<div class="ncs-nav" id="nav" style="background-color:<?php echo $output['store_color'];?>">
		<ul>
			<li class="active"><a href="<?php echo url('store/index', array('id'=>$output['rsBiz']['Biz_ID']));?>"><span>店铺首页<i></i></span></a></li>
			<?php foreach($output['store_menu'] as $k => $v){?>
			<li class="normal"><a href="<?php if(strpos($v['menu_link'], 'http://') === false){echo 'http://' . $v['menu_link'];}else{echo $v['menu_link'];}?>" <?php echo $v['menu_target'] == 1 ? 'target="_blank"' : '';?>><span><?php echo $v['menu_name']?><i></i></span></a></li>
		    <?php }?>
		</ul>
	</div>
</div>