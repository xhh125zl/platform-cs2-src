<?php require_once($rsBiz["Skin_ID"]."/top.php");?>
 <div id="mulu">
    <?php
		foreach($categorys as $f){
	?>
     <dl>
      <dt><a href="<?php echo $biz_url;?>products/<?php echo $f["Category_ID"];?>/"><?php echo $f["Category_Name"];?></a></dt>
      <dd>
      <?php
	  	 if(!empty($f["child"])){
		 foreach($f["child"] as $s){
	  ?>
       	<a href="<?php echo $biz_url;?>products/<?php echo $s["Category_ID"];?>/" class="mulu_left"><?php echo $s["Category_Name"];?></a>
      <?php }}?>
      <div class="clear"></div>
      </dd>
     </dl>
    <?php }?>
 </div>
<?php require_once($rsBiz["Skin_ID"]."/footer.php");?>