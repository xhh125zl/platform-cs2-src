<link href="<?php echo $output['_site_url'];?>/static/pc/shop/biz/<?php echo $output['rsBiz']['PC_Skin_ID'];?>/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $output['_site_url'];?>/static/pc/public/js/jquery.raty/jquery.raty.min.js"></script>
<style>
.ncs-main-container .title {
    border-color: <?php echo $output['store_color'];?> #e7e7e7 #e7e7e7;
}
</style>
<div class="comtent">
	<?php include(dirname(dirname(__DIR__)) . '/home/' . '/_menu.php');?>
	<div id="store_decoration_content" class="background">
		<?php include(__DIR__ . '/top.php');?>
		<div style="height:10px;"></div>
		<div class="wrapper">
		    <div class="ncs-main">
			    <div class="flexslider">
					<ul class="slides">
						<?php if(!empty($output['store_slide']) && is_array($output['store_slide'])){?>
						<?php for($i=0; $i<count($output['store_slide']); $i++){?>
						<?php if($output['store_slide'][$i] != ''){?>
						<li><a <?php if(strpos($output['store_slide'][$i]['link'],'http://') === false){?>href="http://<?php echo $output['store_slide'][$i]['link'];?>"<?php }else{echo $output['store_slide'][$i]['link'];}?>><img alt="<?php echo $output['store_slide'][$i]['name'];?>" src="<?php echo $output['store_slide'][$i]['ImgPath'];?>"></a></li>
						<?php }?>
						<?php }?>
						<?php }?>
					</ul>
                </div>
				<?php if(!empty($output['RecProducts'])){?>
				<div class="ncs-main-container">
				    <div class="title"> 
					    <span><a class="more" href="<?php echo url('store/list',array('type'=>'rec','id'=>$output['rsBiz']['Biz_ID']))?>">更多</a></span>
                        <h4>推荐商品</h4>
                    </div>
					<div class="content ncs-goods-list">
					    <ul>
						<?php foreach($output['RecProducts'] as $key => $val){?>
						<?php $JSON = json_decode($val['Products_JSON'], true);?>
						    <li>
							    <dl>
								    <dt>
									    <a class="goods-thumb" target="_blank" href="<?php echo url('goods/index', array('id'=>$val['Products_ID']))?>">
										    <img alt="<?php echo $val['Products_Name'];?>" src="<?php echo $JSON["ImgPath"][0];?>">
										</a>
									</dt>
									<dd class="goods-name">
									    <a target="_blank" title="<?php echo $val['Products_Name'];?>" href="<?php echo url('goods/index', array('id'=>$val['Products_ID']))?>"><?php echo $val['Products_Name'];?></a>
									</dd>
									<dd class="goods-info">
									    <span class="price"><i>¥</i> <?php echo $val['Products_PriceX'];?> </span>
										<span class="goods-sold">已售：<strong><?php echo $val['Products_Sales'];?></strong> 件</span>
									</dd>
									<dd class="goods-promotion" style="display:none;"><span>推荐商品</span></dd>
								</dl>
							</li>
						<?php }?>
						</ul>
					</div>
				</div>
				<div style="height:15px;"></div>
				<?php }?>
				<?php if(!empty($output['NewProducts'])){?>
				<div class="ncs-main-container">
				    <div class="title"> 
					    <span><a class="more" href="<?php echo url('store/list',array('type'=>'new','id'=>$output['rsBiz']['Biz_ID']))?>">更多</a></span>
                        <h4>新品上市</h4>
                    </div>
					<div class="content ncs-goods-list">
					    <ul>
						<?php foreach($output['NewProducts'] as $key => $val){?>
						<?php $JSON = json_decode($val['Products_JSON'], true);?>
						    <li>
							    <dl>
								    <dt>
									    <a class="goods-thumb" target="_blank" href="<?php echo url('goods/index', array('id'=>$val['Products_ID']))?>">
										    <img alt="<?php echo $val['Products_Name'];?>" src="<?php echo $JSON["ImgPath"][0];?>">
										</a>
									</dt>
									<dd class="goods-name">
									    <a target="_blank" title="<?php echo $val['Products_Name'];?>" href="<?php echo url('goods/index', array('id'=>$val['Products_ID']))?>"><?php echo $val['Products_Name'];?></a>
									</dd>
									<dd class="goods-info">
									    <span class="price"><i>¥</i> <?php echo $val['Products_PriceX'];?> </span>
										<span class="goods-sold">已售：<strong><?php echo $val['Products_Sales'];?></strong> 件</span>
									</dd>
									<dd class="goods-promotion" style="display:none;"><span>商品上市</span></dd>
								</dl>
							</li>
						<?php }?>
						</ul>
					</div>
				</div>
				<div style="height:15px;"></div>
				<?php }?>
				<?php if(!empty($output['HotProducts'])){?>
				<div class="ncs-main-container">
				    <div class="title"> 
					    <span><a class="more" href="<?php echo url('store/list',array('type'=>'hot','id'=>$output['rsBiz']['Biz_ID']))?>">更多</a></span>
                        <h4>热卖商品</h4>
                    </div>
					<div class="content ncs-goods-list">
					    <ul>
						<?php foreach($output['HotProducts'] as $key => $val){?>
						<?php $JSON = json_decode($val['Products_JSON'], true);?>
						    <li>
							    <dl>
								    <dt>
									    <a class="goods-thumb" target="_blank" href="<?php echo url('goods/index', array('id'=>$val['Products_ID']))?>">
										    <img alt="<?php echo $val['Products_Name'];?>" src="<?php echo $JSON["ImgPath"][0];?>">
										</a>
									</dt>
									<dd class="goods-name">
									    <a target="_blank" title="<?php echo $val['Products_Name'];?>" href="<?php echo url('goods/index', array('id'=>$val['Products_ID']))?>"><?php echo $val['Products_Name'];?></a>
									</dd>
									<dd class="goods-info">
									    <span class="price"><i>¥</i> <?php echo $val['Products_PriceX'];?> </span>
										<span class="goods-sold">已售：<strong><?php echo $val['Products_Sales'];?></strong> 件</span>
									</dd>
									<dd class="goods-promotion" style="display:none;"><span>热卖商品</span></dd>
								</dl>
							</li>
						<?php }?>
						</ul>
					</div>
				</div>
				<div style="height:15px;"></div>
				<?php }?>
			</div>
			<div class="ncs-sidebar">
			    <div class="ncs-sidebar-container ncs-class-bar">
					<div class="title">
						<h4>商家简介</h4>
					</div>
					<div class="content">
						<img src="<?php echo $output['rsBiz']['Biz_Logo'];?>"/>
						<?php echo htmlspecialchars_decode($output['rsBiz']['Biz_Introduce']);?>
					</div>
					<div style="height:5px;"></div>
					<div class="title">
						<h4>店铺商品分类</h4>
					</div>
					<div style="border:1px solid #e7e7e7;">
						<ul class="ncs-submenu">
						<?php if(!empty($output['bizCategoryTree'])) {?>
						    <?php foreach($output['bizCategoryTree'] as $key => $val){?>
							<?php if(!empty($val['child'])){?>
							<li><span class="ico-none" onclick="class_list(this);" span_id="<?php echo $val['Category_ID'];?>" style="cursor: pointer;"><em>-</em></span><a href="<?php echo url('store/list', array('cid'=>$val['Category_ID'],'id'=>$output['rsBiz']['Biz_ID']));?>"><?php echo $val['Category_Name'];?></a>
								<ul id="stc_<?php echo $val['Category_ID'];?>">
								    <?php foreach($val['child'] as $v){?>
								    <li><span class="ico-sub">&nbsp;</span><a href="<?php echo url('store/list', array('cid'=>$v['Category_ID'],'id'=>$output['rsBiz']['Biz_ID']));?>"><?php echo $v['Category_Name'];?></a></li>
								    <?php }?>
								</ul>
							</li>
							<?php }else{?>
							<li> <span class="ico-none"><em>-</em></span><a href="<?php echo url('store/list', array('cid'=>$val['Category_ID'],'id'=>$output['rsBiz']['Biz_ID']));?>"><?php echo $val['Category_Name']?></a></li>
							<?php }?>
							<?php }?>
						<?php }?>
						</ul>
					</div>
					<div style="height:5px;"></div>
					<div class="title">
						<h4>商家评价</h4>
					</div>
					<div class="comment">
						<div class="raty" data-score="<?php echo $output['average_score'];?>"></div><div class="score"><?php echo $output['average_score'];?></div>
					</div>
					<div style="height:5px;"></div>
					<div class="title">
						<h4>店铺客服</h4>
					</div>
					<div class="contact">
						<?php echo htmlspecialchars_decode($output['rsBiz']['Biz_Kfcode']);?>
					</div>
					<div style="height:5px;"></div>
					<div class="title">
						<h4>店铺二维码</h4>
					</div>
					<div id="qrcode"></div>
				</div>
			</div>
		</div>
    </div>
