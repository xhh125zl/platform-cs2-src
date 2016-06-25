<script src="<?php echo SITE_URL;?>/static/pc/public/js/common.js"></script> 
<link href='<?php echo SITE_URL;?>/static/pc/public/css/skin.css' rel='stylesheet' type='text/css' />
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<style type="text/css">
	h3.dialog_head {
		margin: 0 !important;
	}
	.dialog_content {
		width: 610px;
		padding: 0 15px 15px 15px !important;
		overflow: hidden;
	}
	#picture_tit img{
		width:165px; 
		height:140px;
	}
	#picture_floor h2{
		font-family: Microsoft YaHei;
		font-size: 48px;
		font-weight: 100;
		color:#fff;
		line-height:58px;
		margin:0;
	}
	#picture_floor span{
		font-family: Cambria,"Hoefler Text","Liberation Serif",Times,"Times New Roman",serif;
		font-size: 18px;
		opacity: 0.5;
		text-transform: uppercase;
		width: 159px;
		color:#fff;
		line-height:25px;
		border:none;
	}
	#submitBtn{
		border-radius: 8px;
		color: #fff;
		font-size: 14px;
		height: 30px;
		line-height: 30px;
		width: 145px;
		background:#3AA0EB;
	}
	#submitBtn span{
		padding:0px;
	}
	#submitBtn:hover span{
		padding:0px;
		color: #fff;
	}
</style>
<script type="text/javascript">
	var SHOP_SITE_URL = SITE_URL = "<?php echo SITE_URL;?>";
	var UPLOAD_SITE_URL = "<?php echo SITE_URL . '/uploadfiles/' . $_SESSION['Users_ID'] . '/image';?>";
