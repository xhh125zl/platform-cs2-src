<?php
require_once($rsBiz["Skin_ID"].'/top.php');
?>
<style type="text/css">
body{background:#f5f5f5}
</style>
 <div id="intro">
   <div class="company_info">
      <img src="<?php echo $rsBiz["Biz_Logo"];?>" />
      <span><?php echo $rsBiz["Biz_Name"];?></span>
      <div class="clear"></div>
   </div>
   <div class="introduce"><?php echo $rsBiz["Biz_Introduce"];?></div>
 </div>
<?php
require_once($rsBiz["Skin_ID"].'/footer.php');
?>