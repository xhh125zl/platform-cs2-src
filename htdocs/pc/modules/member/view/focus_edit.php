<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=pc_focus',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		if(K('#PicDetail').children().length>=5){
			alert('您上传的图片数量已经超过5张，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a> <span>删除</span><input type="hidden" name="pic" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#PicDetail div span').click(function(){
		K(this).parent().remove();
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
</style>
<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<div class="r_nav">
			<ul>
				<li class=""><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class="cur"><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
			<form class="r_con_form" method="post" action="<?php echo url('pc_diy/focus_edit');?>">
				<div class="rows">
					<label>标题</label>
					<span class="input">
					    <input type="text" name="title" value="<?php echo $output['info']['title']?>" class="form_input" size="35" maxlength="100"/>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>图片</label>
					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">共可上传<span id="pic_count">1</span>张图片 建议尺寸：1920*482</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail">
						<?php if(isset($output['info']['pic'])){ ?>
						<div><a target="_blank" href="<?php echo $output['info']['pic'] ?>"> <img src="<?php echo $output['info']['pic']; ?>"></a><span>删除</span>
							<input type="hidden" name="pic" value="<?php echo $output['info']['pic'] ?>">
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>排序</label>
					<span class="input">
					<input type="text" name="sort" value="<?php echo $output['info']['sort']?>" class="form_input" size="5" maxlength="10" />
					<span class="tips">&nbsp;注:越小越靠前.</span> </span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>显示</label>
					<span class="input">
					<label><input type="radio" value="1" <?php if($output['info']['is_show'] == 1){?> checked="checked"<?php }?> name="is_show"/>是</label>&nbsp;&nbsp;
					<label><input type="radio" value="0" <?php if($output['info']['is_show'] == 0){?> checked="checked"<?php }?> name="is_show"/>否</label>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>链接地址</label>
					<span class="input">
					    <input type="text" name="link" value="<?php echo $output['info']['link'] ?>" class="form_input" size="50" maxlength="200"/>
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
				<input type="hidden" name="id" value="<?php echo $output['info']['id']?>" />
			</form>
		</div>
	</div>
</div>