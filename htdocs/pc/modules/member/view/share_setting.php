<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="share_rules"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=pc_setting&Users_ID=<?php echo $_SESSION["Users_ID"];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=pc_setting',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		if(K('#PicDetail').children().length>1){
			alert('您上传的图片数量已经超过1张，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" width="120px"/></a> <span>删除</span><input type="hidden" name="web_share_bg" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#PicDetail div span').click(function(){
		K(this).parent().remove();
	});
	
	K('#ImgUpload2').click(function(){
		if(K('#PicDetail2').children().length>1){
			alert('您上传的图片数量已经超过1张，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail2').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" width="120px"/></a> <span>删除</span><input type="hidden" name="diy_share_bg" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
})
$(document).ready(function() {
	$('#PicDetail2').on('click','div span',function() {
		$(this).parent().remove();
	});
});
</script>
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
				<li class=""><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class=""><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li class="cur"><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<form class="r_con_form" method="post" action="<?php echo url('pc_setting/share_setting');?>">
				<div class="rows">
					<label>网站分享页面自定义背景图</label>
					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">共可上传<span id="pic_count">1</span>张图片，建议大小：1010像素*500像素</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail">
						<?php if(isset($output['config']['web_share_bg'])) { ?>
						<div>
						    <a target="_blank" href="<?php echo $output['config']['web_share_bg'];?>"> 
							    <img src="<?php echo $output['config']['web_share_bg']; ?>" width="120px" />
							</a>
							<span>删除</span>
							<input type="hidden" name="web_share_bg" value="<?php echo $output['config']['web_share_bg'] ?>" />
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>色彩风格</label>
					<span class="input">
					<div class="color_box"><input type="text" name="web_share_bg_color" value="<?php echo $output['config']['web_share_bg_color']?:'#e95345'?>" readonly="readonly"/></div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>分享自定义图片：</label>
					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload2" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">共可上传<span id="pic_count2">1</span>张图片 建议尺寸：160 * 160</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail2">
						<?php if(isset($output['config']['diy_share_bg'])) { ?>
						<div>
						    <a target="_blank" href="<?php echo $output['config']['diy_share_bg'];?>"> 
							    <img src="<?php echo $output['config']['diy_share_bg']; ?>" width="120px" />
							</a>
							<span>删除</span>
							<input type="hidden" name="diy_share_bg" value="<?php echo $output['config']['diy_share_bg'] ?>" />
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>分享内容</label>
					<span class="input">
					<textarea name="bdText" rows="6" class="form_input"><?php echo $output['config']['bdtext'];?></textarea>
					（不要超过200字）
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>分享描述</label>
					<span class="input">
					    <textarea class="form_input" rows="6" name="bdDesc"><?php echo $output['config']['bddesc'];?></textarea>
						注：有些站点不支持，选填项（不要超过200字）
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows" style="display:none;">
				    <label>分享规则</label>
				    <span class="input">
				        <textarea name="share_rules" class="briefdesc ckeditor"><?php echo $output['config']["share_rules"] ?></textarea>
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
				<input type="hidden" name="id" value="<?php echo $output['config']['id']?>" />
			</form>
		</div>
	</div>
</div>