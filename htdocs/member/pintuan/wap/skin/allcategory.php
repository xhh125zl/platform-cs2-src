<?php require_once('top.php'); ?>
<body>
<link href='/wap/css/cate.css' rel='stylesheet' type='text/css' />
<div id="shop_page_contents">
	<div id="cover_layer"></div>
    <!--header-->
	
 	<div id="header_common">
  		<div class="remark"><span onClick="history.go(-1);"></span>分类列表</div>
  		<div class="clear"></div>
 	</div>
    <!--banner-->
 	<div class="search">
       <form action="/wap/search.php" method="get">
            <input type="text" name="kw" class="input" value="" placeholder="输入商品名称..." />
            <input type="submit" class="submit" value=" " />
        </form>
    </div>
    <!--lists-->

    <div id="catelist">
    	<dl>
        	<?php foreach($cateinfos  as $key=>$parentCate){?>
            <?php
				$imgName = "none.png";
				$dd_display = "none";
				if($parentCate['catid'] == $catid){
					$imgName = "block.png";
					$dd_display = "block";
				}
				
				
			?>
            <dt><img src="/wap/images/<?=$imgName?>" class="img_show" /><a class="first" href="javascript:void(0)"><?=$parentCate['catname']?></a></dt>
            <?php if(isset($parentCate['child'])){?>
            	<dd style="display:<?=$dd_display?>">
				<?php foreach($parentCate['child'] as $key=>$child){?>
            		<a href="/wap/category.php?catid=<?=$child['id']?>"><?=$child['name']?></a>
            	<?php }?>
                	<div class="clear"></div>
            
            	</dd>
            <?php }?>    
            <?php }?>
	    </dl>	
	</div>
</div>
<div id="footer_points"></div>
<!--页脚导航 begin-->
<?php
require_once("footer.php");
?>
<!--页脚导航 end-->
<script type="text/javascript">

$("#catelist dl dt img").click(function(){
	var $dd = $(this).parent('dt').next('dd');
	var is  = $dd.css('display');
	
	if(typeof(is) != "undefined"){
		$('img.img_show').attr('src','/wap/images/none.png');
		$(this).attr('src','/wap/images/'+(is=='none' ? 'block' : 'none')+'.png');
		is=='none'?$('#catelist dl dd').hide():'';
		$dd.slideToggle();
	}
});


$("#catelist dl dt a.first").click(function(){
	var $dd = $(this).parent('dt').next('dd');
	var is  = $dd.css('display');
	$('img.img_show').attr('src','/wap/images/none.png');
	$(this).parent('dt').children('img').attr('src','/wap/images/'+(is=='none' ? 'block' : 'none')+'.png');
	is=='none'?$('#catelist dl dd').hide():'';
	$dd.slideToggle();
});

</script>
</body>
</html>