</script>
<div id="iframe_page">
	<div class="iframe_content">
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class=""><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li ><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<table class="table tb-type2" id="prompt">
			<tbody>
				<tr class="space odd">
					<th colspan="12"> <div class="title">
							<h5>操作提示</h5>
							<span class="arrow"></span> </div>
					</th>
				</tr>
				<tr>
					<td>
					    <ul>
							<li>所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。</li>
							<li>左侧的“推荐分类”没有个数限制，但是如果太多会不显示(已选择的子分类可以拖动进行排序，单击选中，双击删除)。</li>
							<li>右侧的“商品推荐模块”由于页面宽度只能加8个，商品数为8个；(“商品推荐模块”拖动进行排序)。</li>
							<li>如果产品下架或者删除（包括分类）请重新选择保存并更新。</li>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table tb-type2 nohover">
			<tbody>
				<tr>
					<td colspan="2" class="required"><label>板块内容设置：</label></td>
				</tr>
				<tr class="noborder">
					<td colspan="2" class="vatop">
					    <div class="home-templates-board-layout">
							<div class="left" style="width:180px;">
								<dl id="left_tit" style="background:<?php echo $output['web_array']['style_name'];?>;height:204px;">
									<dt> <a href="JavaScript:show_dialog('upload_tit');">编辑</a> </dt>
									<dd class="tit-txt" style="border:none;text-align:center;background:none;width:auto;height:auto;<?php if($output['code_tit']['code_info']['type'] != 'txt'){ ?>display:none;<?php } ?>">
										<div id="picture_floor">
											<h2><?php echo $output['code_tit']['code_info']['title'];?></h2>
											<span><?php echo $output['code_tit']['code_info']['floor'];?></span> </div>
									</dd>
									<dd class="tit-pic" style="border:none;<?php if($output['code_tit']['code_info']['type'] == 'txt'){ ?>display:none;<?php } ?>">
										<div id="picture_tit"> <img src="<?php echo SITE_URL.'/uploadfiles/' . $_SESSION['Users_ID'] . '/image/'.$output['code_tit']['code_info']['pic'];?>" width="165px" height="140px"/> </div>
									</dd>
								</dl>
								<dl style="padding:0;">
									<dt>
										<h4 style="color:#333;">活动图片</h4>
										<a href="JavaScript:show_dialog('upload_act');"><i class="icon-picture"></i>编辑</a> </dt>
									<dd class="act-pic">
										<div id="picture_act" class="picture">
											<?php if(!empty($output['code_act']['code_info']['pic'])) { ?>
											<img src="<?php echo SITE_URL.'/uploadfiles/' . $_SESSION['Users_ID'] . '/image/'.$output['code_act']['code_info']['pic'];?>"/>
											<?php }  ?>
										</div>
									</dd>
								</dl>
								<dl style="background:#dadada;">
									<dt>
										<h4 style="color:#333;">推荐分类</h4>
										<a href="JavaScript:show_dialog('category_list');"><i class="icon-th"></i>编辑</a>
									</dt>
									<dd class="category-list">
                                                                                <?php //if (is_array($output['code_category_list']['code_info']['goods_class']) && !empty($output['code_category_list']['code_info']['goods_class'])) { 
                                                                                if (is_array($output['goods_class']) && !empty($output['goods_class'])) { ?>
										<ul>
                                                                                        <?php //foreach($output['code_category_list']['code_info']['goods_class'] as $k => $v) { 
                                                                                        foreach($output['goods_class'] as $k => $v) { ?>
											<li title="<?php echo $v['Category_Name'];?>"><a href="javascript:void(0);"><?php echo $v['Category_Name'];?></a></li>
											<?php } ?>
										</ul>
										<?php }else { ?>
										<ul>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
											<li><a href="javascript:void(0);">子分类</a></li>
										</ul>
										<?php } ?>
									</dd>
								</dl>
							</div>
							<div class="middle" style="width:276px">
								<dl>
									<dt>
										<h4>切换广告图片</h4>
										<a href="JavaScript:show_dialog('upload_adv');">编辑</a>
									</dt>
									<dd class="adv-pic">
										<div id="picture_adv" class="picture">
										<?php if(is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) {
											$adv = current($output['code_adv']['code_info']);
										?>
										<?php if(is_array($adv) && !empty($adv)) { ?>
											<img src="<?php echo SITE_URL.'/uploadfiles/' . $_SESSION['Users_ID'] . '/image/' . $adv['pic_img'];?>"/>
										<?php } ?>
										<?php } ?>
										</div>
									</dd>
								</dl>
							</div>
							<div class="right" style="width:470px;">
								<div>
								<?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
								    <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
								    <dl recommend_id="<?php echo $key;?>">
										<dt>
										    <h4><?php echo $val['recommend']['name'];?></h4>
										    <a href="JavaScript:del_recommend(<?php echo $key;?>);"><i class="icon-remove-sign "></i>删除</a>
										    <a href="JavaScript:show_recommend_dialog(<?php echo $key;?>);"><i class="icon-shopping-cart"></i>商品块</a>
										</dt>
										<dd>
											<?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
											<ul class="goods-list">
												<?php foreach($val['goods_list'] as $k => $v) { ?>
												<li><span><a href="javascript:void(0);">
													<img title="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'], 'http')===0 ? $v['goods_pic'] : SITE_URL . '/uploadfiles/' . $_SESSION['Users_ID'] . '/image/' . $v['goods_pic'];?>"/></a></span>
												</li>
												<?php } ?>
											</ul>
											<?php }else { ?>
											<ul class="goods-list">
												<li><span><i class="icon-gift"></i></span></li>
												<li><span><i class="icon-gift"></i></span></li>
												<li><span><i class="icon-gift"></i></span></li>
												<li><span><i class="icon-gift"></i></span></li>
												<li><span><i class="icon-gift"></i></span></li>
												<li><span><i class="icon-gift"></i></span></li>
												<li><span><i class="icon-gift"></i></span></li>
												<li><span><i class="icon-gift"></i></span></li>
											</ul>
											<?php } ?>
										</dd>
								    </dl>
								    <?php } ?>
								<?php } ?>
									<div class="add-tab" id="btn_add_list" style="top:0px;"><a href="JavaScript:add_recommend();"><i class="icon-plus-sign-alt"></i>新增商品推荐模块</a>(最多8个)</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="tfoot">
					<td colspan="2" ><a href="javascript:void(0);" class="btn" id="submitBtn"><span>更新板块内容</span></a></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<!-- 标题图片 -->
