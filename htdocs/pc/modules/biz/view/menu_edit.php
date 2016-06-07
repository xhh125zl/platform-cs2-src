<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<div class="r_nav">
			<ul>
				<li class=""><a href="<?php echo url('store_decoration/index_setting');?>">店铺设置</a></li>
				<li class="cur"><a href="<?php echo url('store_decoration/menu_setting');?>">导航设置</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<form class="r_con_form" method="post" action="<?php echo url('store_decoration/menu_edit');?>">
				<div class="rows">
					<label>栏目名称</label>
					<span class="input">
					<input type="text" name="name" value="<?php echo $output['menu_info']['menu_name']?>" class="form_input" size="35" maxlength="100"/>
					<font class="fc_red">*</font></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>链接地址</label>
					<span class="input">
					<input type="text" name="link" value="<?php echo $output['menu_info']['menu_link']?>" class="form_input" size="35" maxlength="100"/>
					<font class="fc_red">*</font></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>排序</label>
					<span class="input">
					<input type="text" name="sort" value="<?php echo $output['menu_info']['menu_sort']?>" class="form_input" size="5" maxlength="10" />
					<span class="tips">&nbsp;注:越小越靠前.</span> </span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>新窗口打开</label>
					<span class="input">
					<label><input type="radio" value="1" <?php if($output['menu_info']['menu_target'] == 1){?> checked="checked"<?php }?> name="target"/>是</label>&nbsp;&nbsp;
					<label><input type="radio" value="0" <?php if($output['menu_info']['menu_target'] == 0){?> checked="checked"<?php }?> name="target"/>否</label>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label></label>
					<span class="input">
					<input type="submit" class="btn_green" name="submit_button" value="提交保存" />
					<a href="javascript:void(0);" onClick="history.go(-1);" class="btn_gray">返回</a></span>
					<div class="clear"></div>
				</div>
				<input type="hidden" name="id" value="<?php echo $output['menu_info']['id']?>" />
			</form>
		</div>
	</div>
</div>