<?php require_once('top.php'); ?>

<body>
<link href='/static/api/shop/skin/23/cate.css' rel='stylesheet' type='text/css' />
<div id="shop_page_contents">
	<div id="cover_layer"></div>
    <!--header-->
 	<div id="header_common">
  		<div class="logo"><img src="/static/api/shop/skin/23/logo.png" /></div>
  		<div class="remark"><span onClick="history.go(-1);"></span>分类列表</div>
  		<div class="clear"></div>
 	</div>
    <!--banner-->
 	<div class="search">
       <form action="<?=$base_url?>api/shop/search.php" method="get">
            <input type="text" name="kw" class="input" value="" placeholder="输入商品名称..." />
            <input type="hidden" name="UsersID" value="<?=$UsersID?>"/>
            <input type="submit" class="submit" value=" " />
        </form>
    </div>
    <!--lists-->
    <div id="catelist">
    	<dl>
        	<?php foreach($child_category_list  as $key=>$item):?>
            <dt><img src="/static/api/shop/skin/23/arrow.png" /><a  href="<?=$shop_url?>category/<?=$item['Category_ID']?>/"><?=$item['Category_Name']?></a></dt>
            <?php endforeach;?>
	    </dl>	
	</div>
</div>
<div id="footer_points"></div>
<!--页脚导航 begin-->
<?php
require_once($rsConfig['Skin_ID']."/footer.php");
?>
<!--页脚导航 end-->

</body>
</html>