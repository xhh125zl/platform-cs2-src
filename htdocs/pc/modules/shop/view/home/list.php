<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/liebiao_css.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/list.js"></script> 
<style>
.highlight{font-weight:bold;color:red;}
</style>
<script>
<?php if(!empty($_GET['Keyword'])){?>
    var highlight_flag = true;
<?php }else{?>
    var highlight_flag = false;
<?php }?>
<?php
    $query['UsersID'] = $output['UsersID'];
	$query_tmp = array();
    if(!empty($_GET['id']))
		$query['id'] = $_GET['id'];
	if(!empty($_GET['Keyword']))
	    $query['Keyword'] = $_GET['Keyword'];
?>
    var ajax_url = '<?php echo url('list/index',$query);?>';
	$(document).ready(function(){
		list_obj.list_init();
	});
</script>
<div class="comtent">
	<?php include(__DIR__ . '/_menu.php');?>
</div>
<div class="breadcrumb">
<?php if(empty($_GET['Keyword'])){?>
<?php if(!empty($output['Bread'])){?>
<?php foreach($output['Bread'] as $link => $name){?>
<span><a href="<?php echo $link;?>"><?php echo $name;?></a></span> &nbsp;>&nbsp;
<?php }?>
<?php }?>
<?php }else{?>
<span><a href="<?php echo url('index/index');?>">首页</a></span> &nbsp;>&nbsp;<span>搜索结果</span>
<?php }?>
</div>
<div class="list_magin">
	<div class="liebiao">
		<div class="filt">
			<div class="filt_up" style="overflow:hidden;">
				<div class="fu_next" page="1"> <span>共<i>0</i>件商品</span> <span><i>0</i>/<em>0</em>页</span> <a href="javascript:;" id="up">上一页</a><a href="javascript:;" id="down">下一页</a> </div>
				<div class="fu_mune">
					<ul>
						<li class="fu_mune_lihover" rel="sales"><a href="javascript:;">销量</a></li>
						<li class="" rel="price"><a href="javascript:;">价格</a></li>
						<li class="" rel="time_a"><a href="javascript:;">上架时间正序</a></li>
						<li class="" rel="time_d"><a href="javascript:;">上架时间倒序</a></li>
					</ul>
				</div>
				
				<div class="fu_form">
				<?php if(empty($_GET['Keyword'])){?>
					<form>
						<input type="text" placeholder="在<?php if(!empty($output['Bread'])) echo array_pop($output['Bread']);?>里面搜索" name="k" value="" />
						<input id="list_search" value="" type="button" />&nbsp;
					</form>
				<?php }?>
				</div>
				
			</div>
			<div class="filt_down"> 
				<a href="javascript:;" rel="shipping"><i class=""></i>包邮</a> 
				<a href="javascript:;" rel="recommend"><i class=""></i>推荐</a> 
				<a href="javascript:;" rel="new"><i class=""></i>最新</a> 
				<a href="javascript:;" rel="hot"><i class=""></i>热卖</a> 
				<a href="javascript:;" rel="counts"><i class=""></i>仅显示有货</a>
				<input type="hidden" name="shaixuan" value=""/>
			</div>
		</div>
		<div id="listBox"></div>
	</div>
	<div class="hot_prodice">
		<h3>热销单品</h3>
		<?php if(!empty($output['Hot'])){?>
		<?php foreach($output['Hot'] as $key => $val){?>
		<div class="hp_list">
			<div class="hp_list_img"><a href="<?php echo $val['link'];?>" title="<?php echo $val['products_name'];?>"><img src="<?php echo $val['ImgPath'];?>" /></a></div>
			<div class="list_word">
				<div class="bb_name"><a href="<?php echo $val['link'];?>" title="<?php echo $val['products_name'];?>"><?php echo $val['products_name'];?></a></div>
				<span class="yishou hp_list_yishou">已售<i><?php echo $val['products_sales'];?></i>件</span>
				<div class="bb_proce"><span>￥<?php echo $val['products_pricex'];?></span></div>
			</div>
		</div>
		<?php }?>
		<?php }?>
	</div>
</div>
<div class="beibi_tui_ma">
	<h3>热门推荐</h3>
	<div class="beibi_tui_posi">
		<div class="beibi_tui">
			<ul>
			<?php if(!empty($output['Recommend'])){?>
		    <?php foreach($output['Recommend'] as $key => $val){?>
				<li class="1">
					<div class="beibi_tui_img"> <a href="<?php echo $val['link'];?>"> <img src="<?php echo $val['ImgPath'];?>" /></a> </div>
					<div class="beibi_tui_word"><a style="height:15px;overflow:hidden;" href="<?php echo $val['link'];?>">
						<p><?php echo $val['products_name'];?></p>
						</a><span>￥<?php echo $val['products_pricex'];?></span><i>￥<?php echo $val['products_pricey'];?></i></div>
				</li>
			<?php }?>
			<?php }?>
			</ul>
		</div>
		<div class="up"><a href="#none"></a></div>
		<div class="down"><a href="#none"></a></div>
	</div>
</div>