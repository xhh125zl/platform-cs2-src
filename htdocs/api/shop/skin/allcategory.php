<?php include('top.php'); ?>

<body>
    <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
	<link href='/static/api/shop/skin/default/css/cate.css' rel='stylesheet' type='text/css' />
    <div class="header_search">
     <div class="search_form">
      <?php include('search_in.php'); ?>
     </div>
	 <div class="bottom_div0"></div>
	 <div class="bottom_div"></div>
    </div>
<div id="wrap">
    <div id="catelist">
    	<dl>
        	<?php foreach($CategoryList  as $key=>$parentCate):?>
            <?php
				$imgName = "block.png";
				$dd_display = "none";
				
				if($parentCate['Category_ID'] == $Select_Cat_ID){
					$imgName = "block.png";
					$dd_display = "block";
				}
				
				
			?>
            <dt><span class="fa  fa-circle" style="color:red"></span>&nbsp;&nbsp;<a class="first" href="<?=$shop_url?>category/<?=$parentCate['Category_ID']?>/"><?=$parentCate['Category_Name']?></a>
            	 <?php if(isset($parentCate['child'])):?>
                <img src="/static/api/shop/skin/default/images/<?=$imgName?>" class="img_show" />
                <?php endif?>
            </dt>
            <?php if(isset($parentCate['child'])):?>
            	<dd style="display:<?=$dd_display?>">
				<?php foreach($parentCate['child'] as $key=>$child):?>
            		<a href="<?=$shop_url?>category/<?=$child['Category_ID']?>/"><?=$child['Category_Name']?></a>
            	<?php endforeach;?>
                	<div class="clear"></div>
            
            	</dd>
            <?php endif;?> 
            <?php endforeach;?>
	    </dl>	
	</div>
</div>
<script type="text/javascript">

$(document).ready(function(){
$("#catelist dl dt img").click(function(){
	var $dd = $(this).parent('dt').next('dd');
	var is  = $dd.css('display');

	if(typeof(is) != "undefined"){
		$(this).attr('src','/static/api/shop/skin/default/images/'+is+'.png');
		$(this).prev().attr('src','/static/api/shop/skin/default/images/'+is+'.png');
		is=='none'?$('#catelist dl dd').hide():'';
		$dd.slideToggle();
	}
});



});

</script>


<!--页脚导航 begin-->
<?php
require_once("distribute_footer.php");
?>
<!--页脚导航 end-->