</div>
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/qrcode.min.js"></script>
<script>
	var qrcode = new QRCode('qrcode', {
		text: "http://<?php echo $_SERVER['HTTP_HOST'];?>/api/<?php echo $output['rsBiz']['Users_ID'];?>/shop/biz/<?php echo $output['rsBiz']['Biz_ID'];?>/",
		width: 150,
		height: 150,
		colorDark : "#000000",
		colorLight : "#ffffff",
		correctLevel : QRCode.CorrectLevel.H
	});
	// 商品分类
	function class_list(obj){
		var stc_id=$(obj).attr('span_id');
		var span_class=$(obj).attr('class');
		if(span_class=='ico-block') {
			$("#stc_"+stc_id).show();
			$(obj).html('<em>-</em>');
			$(obj).attr('class','ico-none');
		}else{
			$("#stc_"+stc_id).hide();
			$(obj).html('<em>+</em>');
			$(obj).attr('class','ico-block');
		}
	}
</script>
<script>
    $(document).ready(function(){
		$('.raty').raty({
			path: "<?php echo $output['_site_url'];?>/static/pc/public/js/jquery.raty/img",
			readOnly: true,
			score: function() {
			  return $(this).attr('data-score');
			}
		});
	});
</script>
<!-- 引入幻灯片JS --> 
<script type="text/javascript" src="<?php echo $output['_site_url'];?>/static/pc/public/js/jquery.flexslider-min.js"></script> 
<!-- 绑定幻灯片事件 --> 
<script type="text/javascript">
	$(window).load(function() {
		$('.flexslider').flexslider({
			controlNav: true,
		});
	});
</script>