<div id="upload_tit_dialog" style="display:none;">
	<form id="upload_tit_form" name="upload_tit_form" enctype="multipart/form-data" method="post" action="<?php echo url('web_api/upload_pic');?>" target="upload_pic">
		<input type="hidden" name="web_id" value="<?php echo $output['code_tit']['web_id'];?>" />
		<input type="hidden" name="code_id" value="<?php echo $output['code_tit']['code_id'];?>" />
		<input type="hidden" name="tit[pic]" value="<?php echo $output['code_tit']['code_info']['pic'];?>" />
		<input type="hidden" name="tit[url]" value="" />
		<table class="table tb-type2">
			<tbody>
				<tr>
					<td colspan="2" class="required">选择类型： </td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><label title="图片上传">
							<input type="radio" name="tit[type]" value="pic" onclick="upload_type('tit');" checked="checked">
							<span>图片上传</span> </label>
						<label title="文字类型">
							<input type="radio" name="tit[type]" value="txt" onclick="upload_type('tit');" >
							<span>文字类型</span></label></td>
					<td class="vatop tips"></td>
				</tr>
			</tbody>
		</table>
		<table id="upload_tit_type_pic" class="table tb-type2" >
			<tbody>
				<tr>
					<td colspan="2" class="required">标题图片上传：</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform">
						<span class="type-file-box">
							<input type='text' name='textfield' id='textfield1' class='type-file-text' />
							<input type='button' name='button' id='button1' value='' class='type-file-button' />
							<input name="pic" id="pic" type="file" class="type-file-file" size="30">
						</span>
					</td>
					<td class="vatop tips">建议上传宽165*高140像素GIF\JPG\PNG格式图片，超出规定范围的图片部分将被自动隐藏。</td>
				</tr>
			</tbody>
		</table>
		<table id="upload_tit_type_txt" class="table tb-type2" style="display:none;">
			<tbody>
				<tr>
					<td colspan="2" class="required">版块标题：</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input class="txt" type="text" name="tit[title]" id="tit_title" value=""></td>
					<td class="vatop tips">如鞋包配饰、男女服装、运动户外。</td>
				</tr>
				<tr>
					<td colspan="2" class="required">楼层英文名：</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input class="txt" type="text" name="tit[floor]" id="tit_floor" value=""></td>
					<td class="vatop tips">楼层名称的英文名称。</td>
				</tr>
			</tbody>
		</table>
		<a href="JavaScript:void(0);" onclick="$('#upload_tit_form').submit();" class="btn"><span>提交</span></a>
	</form>
</div>
<!-- 推荐分类模块 -->
<div id="category_list_dialog" style="display:none;">
	<div class="dialog-handle">
		<h4 class="dialog-handle-title">添加推荐分类</h4>
		<p>
		    <span class="handle">
				<select name="gc_parent_id" id="gc_parent_id" class=" w200" onchange="get_goods_class();">
					<option value="0">-请选择-</option>
					<?php if(!empty($output['parent_goods_class']) && is_array($output['parent_goods_class'])) { ?>
					<?php foreach($output['parent_goods_class'] as $k => $v) { ?>
					<option value="<?php echo $v['Category_ID'];?>"><?php echo $v['Category_Name'];?></option>
					<?php } ?>
					<?php } ?>
				</select>
			</span> 
			<span class="note">从分类下拉菜单中选择该板块要推荐的分类，选择父级分类将包含字分类。</span>
		</p>
	</div>
	<form id="category_list_form">
		<input type="hidden" name="web_id" value="<?php echo $output['code_category_list']['web_id'];?>">
		<input type="hidden" name="code_id" value="<?php echo $output['code_category_list']['code_id'];?>">
		<div class="s-tips">小提示：双击分类名称可删除不想显示的分类</div>
		<div class="category-list category-list-edit">
			<dl>
				<?php //if (is_array($output['code_category_list']['code_info']['goods_class']) && !empty($output['code_category_list']['code_info']['goods_class'])) { 
                                if (is_array($output['goods_class']) && !empty($output['goods_class'])) { ?>
				<?php //foreach($output['code_category_list']['code_info']['goods_class'] as $k => $v) { 
                                foreach($output['goods_class'] as $k => $v) { ?>
				<dd gc_id="<?php echo $v['Category_ID'];?>" title="<?php echo $v['Category_Name'];?>" ondblclick="del_goods_class(<?php echo $v['Category_ID'];?>);"> <i onclick="del_goods_class(<?php echo $v['Category_ID'];?>);" style="color:#fff;">x</i><?php echo $v['Category_Name'];?>
					<input name="category_list[goods_class][<?php echo $v['Category_ID'];?>][Category_ID]" value="<?php echo $v['Category_ID'];?>" type="hidden">
					<input name="category_list[goods_class][<?php echo $v['Category_ID'];?>][Category_Name]" value="<?php echo $v['Category_Name'];?>" type="hidden">
				</dd>
				<?php } ?>
				<?php } ?>
			</dl>
		</div>
		<a href="JavaScript:void(0);" onclick="update_category();" class="btn ml30"><span>保存</span></a>
	</form>
