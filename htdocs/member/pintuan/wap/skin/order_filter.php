<?php
	$filter_array = array(
				"sales"=>"销量",
				"price"=>"价格",
				"comments"=>"评价"
				);

?>

<ul>
	<?php foreach($filter_array as $key=>$item):?>
    	<?php if($key == $order_by):?>	
    		<li><a href="<?=$shop_url?><?=$key?>" class="cur"><?=$item?></a></li>
		<?php else:?>
        	<li><a href="<?=$shop_url?><?=$key?>"><?=$item?></a></li>
        <?php endif;?>
	<?php endforeach;?>
	
	<div class="clear"></div>
</ul>