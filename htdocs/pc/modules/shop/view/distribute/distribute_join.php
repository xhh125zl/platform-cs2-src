<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/liebiao_css.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/distribute_join.js"></script>
<script>
	$(document).ready(function(){
		distribute_join_obj.distribute_join_init();
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
		<?php if($output['error_msg'] != '4'){?>
		    <?php echo $output['html_mes'];?>
		<?php }else {?>
		    <div class="body_center_pub all_dingdan" style="min-height: auto;">
				<label style="margin-left:15px;">申请分销商</label>
				<div class="xinxi_form">
					<form action="" id="personal_form" method="POST">
					    <input type="hidden" name="action"  value="join"/>
						<div class="name" style="margin-top:20px;"><span class="xiang"><i>*</i>昵称：</span>
						    <input type="text" value="" name="User_Name" id="nicheng" />
						</div>
						<div style="margin-top:20px;"><span class="xiang"><i>*</i>手机号码：</span>
						    <input type="text" value="" name="Mobile" />
						</div>
						<a href="javascript:;" class="submit">提交</a>
					</form>
				</div>
			</div>
		<?php }?>
		</div>
	</div>
</div>