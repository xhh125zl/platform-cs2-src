<?php require_once('top.php'); ?>
<body>
<link href="/static/api/cloud/css/index.css" rel="stylesheet" type="text/css">
<link href='/static/js/plugin/flexslider/flexslider.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script>
<script type='text/javascript' src='/static/api/shop/js/toast.js'></script> 
<script type='text/javascript' src='/static/api/cloud/js/index.js?t=124'></script>
<script>
var UsersID = '<?php echo $UsersID;?>';
var cloud_url = '<?php echo $cloud_jjjxurl;?>';
var OwnerID = <?php echo $owner['id'];?>;
var BizID = '<?=$BizID ?>';
var ActiveID = '<?=$ActiveID ?>';

$(document).ready(function(){
	$('.flexslider').flexslider({
		animation:"slide",
		directionNav: false,
		controlNav: true, 
		touch: true //是否支持触屏滑动
	});
	index_obj.index_init();
});
</script>
<style>
	.banner {
		/*height: 180px;*/
		max-height: 250px;
		width: 100%;
		padding:0;
		margin:0;
		overflow:hidden;
	}
    .banner *{
		height: 100%;
		overflow: hidden;
		width: 100%;
		padding:0;
		margin:0;
		line-height:250px;
	}
	ol.flex-control-paging li a{display: none;}
</style>
<!-- 焦点图 -->
<?php if(!empty($slide_list)){?>
<div class="banner">
	<div class="flexslider">
		<ul class="slides">
		<?php foreach($slide_list as $key => $val){?>
			<li><a href="<?php echo $val['slide_link'];?>"><img src="<?php echo $val['slide_img'];?>" alt="<?php echo $val['slide_title'];?>"></a></li>
		<?php }?>
		</ul>
	</div>
</div>
<?php }?>
<!--导航-->
<nav id="goodsNav" class="nav-wrapper">
	<div class="nav-inner">
		<ul id="ulOrder" class="nav-list clearfix">
      <?php if(empty($BizInfo)){ ?>
		  <li order="IsRecommend" class="current" style="width:23%;"><a href="javascript:;"><span>推荐</span></a></li>
			<li order="IsNew" style="width:23%;"><a href="javascript:;"><span>最新</span></a></li>
			<li order="IsHot" style="width:23%;"><a href="javascript:;"><span>热卖</span></a></li>
			<li order="jjjx" style="width:31%;"><a href="javascript:;"><span>即将揭晓</span></a></li>
			<?php }else{ ?>
			<li order="republicTime" class="current" style="width:23%;"><a href="javascript:;"><span>按发布时间</span></a></li>
			<li order="sales" style="width:23%;"><a href="javascript:;"><span>按销量</span></a></li>
			<li order="prices" style="width:23%;"><a href="javascript:;"><span>按价格</span></a></li>
			<li order="define" style="width:31%;"><a href="javascript:;"><span>按手动</span></a></li>
			<?php } ?>
		</ul>
	</div>
	<!--点击添加或移除current-->
	<div id="divSort" class="select-btn"> <span class="select-icon"> <i></i><i></i><i></i><i></i> </span> 分类 </div>
	<!--分类-->
	<?php if(!empty($category_list)){?>
	<div class="select-total" style="display: none">
		<ul class="sort_list">
		    <li><a href="<?php echo $cloud_url.'category/0/'?>">全部分类</a></li>
			<?php foreach($category_list as $key => $val){?>
				<li><a href="<?php echo $cloud_url.'category/'.$val['Category_ID'].'/';?>"><?php echo $val['Category_Name'];?></a></li>
			<?php }?>
		</ul>
	</div>
	<?php }?>
</nav>
	<!--商品列表-->
<div class="goods-wrap marginB">
	<ul id="ulGoodsList" class="goods-list clearfix"></ul>
	<div class="loading clearfix" style="display:none;" page="1">加载更多</div>
</div>
<!--底部-->
<?php require_once('footer.php');?>
<div id="div_fastnav" class="fast-nav-wrapper">
	<ul class="fast-nav">
		<li id="li_top" style="display: none;"><a href="javascript:;"><i class="nav-top"></i></a></li>
	</ul>
</div>
<script>
$(function () {  
	$(window).scroll(function(){  
		if ($(window).scrollTop()>100){  
			$("#div_fastnav li_top").fadeIn(1500);  
		}  else  {  
			$("#div_fastnav li_top").fadeOut(1500);  
		}  
	});
	//当点击跳转链接后，回到页面顶部位置
	$("#div_fastnav li_top").click(function(){
		$('body,html').animate({scrollTop:0},1000);  
			return false;  
	});  
});
</script>
</body>
</html>