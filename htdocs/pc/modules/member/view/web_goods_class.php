  <?php if(!empty($output['goods_class']) && is_array($output['goods_class'])){ ?>
  <?php foreach($output['goods_class'] as $k => $v){ ?>
  <dd gc_id="<?php echo $v['Category_ID'];?>" title="<?php echo $v['Category_Name'];?>" ondblclick="del_goods_class(<?php echo $v['Category_ID'];?>);"> 
  	<i onclick="del_goods_class(<?php echo $v['Category_ID'];?>);"></i><?php echo $v['Category_Name'];?>
    <input name="category_list[goods_class][<?php echo $v['Category_ID'];?>][gc_id]" value="<?php echo $v['Category_ID'];?>" type="hidden">
    <input name="category_list[goods_class][<?php echo $v['Category_ID'];?>][gc_name]" value="<?php echo $v['Category_Name'];?>" type="hidden">
  </dd>
  <?php } ?>
  <?php } ?>
