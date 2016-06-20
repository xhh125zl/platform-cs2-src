<div class="main_nav_bg">
		<div class="main_nav">
			<ul class="main_nav_ul">
			<?php foreach($output['main_nav'] as $key => $val){?>
				<li><a href="<?php echo $val;?>"><?php echo $key;?></a></li>
			<?php }?>
			</ul>
			<div class="pullDown">
				<h2 class="pullDownTitle"> 所有商品分类 </h2>
				<em class="pullDown_em"><img src="/static/pc/shop/images/多边形 2.png" /></em>
				<ul class="pullDownList">
				<?php if(!empty($output['categoryTree'])){?>
				<?php foreach($output['categoryTree']  as $key => $parentCate){
				    $imgName = "block.png";
					$dd_display = "none";
					$Select_Cat_ID = !empty($_GET['categoryID'])?$_GET['categoryID']:0;
					if($parentCate['Category_ID'] == $Select_Cat_ID){
						$imgName = "block.png";
						$dd_display = "block";
					}	
				?>
					<li class="" rel="yMenuListCon<?php echo $key;?>"> <i class="listi<?=$key+1?>"></i> <a href="<?php echo url('list/index',array('id'=>$parentCate['Category_ID']));?>" target="_blank"><?=$parentCate['Category_Name']?></a> <span></span> </li>
				<?php }?>
				<?php }?>
				</ul>
				<!-- 下拉详细列表具体分类 -->
				<?php if(!empty($output['categoryTree'])){?>
				<?php foreach($output['categoryTree']  as $key => $parentCate){ ?>
				<?php if(!empty($parentCate['child'])){?>
				<div class="yMenuListCon" id="yMenuListCon<?php echo $key;?>">
					<div class="yMenuListConin">
						<div class="yMenuLCinList">
							<h3>
								<a href="<?php echo url('list/index',array('id'=>$parentCate['Category_ID']));?>" class="yListName"><?=$parentCate['Category_Name']?></a>
							</h3>
							<p>
							    <?php foreach($parentCate['child'] as $key => $child){?>
							    <a href="<?php echo url('list/index',array('id'=>$parentCate['Category_ID']));?>"><?=$child['Category_Name']?></a> 
								<?php }?>
							</p>
						</div>
					</div>
				</div>
				<?php }?>
				<?php }?>
				<?php }?>
			</div>
		</div>
	</div>