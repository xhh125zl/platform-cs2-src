<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<div class="r_nav">
			<ul>
				<li class=""><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class=""><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class="cur"><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<form class="r_con_form" method="post" action="<?php echo url('pc_setting/menu_add');?>">
				<div class="rows">
					<label>栏目名称</label>
					<span class="input">
					<input type="text" name="name" value="" class="form_input" size="35" maxlength="100"/>
					<font class="fc_red">*</font></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>链接地址</label>
					<span class="input">
					<input type="text" name="link" value="" class="form_input" size="35" maxlength="100"/>
					<font class="fc_red">*</font></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>排序</label>
					<span class="input">
					<input type="text" name="sort" value="0" class="form_input" size="5" maxlength="10" />
					<span class="tips">&nbsp;注:越小越靠前.</span> </span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>新窗口打开</label>
					<span class="input">
					<label><input type="radio" value="1" checked="checked" name="target"/>是</label>&nbsp;&nbsp;
					<label><input type="radio" value="0" name="target"/>否</label>
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
			</form>
		</div>
	</div>
</div>