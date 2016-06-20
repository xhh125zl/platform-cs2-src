<script type='text/javascript' src='/static/js/plugin/colorpicker/js/colorpicker.js' ></script>
<link href='/static/js/plugin/colorpicker/css/colorpicker.css' rel='stylesheet' type='text/css'  />
<script>
$(document).ready(function(){
	//颜色选择
	$('.color_box input').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val('#' + hex);
			$(el).css('backgroundColor', '#' + hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		},
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('.color_box input').css('backgroundColor', '#' + hex);
			$('.color_box input').val('#' + hex);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
});	
</script>
<style>
.color_box input{
	display:block;
	width:60px;
	height:24px;
	border-radius:3px;
}
</style>
<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class=""><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<form class="r_con_form" method="post" action="<?php echo url('pc_diy/block_add');?>">
				<div class="rows">
					<label>板块名称</label>
					<span class="input">
					<input type="text" name="web_name" value="" class="form_input" size="35" maxlength="100"/>
					<font class="fc_red">*</font></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>色彩风格</label>
					<span class="input">
					<div class="color_box"><input type="text" name="style_name" value="#f53444" readonly="readonly"/></div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>排序</label>
					<span class="input">
					<input type="text" name="web_sort" value="0" class="form_input" size="5" maxlength="10" />
					<span class="tips">&nbsp;注:越小越靠前.</span> </span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>显示</label>
					<span class="input">
					<label><input type="radio" value="1" checked="checked" name="web_show"/>是</label>&nbsp;&nbsp;
					<label><input type="radio" value="0" name="web_show"/>否</label>
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