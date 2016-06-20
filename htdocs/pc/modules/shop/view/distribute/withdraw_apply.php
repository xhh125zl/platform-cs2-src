<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/liebiao_css.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/withdraw_apply.js"></script>
<script>
	$(document).ready(function(){
		withdraw_apply_obj.withdraw_apply_init();
	});
</script>
<style>
    .join_tip{line-height:35px;text-align:center;font-size:20px;color:#555;}
</style>
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
<div class="list_magin">
	<div class="liebiao">
		<div id="listBox">
		    <?php echo $output['html_mes'];?>
		</div>
	</div>
</div>