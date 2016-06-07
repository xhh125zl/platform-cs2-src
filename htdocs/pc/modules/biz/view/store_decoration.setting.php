<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
<style>
    .up_input{float:left;margin-right:10px;width:80px;}
	.up_input a{
		display:block;
		background: rgba(0, 0, 0, 0) url(/static/member/images/global/ok-btn-120-bg.jpg) no-repeat scroll left top;
		border: medium none;
		border-radius: 2px;
		color: #fff;
		cursor: pointer;
		height: 30px;
		line-height: 30px;
		text-align:center;
	}
</style>
<div id="iframe_page">
	<div class="iframe_content">
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="<?php echo url('store_decoration/decoration_setting');?>">店铺装修</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<form class="r_con_form" method="post" action="<?php echo url('store_decoration/decoration_setting_save');?>">
				<div class="rows">
					<label>仅显示装修内容</label>
					<span class="input">
						<label><input type="radio" value="1" <?php if($output['store_decoration_info']['store_decoration_only'] == 1){?> checked="checked"<?php }?> name="store_decoration_only"/>是</label>&nbsp;&nbsp;
						<label><input type="radio" value="0" <?php if($output['store_decoration_info']['store_decoration_only'] == 0){?> checked="checked"<?php }?> name="store_decoration_only"/>否</label>
					    <p style="color:#999;">该项设置如选择“是”，则店铺首页仅显示店铺装修所设定的内容；
如选择“否”则按标准默认风格模板延续显示页面下放内容，即左侧店铺导航、销售排行，右侧轮换广告、最新商品、推荐商品等相关店铺信息。</p>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>店铺装修</label>
					<span class="input">
						<div class="up_input">
							<a href="<?php echo url('store_decoration/decoration_edit', array('decoration_id'=>$output['store_decoration_info']['decoration_id']));?>">装修页面</a>
						</div>&nbsp;&nbsp;
						<div class="up_input">
							<a href="<?php echo url('store_decoration/decoration_build', array('decoration_id'=>$output['store_decoration_info']['decoration_id']));?>">生成页面</a>
						</div>
						<p style="color:#999;">点击“装修页面”按钮，在新窗口对店铺首页进行装修设计；
预览效果满意后，点击“生成页面”按钮则可将预览效果保存为您的“店铺装修”风格模板。</p>
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
				<input type="hidden" name="id" value="<?php echo $output['store_decoration_info']['decoration_id']?>" />
			</form>
		</div>
	</div>
</div>