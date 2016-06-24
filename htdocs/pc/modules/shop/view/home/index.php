<style>
	#hotBox{
		height:300px;
		overflow:hidden;
	}
</style>
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/index.js"></script> 
<script>
    var ajax_url = '<?php echo url('index/index',array('UsersID'=>$output['UsersID']));?>';
	$(document).ready(function(){
		index_obj.index_init();
	});
</script>
<div class="comtent">
	<?php include(__DIR__ . '/_menu.php');?>
	<div id="banner_tabs" class="flexslider">
		<ul class="slides">
		<?php if(!empty($output['focus_list'])){?>
		<?php foreach($output['focus_list'] as $key => $val){?>
			<li> <a title="<?php echo $val['title'];?>" target="_blank" href="<?php echo $val['link'];?>"> <img width="1920" height="482" alt="" style="background: url(<?php echo $val['pic']?>) no-repeat center;" src="/static/pc/shop/images/alpha.png"> </a> </li>
		<?php }?>
		<?php }?>
		</ul>
		<ul class="flex-direction-nav">
			<li><a class="flex-prev" href="javascript:;">Previous</a></li>
			<li><a class="flex-next" href="javascript:;">Next</a></li>
		</ul>
		<ol id="bannerCtrl" class="flex-control-nav flex-control-paging">
		<?php if(!empty($output['focus_list'])){?>
		<?php foreach($output['focus_list'] as $key => $val){?>
			<li class="<?php echo $key==0 ? 'active' : '';?>"><a><?php echo $key+1?></a></li>
		<?php }?>
		<?php }?>
		</ol>
	</div>
</div>
<div class="hs_bg">
	<div class="hot_shopping">
		<div class="hs_h2"><span class="agine" page="1"><a href="javascript:void(0);">换一批</a></span>
			<h2>热卖商品</h2>
		</div>
		
		<div id="hotBox"></div>
		
	</div>
</div>
<div class="clear"></div>
<!--StandardLayout Begin--> 
<?php echo htmlspecialchars_decode($output['web_html']['index']);?> 
<!--StandardLayout End-->
<div id="box">
	<ul>
	<?php if(!empty($output['block_list'])){?>
	<?php foreach($output['block_list']  as $k => $v){?>
		<li style="width:auto" ret="<?php echo $k;?>" style="overflow:hidden;"> <a class="num" href="#f<?php echo $v['web_id']?>"><?php echo $v['web_name']?></a> <a class="word" href="#f<?php echo $v['web_id']?>" ><?php echo $v['web_name']?></a> </li>
	<?php }?>
	<?php }?>
	</ul>
</div>

<!-- 例如美妆护肤自动切换的代码begin--> 
<script>
        $(function(){
            <!--调用Luara示例-->
            $(".mc").each(function(index, element) {
               $(this).luara({width:"276",height:"564",interval:3000,selected:"seleted",deriction:"left"});
            });
        });
</script> 
<!-- 例如美妆护肤自动切换的代码end--> 
<script src="/static/pc/shop/js/slider.js"></script> 
<script src="/static/pc/shop/js/tab_change.js"></script>
<script type="text/javascript">jQuery(".tab").slide({delayTime:0 });</script> 