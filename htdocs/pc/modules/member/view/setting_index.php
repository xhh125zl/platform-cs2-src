<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
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
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" width="120px"/></a> <span>删除</span><input type="hidden" name="logo" value="'+url+'" /></div>');
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
					K('#PicDetail2').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" width="120px"/></a> <span>删除</span><input type="hidden" name="login_bg" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload3').click(function(){
		if(K('#PicDetail3').children().length>1){
			alert('您上传的图片数量已经超过1张，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail3').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" width="120px"/></a> <span>删除</span><input type="hidden" name="reg_bg" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
})
$(document).ready(function() {PicDetail
        $('#PicDetail').on('click','div span',function() {
		$(this).parent().remove();
	});
	$('#PicDetail2').on('click','div span',function() {
		$(this).parent().remove();
	});
	$('#PicDetail3').on('click','div span',function() {
		$(this).parent().remove();
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
				<li class="cur"><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
			    <li class=""><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div id="products" class="r_con_wrap">
		    <style>
			.tips_info {
				background: #f7f7f7 none repeat scroll 0 0;
				border: 1px solid #ddd;
				border-radius: 5px;
				font-size: 12px;
				line-height: 22px;
				margin-bottom: 10px;
				padding: 10px;
			}
			</style>
			<div class="tips_info">
			自定义域名生效操作：<br />
			1.自定义域名需要绑定IP<font style="color:#F00; font-size:12px;"><?php echo gethostbyname($_SERVER["SERVER_NAME"]);?></font><br />
			2.网站管理员需在服务器端绑定自定义域名<br />
			</div>
			<form class="r_con_form" method="post" action="<?php echo url('pc_setting/index');?>">
				<div class="rows">
					<label>网站logo</label>
					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">共可上传<span id="pic_count">1</span>张图片 建议尺寸：384 * 123</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail">
						<?php if(isset($output['config']['logo'])) { ?>
						<div>
						    <a target="_blank" href="<?php echo $output['config']['logo'];?>"> 
							    <img src="<?php echo $output['config']['logo']; ?>" width="120px" />
							</a>
							<span>删除</span>
							<input type="hidden" name="logo" value="<?php echo $output['config']['logo'] ?>" />
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>登录背景图</label>
					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload2" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">共可上传<span id="pic_count2">1</span>张图片 建议尺寸：450 * 350</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail2">
						<?php if(isset($output['config']['login_bg'])) { ?>
						<div>
						    <a target="_blank" href="<?php echo $output['config']['login_bg'];?>"> 
							    <img src="<?php echo $output['config']['login_bg']; ?>" width="120px" />
							</a>
							<span>删除</span>
							<input type="hidden" name="login_bg" value="<?php echo $output['config']['login_bg'] ?>" />
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">

					<label>注册二维码</label>

					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload3" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">共可上传<span id="pic_count3">1</span>张图片 建议尺寸：450 * 350</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail3">
						<?php if(isset($output['config']['reg_bg'])) { ?>
						<div>
						    <a target="_blank" href="<?php echo $output['config']['reg_bg'];?>"> 
							    <img src="<?php echo $output['config']['reg_bg']; ?>" width="120px" />
							</a>
							<span>删除</span>
							<input type="hidden" name="reg_bg" value="<?php echo $output['config']['reg_bg'] ?>" />
						</div>
						<?php }?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>自定义域名</label>
					<span class="input">
					<input type="text" name="site_url" value="<?php echo $output['config']['site_url'];?>" class="form_input" size="35" maxlength="100"/>&nbsp;例如：abc.xxxx.com
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>站点状态</label>
					<span class="input">
					<label><input type="radio" value="1" <?php if($output['config']['site_status'] == 1){?> checked="checked"<?php }?> name="site_status"/>开启</label>&nbsp;&nbsp;
					<label><input type="radio" value="0" <?php if($output['config']['site_status'] == 0){?> checked="checked"<?php }?> name="site_status"/>关闭</label>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>关闭原因</label>
					<span class="input">
					    <textarea class="form_input" rows="6" name="closed_reason"><?php echo $output['config']['closed_reason'];?></textarea>
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