</div>
<!-- 活动图片 -->
<div id="upload_act_dialog" class="upload_act_dialog" style="display:none;">
	<table class="table tb-type2">
		<tbody>
			<tr class="space odd" id="prompt">
				<th class="nobg" colspan="12"> <div class="title">
						<h5>操作提示</h5>
						<span class="arrow"></span> </div>
				</th>
			</tr>
			<tr>
				<td><ul>
						<li>建议图片大小：宽度：159px&nbsp;&nbsp;高度：185px</li>
					</ul></td>
			</tr>
		</tbody>
	</table>
	<form id="upload_act_form" name="upload_act_form" enctype="multipart/form-data" method="post" action="<?php echo url('web_api/upload_pic');?>" target="upload_pic">
		<input type="hidden" name="web_id" value="<?php echo $output['code_act']['web_id'];?>" />
		<input type="hidden" name="code_id" value="<?php echo $output['code_act']['code_id'];?>" />
		<input type="hidden" name="act[pic]" value="<?php echo $output['code_act']['code_info']['pic'];?>" />
		<input type="hidden" name="act[type]" value="pic" />
		<table class="table tb-type2" id="upload_act_type_pic">
			<tbody>
				<tr>
					<td colspan="2" class="required">活动名称：</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input class="txt" type="text" name="act[title]" value="<?php echo $output['code_act']['code_info']['title'];?>"></td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label>图片跳转链接：</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input name="act[url]" value="<?php echo !empty($output['code_act']['code_info']['url']) ? $output['code_act']['code_info']['url'] : $output['_site_url'];?>" class="txt" type="text"></td>
					<td class="vatop tips">输入点击该图片后所要跳转的链接地址。</td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label>活动图片上传：</label></td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><span class="type-file-box">
						<input type='text' name='textfield' id='textfield1' class='type-file-text' />
						<input type='button' name='button' id='button1' value='' class='type-file-button' />
						<input name="pic" id="pic" type="file" class="type-file-file" size="30" />
						</span></td>
					<td class="vatop tips">建议上传宽212*高280像素GIF\JPG\PNG格式图片，超出规定范围的图片部分将被自动隐藏。</td>
				</tr>
			</tbody>
		</table>
		<a href="JavaScript:void(0);" onclick="$('#upload_act_form').submit();" class="btn"><span>提交</span></a>
	</form>
