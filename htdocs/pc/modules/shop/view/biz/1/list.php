<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/liebiao_css.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $output['_site_url'];?>/static/pc/shop/biz/<?php echo $output['rsBiz']['PC_Skin_ID'];?>/style.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/biz/<?php echo $output['rsBiz']['PC_Skin_ID'];?>/list.js"></script> 
<script>
<?php
    $query = array();
	$query['id'] = $output['rsBiz']['Biz_ID'];
    if(!empty($_GET['cid']))
		$query['cid'] = $_GET['cid'];
	if(!empty($_GET['type']))
		$query['type'] = $_GET['type'];
?>
    var ajax_url = '<?php echo url('store/list',$query);?>';
	$(document).ready(function(){
		list_obj.list_init();
	});
</script>
<div class="comtent">
	<?php include(dirname(dirname(__DIR__)) . '/home/' . '/_menu.php');?>
	<div id="store_decoration_content" class="background">
	    <?php include(__DIR__ . '/top.php');?>
		<div style="height:10px;"></div>
		<div class="wrapper">
		    <div class="ncs-main-container">
			    <div class="title">
				    <h4> 商品列表 </h4>
				</div>
				<div class="ncs-goodslist-bar">
				    <div class="ncs-search">
					    <form id="searchShop">
							<input type="text" placeholder="搜索店内商品" value="" name="search" class="text w120">
							<a class="ncs-btn" href="javascript:;">搜索</a>
						</form>
					</div>
				</div>
				<div class="content ncs-all-goods-list mb15">
				    <ul id="listBox"></ul>
				</div>
			</div>
			<div class="fanye">
			    <input type="hidden" name="page" value="1" />
                <div class="fy1"> 
				    <a href="javascript:;" title="上一页" id="up"><</a> 
				    <span><i id="cur_page">0</i>/<b id="total_page">0</b></span> 
					<a href="javascript:;" title="下一页" id="down">></a>
					<i>到</i>
                    <input type="text" id="text" maxlength="2" />
                    <i>页</i>
					<a id="submit" href="javascript:;">跳转</a> 
				</div>
            </div>
		</div>
	</div>
</div>