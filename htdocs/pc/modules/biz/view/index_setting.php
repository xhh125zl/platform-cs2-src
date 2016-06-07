<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src='/static/js/plugin/colorpicker/js/colorpicker.js' ></script>
<link href='/static/js/plugin/colorpicker/css/colorpicker.css' rel='stylesheet' type='text/css'  />
<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
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
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=index_setting',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		if(K('#PicDetail').children().length>=1){
			alert('您上传的图片数量已经超过1张，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" style="width:250px;height:auto;"/></a> <span>删除</span><input type="hidden" name="banner" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#PicDetail div span').click(function(){
		K(this).parent().remove();
	});
	
	K('#ImgUpload_0').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_0').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_0').val(url);
					K('#ImgDetail_0').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_1').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_1').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_1').val(url);
					K('#ImgDetail_1').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_2').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_2').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_2').val(url);
					K('#ImgDetail_2').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_3').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_3').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_3').val(url);
					K('#ImgDetail_3').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_4').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_4').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_4').val(url);
					K('#ImgDetail_4').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
<style>
.color_box input{
	display:block;
	width:60px;
	height:24px;
	border-radius:3px;
}
.pic img{width:200px}
</style>
<div id="iframe_page">
	<div class="iframe_content">
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="<?php echo url('store_decoration/index_setting');?>">店铺设置</a></li>
				<li class=""><a href="<?php echo url('store_decoration/menu_setting');?>">导航设置</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<form class="r_con_form" method="post" action="<?php echo url('store_decoration/index_setting');?>">
				<div class="rows">
					<label>店铺banner</label>
					<span class="input"> 
						<span class="upload_file">
							<div>
								<div class="up_input">
									<input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
								</div>
								<div class="tips">共可上传<span id="pic_count">1</span>张图片，大小：1200*240像素</div>
								<div class="clear"></div>
							</div>
						</span>
					<div class="img" id="PicDetail">
						<?php if(!empty($output['rsBiz']['pc_banner'])) { ?>
						<div style="width:250px;min-height:100px;">
						    <a target="_blank" href="<?php echo $output['rsBiz']['pc_banner'];?>"> 
							    <img src="<?php echo $output['rsBiz']['pc_banner']; ?>" style="width:250px;height:auto;display:block" />
							</a>
							<span>删除</span>
							<input type="hidden" name="banner" value="<?php echo $output['rsBiz']['pc_banner'] ?>" />
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>幻灯片</label>
					<span class="input"> 
						<table class="level_table" cellspacing="0" cellpadding="5" border="0">
						    <thead>
							    <tr>
								    <td width="10%">序号</td>
									<td width="15%">幻灯片名称</td>
									<td width="30%">链接地址</td>
									<td width="25%">图片</td>
									<td width="20%">操作</td>
								</tr>
							</thead>
							<tbody>
							    <tr fieldtype="text" style="display:;">
								    <td>1</td>
									<td>
									    <input class="form_input" type="text" name="slide[0][name]" value="<?php echo empty($output['pc_slide'][0]['name']) ? '' : $output['pc_slide'][0]['name'];?>">
									</td>
									<td>
									    <input class="form_input" type="text" name="slide[0][link]" value="<?php echo empty($output['pc_slide'][0]['link']) ? '' : $output['pc_slide'][0]['link'];?>" style="width:90%;">
									</td>
									<td>
									    <label>
										    <input type="button" style="width:80px;" value="选择图片" id="ImgUpload_0">
										</label>
										<?php if(empty($output['pc_slide'][0]['ImgPath'])){?>
										<span id="ImgDetail_0" class="pic">暂无图片</span>
										<?php }else{?>
										<span class="pic" id="ImgDetail_0"><img src="<?php echo $output['pc_slide'][0]['ImgPath'];?>"></span>
										<?php }?>
										<input type="hidden" value="<?php echo empty($output['pc_slide'][0]['ImgPath']) ? '':$output['pc_slide'][0]['ImgPath'] ?>" name="slide[0][ImgPath]" id="ImgPath_0">
									</td>
									<td><a class="input_add" href="javascript:void(0);"><img src="/static/member/images/ico/add.gif"></a></td>
								</tr>
								<tr fieldtype="text" style="display:<?php echo empty($output['pc_slide'][1])?'none':'' ?>;">
								    <td>2</td>
									<td>
									    <input class="form_input" type="text" name="slide[1][name]" value="<?php echo empty($output['pc_slide'][1]['name']) ? '' : $output['pc_slide'][1]['name'] ?>">
									</td>
									<td>
									    <input class="form_input" type="text" name="slide[1][link]" value="<?php echo empty($output['pc_slide'][1]['link']) ? '' : $output['pc_slide'][1]['link'] ?>" style="width:90%;">
									</td>
									<td>
									    <label>
										    <input type="button" style="width:80px;" value="选择图片" id="ImgUpload_1">
										</label>
										<?php if(empty($output['pc_slide'][1]['ImgPath'])){?>
										<span id="ImgDetail_1" class="pic">暂无图片</span>
										<?php }else{?>
										<span class="pic" id="ImgDetail_1"><img src="<?php echo $output['pc_slide'][1]['ImgPath'];?>"></span>
										<?php }?>
										<input type="hidden" value="<?php echo empty($output['pc_slide'][1]['ImgPath']) ? '' : $output['pc_slide'][1]['ImgPath'] ?>" name="slide[1][ImgPath]" id="ImgPath_1">
									</td>
									<td><a class="input_del" href="javascript:void(0);"><img src="/static/member/images/ico/del.gif"></a></td>
								</tr>
								<tr fieldtype="text" style="display:<?php echo empty($output['pc_slide'][2])?'none':'' ?>;">
								    <td>3</td>
									<td>
									    <input class="form_input" type="text" name="slide[2][name]" value="<?php echo empty($output['pc_slide'][2]['name']) ? '' : $output['pc_slide'][2]['name'] ?>">
									</td>
									<td>
									    <input class="form_input" type="text" name="slide[2][link]" value="<?php echo empty($output['pc_slide'][2]['link']) ? '' : $output['pc_slide'][2]['link'] ?>" style="width:90%;">
									</td>
									<td>
									    <label>
										    <input type="button" style="width:80px;" value="选择图片" id="ImgUpload_2">
										</label>
										<?php if(empty($output['pc_slide'][2]['ImgPath'])){?>
										<span id="ImgDetail_2" class="pic">暂无图片</span>
										<?php }else{?>
										<span class="pic" id="ImgDetail_2"><img src="<?php echo $output['pc_slide'][2]['ImgPath'];?>"></span>
										<?php }?>
										<input type="hidden" value="<?php echo empty($output['pc_slide'][2]['ImgPath']) ? '' : $output['pc_slide'][2]['ImgPath'] ?>" name="slide[2][ImgPath]" id="ImgPath_2">
									</td>
									<td><a class="input_del" href="javascript:void(0);"><img src="/static/member/images/ico/del.gif"></a></td>
								</tr>
								<tr fieldtype="text" style="display:<?php echo empty($output['pc_slide'][3])?'none':'' ?>;">
								    <td>4</td>
									<td>
									    <input class="form_input" type="text" name="slide[3][name]" value="<?php echo empty($output['pc_slide'][3]['name']) ? '' : $output['pc_slide'][3]['name'] ?>">
									</td>
									<td>
									    <input class="form_input" type="text" name="slide[3][link]" value="<?php echo empty($output['pc_slide'][3]['link']) ? '' : $output['pc_slide'][3]['link'] ?>" style="width:90%;">
									</td>
									<td>
									    <label>
										    <input type="button" style="width:80px;" value="选择图片" id="ImgUpload_3">
										</label>
										<?php if(empty($output['pc_slide'][3]['ImgPath'])){?>
										<span id="ImgDetail_3" class="pic">暂无图片</span>
										<?php }else{?>
										<span class="pic" id="ImgDetail_3"><img src="<?php echo $output['pc_slide'][3]['ImgPath'];?>"></span>
										<?php }?>
										<input type="hidden" value="<?php echo empty($output['pc_slide'][3]['ImgPath']) ? '' : $output['pc_slide'][3]['ImgPath'] ?>" name="slide[3][ImgPath]" id="ImgPath_3">
									</td>
									<td><a class="input_del" href="javascript:void(0);"><img src="/static/member/images/ico/del.gif"></a></td>
								</tr>
								<tr fieldtype="text" style="display:<?php echo empty($output['pc_slide'][4])?'none':'' ?>;">
								    <td>5</td>
									<td>
									    <input class="form_input" type="text" name="slide[4][name]" value="<?php echo empty($output['pc_slide'][4]['name']) ? '' : $output['pc_slide'][4]['name'] ?>">
									</td>
									<td>
									    <input class="form_input" type="text" name="slide[4][link]" value="<?php echo empty($output['pc_slide'][4]['link']) ? '' : $output['pc_slide'][4]['link'] ?>" style="width:90%;">
									</td>
									<td>
									    <label>
										    <input type="button" style="width:80px;" value="选择图片" id="ImgUpload_4">
										</label>
										<?php if(empty($output['pc_slide'][4]['ImgPath'])){?>
										<span id="ImgDetail_4" class="pic">暂无图片</span>
										<?php }else{?>
										<span class="pic" id="ImgDetail_4"><img src="<?php echo $output['pc_slide'][4]['ImgPath'];?>"></span>
										<?php }?>
										<input type="hidden" value="<?php echo empty($output['pc_slide'][4]['ImgPath']) ? '' : $output['pc_slide'][4]['ImgPath'] ?>" name="slide[4][ImgPath]" id="ImgPath_4">
									</td>
									<td><a class="input_del" href="javascript:void(0);"><img src="/static/member/images/ico/del.gif"></a></td>
								</tr>
							</tbody>
						</table>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>自定义导航颜色</label>
					<span class="input">
					<div class="color_box"><input type="text" name="bg_color" value="<?php echo empty($output['pc_bg_color']) ? '#d93600' : $output['pc_bg_color'];?>" readonly="readonly"/></div>
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
<script>
	$('.level_table .input_add').click(function(){
		$('.level_table tr[FieldType=text]:hidden').eq(0).show();
		if(!$('.level_table tr[FieldType=text]:hidden').size()){
			$(this).hide();
		}
	});
	$('.level_table .input_del').click(function(){
		$('.level_table .input_add').show();
		$(this).parent().parent().hide().find('input').parent().parent().find('span.pic').html('暂无图片');
		$(this).parents('tr').find('input').val('');
	});
</script>