</div>
<!-- 商品推荐模块 -->
<div id="recommend_list_dialog" style="display:none;">
	<form id="recommend_list_form">
		<input type="hidden" name="web_id" value="<?php echo $output['code_recommend_list']['web_id'];?>">
		<input type="hidden" name="code_id" value="<?php echo $output['code_recommend_list']['code_id'];?>">
		<div id="recommend_input_list" style="display:none;"><!-- 推荐拖动排序 --></div>
		<?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
		<?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
		<dl select_recommend_id="<?php echo $key;?>">
			<dt>
				<h4 class="dialog-handle-title">商品推荐模块标题名称</h4>
				<div class="dialog-handle-box"><span class="left">
					<input name="recommend_list[<?php echo $key;?>][recommend][name]" value="<?php echo $val['recommend']['name'];?>" type="text" class="w200">
					</span><span class="right">修改该区域中部推荐商品模块选项卡名称，控制名称字符在4-8字左右，超出范围自动隐藏</span>
					<div class="clear"></div>
				</div>
			</dt>
			<dd>
				<h4 class="dialog-handle-title">推荐商品</h4>
				<div class="s-tips">小提示：单击查询出的商品选中，双击已选择的可以删除，最多8个，保存后生效。</div>
				<ul class="dialog-goodslist-s1 goods-list">
					<?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
					<?php foreach($val['goods_list'] as $k => $v) { ?>
					<li id="select_recommend_<?php echo $key;?>_goods_<?php echo $k;?>">
						<div ondblclick="del_recommend_goods(<?php echo $v['goods_id'];?>);" class="goods-pic"> <span class="ac-ico" onclick="del_recommend_goods(<?php echo $v['goods_id'];?>);"></span> <span class="thumb size-72x72"><i></i><img select_goods_id="<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'], 'http')===0 ? $v['goods_pic'] : SITE_URL . '/uploadfiles/'. $_SESSION['Users_ID'] . '/image/' . $v['goods_pic'];?>" onload="javascript:DrawImage(this,72,72);" /></span></div>
						<div class="goods-name"><a href="<?php echo url('shop/goods/index', array('id'=>$v['goods_id']));?>" target="_blank"><?php echo $v['goods_name'];?></a></div>
						<input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_id]" value="<?php echo $v['goods_id'];?>" type="hidden">
						<input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][market_price]" value="<?php echo $v['market_price'];?>" type="hidden">
						<input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_name]" value="<?php echo $v['goods_name'];?>" type="hidden">
						<input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_price]" value="<?php echo $v['goods_price'];?>" type="hidden">
						<input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_pic]" value="<?php echo $v['goods_pic'];?>" type="hidden">
					</li>
					<?php } ?>
					<?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
					<?php foreach($val['pic_list'] as $k => $v) { ?>
					<li id="select_recommend_<?php echo $key;?>_pic_<?php echo $k;?>" style="display:none;">
						<input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_id]" value="<?php echo $v['pic_id'];?>" type="hidden">
						<input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_name]" value="<?php echo $v['pic_name'];?>" type="hidden">
						<input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_url]" value="<?php echo $v['pic_url'];?>" type="hidden">
						<input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_img]" value="<?php echo $v['pic_img'];?>" type="hidden">
					</li>
					<?php } ?>
					<?php } ?>
				</ul>
			</dd>
		</dl>
		<?php } ?>
		<?php } ?>
		<div id="add_recommend_list" style="display:none;"></div>
		<h4 class="dialog-handle-title">选择要展示的推荐商品</h4>
		<div class="dialog-show-box">
			<table class="tb-type1 noborder search">
				<tbody>
					<tr>
						<th><label>选择分类</label></th>
						<td class="dialog-select-bar" id="recommend_gcategory">
						    <input type="hidden" id="cate_id" name="cate_id" value="0" class="mls_id" />
							<input type="hidden" id="cate_name" name="cate_name" value="" class="mls_names" />
							<select>
								<option value="0">-请选择-</option>
								<?php if(!empty($output['goods_class']) && is_array($output['goods_class'])) { ?>
								<?php foreach($output['goods_class'] as $k => $v) { ?>
								<option value="<?php echo $v['Category_ID'];?>"><?php echo $v['Category_Name'];?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="recommend_goods_name">商品名称</label></th>
						<td>
						    <input type="text" value="" name="recommend_goods_name" id="recommend_goods_name" class="txt">
							<a href="JavaScript:void(0);" onclick="get_recommend_goods();" class="btn-search "></a>
						</td>
					</tr>
				</tbody>
			</table>
			<div id="show_recommend_goods_list" class="show-recommend-goods-list"></div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<a href="JavaScript:void(0);" onclick="update_recommend();" class="btn"><span>保存</span></a>
	</form>
</div>
<!-- 切换广告图片 -->
<div id="upload_adv_dialog" class="upload_adv_dialog" style="display:none;">
	<form id="upload_adv_form" name="upload_adv_form" enctype="multipart/form-data" method="post" action="<?php echo url('web_api/slide_adv');?>" target="upload_pic">
		<input type="hidden" name="web_id" value="<?php echo $output['code_adv']['web_id'];?>">
		<input type="hidden" name="code_id" value="<?php echo $output['code_adv']['code_id'];?>">
		<dd>
			<h4 class="dialog-handle-title">已上传图片</h4>
			<div class="s-tips">小提示：单击图片选中修改，拖动可以排序，最少保留1个，最多可加5个，保存后生效。</div>
			<ul class="adv dialog-adv-s1">
				<?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
				<?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
				<?php if (is_array($val) && !empty($val)) { ?>
				<li slide_adv_id="<?php echo $val['pic_id'];?>">
					<div class="adv-pic"><span class="ac-ico" onclick="del_slide_adv(<?php echo $val['pic_id'];?>);"></span><img onclick="select_slide_adv(<?php echo $val['pic_id'];?>);" title="<?php echo $val['pic_name'];?>" src="<?php echo SITE_URL . '/uploadfiles/'. $_SESSION['Users_ID'] . '/image/' . $val['pic_img'];?>"/></div>
					<input name="adv[<?php echo $val['pic_id'];?>][pic_id]" value="<?php echo $val['pic_id'];?>" type="hidden">
					<input name="adv[<?php echo $val['pic_id'];?>][pic_name]" value="<?php echo $val['pic_name'];?>" type="hidden">
					<input name="adv[<?php echo $val['pic_id'];?>][pic_url]" value="<?php echo $val['pic_url'];?>" type="hidden">
					<input name="adv[<?php echo $val['pic_id'];?>][pic_img]" value="<?php echo $val['pic_img'];?>" type="hidden">
				</li>
				<?php } ?>
				<?php } ?>
				<?php } ?>
			</ul>
			<div class="add-adv"><a class="btn-add-nofloat" href="JavaScript:add_slide_adv();">新增图片</a>(最多5个)</div>
		</dd>
		<table id="upload_slide_adv" class="table tb-type2" style="display:none;">
			<tbody>
				<tr>
					<td colspan="2" class="required">文字标题：</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform"><input type="hidden" name="slide_id" value="">
						<input class="txt" type="text" name="slide_pic[pic_name]" value="">
					</td>
					<td class="vatop tips"></td>
				</tr>
				<tr>
					<td colspan="2" class="required"><label>图片跳转链接：</label></td>
				</tr>
				<tr>
					<td class="vatop rowform"><input name="slide_pic[pic_url]" value="" class="txt" type="text"></td>
					<td class="vatop tips">需要带http://</td>
				</tr>
				<tr>
					<td colspan="2" class="required">广告图片上传：</td>
				</tr>
				<tr class="noborder">
					<td class="vatop rowform">
						<span class="type-file-box">
							<input type='text' name='textfield' id='textfield1' class='type-file-text' />
							<input type='button' name='button' id='button1' value='' class='type-file-button' />
							<input name="pic" id="pic" type="file" class="type-file-file" size="30">
						</span>
					</td>
					<td class="vatop tips">建议上传宽276*高564像素GIF\JPG\PNG格式图片，超出规定范围的图片部分将被自动隐藏。</td>
				</tr>
			</tbody>
		</table>
		<a href="JavaScript:void(0);" onclick="$('#upload_adv_form').submit();" class="btn"><span>保存</span></a>
	</form>
</div>
<iframe style="display:none;" src="" name="upload_pic"></iframe>
<link href="<?php echo SITE_URL;?>/static/pc/public/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SITE_URL;?>/static/pc/public/js/perfect-scrollbar.min.js"></script> 
<script type="text/javascript" src="<?php echo SITE_URL;?>/static/pc/public/js/jquery.validation.min.js"></script> 
<script src="<?php echo SITE_URL;?>/static/pc/public/js/jquery.ajaxContent.pack.js"></script> 
<script src="<?php echo SITE_URL;?>/static/pc/public/js/jquery.ui.js"></script> 
<script src="<?php echo SITE_URL;?>/static/pc/public/js/dialog/dialog.js" id="dialog_js"></script> 
<script src="<?php echo SITE_URL;?>/static/pc/public/js/common_select.js"></script> 
<script src="<?php echo SITE_URL;?>/static/pc/public/js/jquery.mousewheel.js"></script> 
<script src="<?php echo SITE_URL;?>/static/pc/public/js/waypoints.js"></script> 
<script src="<?php echo SITE_URL;?>/static/pc/member/js/web_config/web_index.js"></script>
<script>
    $('#submitBtn').click(function(){
		$.post('<?php echo url('pc_diy/web_html');?>',{web_id:<?php echo $_GET['web_id'];?>,style_name:$('#left_tit').css('background-color')},function(data){
			if(data.status == 1) {
				alert(data.msg);
				location.href = data.url;
			}else {
				alert(data.msg);
			}
		},'json');
	});
</script>